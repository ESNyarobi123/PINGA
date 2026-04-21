<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'plan',
        'plan_slug',
        'amount_paid',
        'starts_at',
        'expires_at',
        'status',
        'payment_status',
        'payment_reference',
        'payment_method',
        'payment_type',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Related plan row (not the legacy string column `plan` on this model).
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->expires_at !== null
            && $this->expires_at->isFuture();
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('expires_at', '>', now());
    }

    public function planDisplayName(): string
    {
        $related = $this->subscriptionPlan()->getResults();

        return $related instanceof SubscriptionPlan
            ? $related->name
            : self::planLabel((string) ($this->plan_slug ?? $this->attributes['plan'] ?? 'msingi'));
    }

    /** @deprecated Prefer subscriptionPlan() relationship or plan_slug */
    public static function planLabel(string $plan): string
    {
        return match ($plan) {
            'msingi' => 'Msingi',
            'kawaida' => 'Kawaida',
            'bora' => 'Bora',
            'basic' => 'Msingi',
            'pro' => 'Kawaida',
            'premium' => 'Bora',
            default => ucfirst($plan),
        };
    }
}
