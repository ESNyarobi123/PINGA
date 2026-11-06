<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    const PLATFORM_FEE_PERCENT = 12; // 12% Winga fee (default fallback)

    /**
     * Get the current platform fee percentage from settings
     */
    public static function getPlatformFeePercent(): float
    {
        return \App\Services\SettingsService::commissionRate();
    }

    protected $fillable = [
        'job_id',
        'service_request_id',
        'employer_id',
        'worker_id',
        'amount',
        'platform_fee',
        'worker_amount',
        'status', // pending, escrowed, released, refunded, disputed
        'payment_method', // mpesa, tigopesa, airtel_money
        'payment_reference',
        'payout_reference',
        'payout_status', // pending, processing, completed, failed
        'escrow_released_at',
        'retry_count',
        'last_retry_at',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'worker_amount' => 'decimal:2',
            'escrow_released_at' => 'datetime',
            'last_retry_at' => 'datetime',
        ];
    }

    /**
     * Calculate fees and set amounts before saving (old model: fee deducted from amount)
     */
    public static function calculatePayment(float $amount): array
    {
        $feePercent = static::getPlatformFeePercent();
        $platformFee = round($amount * ($feePercent / 100), 2);
        $workerAmount = $amount - $platformFee;

        return [
            'amount' => $amount,
            'platform_fee' => $platformFee,
            'worker_amount' => $workerAmount,
        ];
    }

    /**
     * Calculate fees from worker's bid — employer pays the bid as-is.
     * Platform commission is deducted from the worker's earnings (not added on top).
     */
    public static function calculateFromWorkerBid(float $workerBid): array
    {
        $feePercent = static::getPlatformFeePercent();
        $platformFee = round($workerBid * ($feePercent / 100), 2);
        $workerAmount = $workerBid - $platformFee;

        return [
            'amount' => $workerBid,
            'platform_fee' => $platformFee,
            'worker_amount' => $workerAmount,
        ];
    }

    /**
     * Release escrow — marks as released and creates a processing transaction.
     * Actual wallet credit happens via Snippe payout webhook.
     */
    public function releaseToWorker(): bool
    {
        if ($this->status !== 'escrowed') {
            return false;
        }

        $this->update([
            'status' => 'released',
            'payout_status' => 'processing',
            'escrow_released_at' => now(),
        ]);

        // Create processing transaction (confirmed on payout webhook)
        $label = $this->escrowItemLabel();

        Transaction::create([
            'user_id' => $this->worker_id,
            'payment_id' => $this->id,
            'type' => 'credit',
            'amount' => $this->worker_amount,
            'description' => ($this->job_id ? 'Malipo ya kazi: ' : 'Malipo ya huduma: ').$label,
            'balance_after' => $this->worker->wallet_balance,
            'status' => 'processing',
        ]);

        return true;
    }

    public function escrowItemLabel(): string
    {
        if ($this->job_id) {
            return (string) ($this->job?->title ?? '');
        }
        if ($this->service_request_id) {
            return (string) ($this->serviceRequest?->service?->title ?? '');
        }

        return '';
    }

    /**
     * Release an escrowed payment straight into the worker's wallet.
     * Idempotent: re-running it never credits the worker twice.
     *
     * @return float|null The amount credited, or null when nothing was released.
     */
    public function releaseToWorkerWallet(): ?float
    {
        return \Illuminate\Support\Facades\DB::transaction(function (): ?float {
            /** @var self|null $locked */
            $locked = static::query()->lockForUpdate()->find($this->id);

            if (! $locked || $locked->status !== 'escrowed') {
                return null;
            }

            $fees = static::calculateFromWorkerBid((float) $locked->amount);
            $workerAmount = (float) $fees['worker_amount'];

            $worker = User::query()->lockForUpdate()->find($locked->worker_id);

            if (! $worker) {
                return null;
            }

            $locked->update([
                'platform_fee' => $fees['platform_fee'],
                'worker_amount' => $workerAmount,
                'status' => 'released',
                'payout_status' => 'completed',
                'escrow_released_at' => now(),
            ]);

            $alreadyCredited = Transaction::query()
                ->where('payment_id', $locked->id)
                ->where('type', 'credit')
                ->exists();

            if (! $alreadyCredited) {
                $worker->increment('wallet_balance', $workerAmount);

                Transaction::create([
                    'user_id' => $worker->id,
                    'payment_id' => $locked->id,
                    'type' => 'credit',
                    'amount' => $workerAmount,
                    'description' => 'Malipo ya '.($locked->job_id ? 'kazi: ' : 'huduma: ').$locked->escrowItemLabel(),
                    'balance_after' => $worker->fresh()->wallet_balance,
                    'status' => 'completed',
                ]);
            }

            return $workerAmount;
        });
    }

    /**
     * Return the escrowed amount to the client/employer wallet.
     * Idempotent: re-running it never refunds the client twice.
     *
     * @return float|null The amount refunded, or null when the payment was not escrowed.
     */
    public function refundToClientWallet(): ?float
    {
        return \Illuminate\Support\Facades\DB::transaction(function (): ?float {
            /** @var self|null $locked */
            $locked = static::query()->lockForUpdate()->find($this->id);

            if (! $locked || $locked->status !== 'escrowed') {
                return null;
            }

            $amount = (float) $locked->amount;

            $debit = Transaction::query()
                ->where('payment_id', $locked->id)
                ->where('type', 'debit')
                ->first();

            if ($debit) {
                $amount = (float) $debit->amount;
            }

            $alreadyCredited = Transaction::query()
                ->where('payment_id', $locked->id)
                ->where('type', 'credit')
                ->where('user_id', $locked->employer_id)
                ->exists();

            if (! $alreadyCredited && $amount > 0) {
                $employer = User::query()->lockForUpdate()->find($locked->employer_id);

                if ($employer) {
                    $employer->increment('wallet_balance', $amount);

                    Transaction::create([
                        'user_id' => $employer->id,
                        'payment_id' => $locked->id,
                        'type' => 'credit',
                        'amount' => $amount,
                        'description' => 'Rudisho la escrow: '.$locked->escrowItemLabel(),
                        'balance_after' => $employer->fresh()->wallet_balance,
                        'status' => 'completed',
                    ]);
                }
            }

            $locked->update([
                'status' => 'refunded',
            ]);

            if ($locked->job && $locked->job->status === 'in_progress') {
                $locked->job->update(['status' => 'open']);
            }

            if ($locked->serviceRequest && $locked->serviceRequest->status === 'in_progress') {
                $locked->serviceRequest->update(['status' => 'accepted']);
            }

            return $amount;
        });
    }

    // Relationships
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
