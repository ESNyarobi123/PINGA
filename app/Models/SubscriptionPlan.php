<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'name_en',
        'price',
        'duration_days',
        'features',
        'badge_label',
        'badge_color',
        'is_recommended',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'features'       => 'array',
            'price'          => 'integer',
            'duration_days'  => 'integer',
            'is_recommended' => 'boolean',
            'is_active'      => 'boolean',
            'sort_order'     => 'integer',
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function formattedPrice(): string
    {
        return 'TZS ' . number_format($this->price);
    }

    public function durationLabel(): string
    {
        return match (true) {
            $this->duration_days >= 180 => 'Miezi 6',
            $this->duration_days >= 90  => 'Miezi 3',
            $this->duration_days >= 30  => 'Mwezi 1',
            default                     => "Siku {$this->duration_days}",
        };
    }
}
