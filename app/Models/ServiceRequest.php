<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceRequest extends Model
{
    protected $fillable = [
        'service_id',
        'service_package_id',
        'client_id',
        'message',
        'status',
        'decline_reason',
        'responded_at',
        'completion_code',
        'code_generated_at',
        'code_used_at',
        'code_hold_until',
        'hold_comment',
        'hold_extended',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
            'code_generated_at' => 'datetime',
            'code_used_at' => 'datetime',
            'code_hold_until' => 'datetime',
            'hold_extended' => 'boolean',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class, 'service_package_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'service_request_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function agreedAmount(): float
    {
        if ($this->service_package_id && $this->package) {
            return (float) $this->package->price;
        }

        return (float) $this->service->price;
    }

    /**
     * Winga (service owner) user id.
     */
    public function wingaUserId(): int
    {
        return (int) $this->service->user_id;
    }

    public function generateCompletionCode(): string
    {
        $code = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $this->update([
            'completion_code' => $code,
            'code_generated_at' => now(),
        ]);

        return $code;
    }

    public function verifyCompletionCode(string $code): bool
    {
        return $this->completion_code === $code;
    }

    public function isOnCodeHold(): bool
    {
        return $this->code_hold_until !== null && $this->code_hold_until->isFuture();
    }

    public function holdCode(int $hours = 3, ?string $comment = null): void
    {
        $this->update([
            'code_hold_until' => now()->addHours($hours),
            'hold_comment' => $comment,
        ]);
    }

    public function extendHold(string $comment): bool
    {
        if ($this->hold_extended) {
            return false;
        }

        $newHoldUntil = $this->code_hold_until && $this->code_hold_until->isFuture()
            ? $this->code_hold_until->copy()->addHours(3)
            : now()->addHours(3);

        $this->update([
            'code_hold_until' => $newHoldUntil,
            'hold_comment' => $comment,
            'hold_extended' => true,
        ]);

        return true;
    }

    public function hasExtendedHold(): bool
    {
        return (bool) $this->hold_extended;
    }
}
