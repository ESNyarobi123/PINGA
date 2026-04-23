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
