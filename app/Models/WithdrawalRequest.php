<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

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

    public function methodLabel(): string
    {
        if ($this->network) {
            return match (strtolower($this->network)) {
                'airtel'   => 'Airtel Money',
                'tigo'     => 'Mixx by Yas (TigoPesa)',
                'halopesa' => 'HaloPesa',
                default    => ucfirst($this->network),
            };
        }

        return match ($this->method) {
            'mpesa'          => 'M-Pesa',
            'tigopesa'       => 'TigoPesa',
            'airtel_money'   => 'Airtel Money',
            'mobile_money'   => 'Mobile Money',
            'bank_transfer'  => 'Bank Transfer',
            default          => ucfirst($this->method),
        };
    }
}
