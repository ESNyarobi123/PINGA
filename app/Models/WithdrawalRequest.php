<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'charge_percent',
        'charge_amount',
        'net_amount',
        'method',
        'phone',
        'network',
        'account_number',
        'account_name',
        'bank_name',
        'status',
        'notes',
        'admin_note',
        'processed_at',
        'payout_reference',
        'payout_status',
        'payout_error',
        'retry_count',
        'last_retry_at',
        'approved_at',
        'approved_by',
        'processed_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'processed_at' => 'datetime',
            'last_retry_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ledger rows that belong to this withdrawal.
     *
     * The first debit is stored as `WD-{id}`; a retry after a refund stores
     * `WD-{id}-retry-{timestamp}` so both attempts are settled together.
     */
    public function ledgerTransactionQuery(): Builder
    {
        return Transaction::query()
            ->where('user_id', $this->user_id)
            ->whereIn('type', ['withdrawal', 'debit'])
            ->where(function (Builder $query): void {
                $query->where('reference', 'WD-'.$this->id)
                    ->orWhere('reference', 'like', 'WD-'.$this->id.'-retry-%');
            });
    }

    /**
     * Close every pending ledger row for this withdrawal.
     */
    public function settleLedger(string $status): int
    {
        return $this->ledgerTransactionQuery()
            ->where('status', 'processing')
            ->update(['status' => $status]);
    }

    /**
     * A payout the provider already accepted but has not confirmed yet.
     */
    public function hasPayoutInFlight(): bool
    {
        if ($this->payout_status !== 'processing') {
            return false;
        }

        return filled($this->payout_reference)
            || ($this->last_retry_at?->gt(now()->subMinutes(10)) ?? false);
    }

    public function methodLabel(): string
    {
        if ($this->network) {
            return match (strtolower($this->network)) {
                'airtel' => 'Airtel Money',
                'vodacom' => 'M-Pesa (Vodacom)',
                'tigo', 'tigopesa' => 'TigoPesa',
                'halopesa', 'halotel' => 'HaloPesa',
                default => ucfirst($this->network),
            };
        }

        return match ($this->method) {
            'mpesa' => 'M-Pesa',
            'tigopesa' => 'TigoPesa',
            'airtel_money' => 'Airtel Money',
            'mobile_money' => 'Mobile Money',
            'bank_transfer' => 'Bank Transfer',
            default => ucfirst($this->method),
        };
    }

    /**
     * Short provider name for display: Vodacom | Airtel | Tigo | Halopesa.
     */
    public function networkLabel(): string
    {
        return match (strtolower((string) ($this->network ?: $this->method))) {
            'airtel', 'airtel_money' => 'Airtel',
            'vodacom', 'mpesa' => 'Vodacom',
            'tigo', 'tigopesa' => 'Tigo',
            'halopesa', 'halotel' => 'Halopesa',
            'bank_transfer' => 'Bank',
            default => $this->network ? ucfirst($this->network) : 'Unknown',
        };
    }
}
