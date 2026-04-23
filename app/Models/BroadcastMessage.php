<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BroadcastMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'title',
        'body',
        'announcement_type',
        'channels',
        'target_type',
        'target_segments',
        'target_value',
        'scheduled_at',
        'sent_at',
        'recipient_count',
        'status',
    ];

    protected $casts = [
        'channels' => 'array',
        'target_segments' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Map admin UI checkbox values to canonical recipient segments.
     *
     * @param  array<int, string>  $ui
     * @return array<int, string>
     */
    public static function segmentsFromUiAudience(array $ui): array
    {
        $ui = array_values(array_unique($ui));
        if (in_array('all', $ui, true)) {
            return ['all'];
        }

        $segments = [];
        if (in_array('clients', $ui, true)) {
            $segments[] = 'wateja';
        }
        if (in_array('workers', $ui, true)) {
            $segments[] = 'wingas';
        }
        if (in_array('premium', $ui, true)) {
            $segments[] = 'subscribed';
        }

        return array_values(array_unique($segments));
    }

    /**
     * Single enum value for legacy column; use target_segments for precise delivery.
     *
     * @param  array<int, string>  $segments
     */
    public static function storageTargetTypeFromSegments(array $segments): string
    {
        $segments = array_values(array_unique($segments));
        if ($segments === [] || in_array('all', $segments, true)) {
            return 'all';
        }
        if (count($segments) === 1) {
            return $segments[0];
        }

        return 'all';
    }

    public static function baseRecipientQuery(): Builder
    {
        return User::query()->where('role', '!=', 'admin');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'scheduled']);
    }

    public function scopeReadyToSend($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now());
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'scheduled' => 'Scheduled',
            'sent' => 'Sent',
            'failed' => 'Failed',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'scheduled' => 'amber',
            'sent' => 'green',
            'failed' => 'red',
            default => 'gray',
        };
    }

    public function getTargetLabelAttribute(): string
    {
        if (is_array($this->target_segments) && $this->target_segments !== []) {
            $labels = array_map(fn (string $s) => match ($s) {
                'all' => 'All Users',
                'wingas' => 'Wingas (Service Providers)',
                'wateja' => 'Wateja (Clients)',
                'subscribed' => 'Subscribed Users',
                default => $s,
            }, $this->target_segments);

            return implode(', ', $labels);
        }

        return match ($this->target_type) {
            'all' => 'All Users',
            'wingas' => 'Wingas (Service Providers)',
            'wateja' => 'Wateja (Clients)',
            'subscribed' => 'Subscribed Users',
            'mkoa' => 'Region: '.($this->target_value ?? 'Unknown'),
            'individual' => 'Individual User',
            default => 'Unknown Target',
        };
    }

    public function hasChannel(string $channel): bool
    {
        return in_array($channel, $this->channels ?? []);
    }

    public function getChannelLabelsAttribute(): array
    {
        $labels = [
            'app' => 'In-App',
            'email' => 'Email',
            'sms' => 'SMS',
        ];

        return array_map(fn ($channel) => $labels[$channel] ?? $channel, $this->channels ?? []);
    }

    public function canSend(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_at->isPast();
    }

    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'failed']);
    }

    public function canDelete(): bool
    {
        return in_array($this->status, ['draft', 'failed']);
    }

    public function markAsSent(int $recipientCount = 0): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipient_count' => $recipientCount,
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update([
            'status' => 'failed',
        ]);
    }

    public function getRecipients(): Collection
    {
        if (is_array($this->target_segments) && $this->target_segments !== []) {
            return self::recipientsForSegments($this->target_segments);
        }

        $base = self::baseRecipientQuery();

        return match ($this->target_type) {
            'all' => $base->get(),
            'wingas' => $base->clone()->where(function ($q) {
                $q->whereIn('role', ['winga', 'mfanyakazi'])
                    ->orWhereHas('roles', fn ($r) => $r->where('name', 'winga'));
            })->get(),
            'wateja' => $base->clone()->where(function ($q) {
                $q->whereIn('role', ['mteja', 'muajili'])
                    ->orWhereHas('roles', fn ($r) => $r->where('name', 'mteja'));
            })->get(),
            'subscribed' => $base->clone()->whereHas(
                'subscriptions',
                fn ($sub) => $sub->where('status', 'active')->where('expires_at', '>', now())
            )->get(),
            'mkoa' => $base->clone()->where('location', 'like', '%'.($this->target_value ?? '').'%')->get(),
            'individual' => $base->clone()->where('id', $this->target_value)->get(),
            default => collect(),
        };
    }

    /**
     * @param  array<int, string>  $segments
     */
    private static function recipientsForSegments(array $segments): Collection
    {
        $segments = array_values(array_unique($segments));
        if ($segments === [] || in_array('all', $segments, true)) {
            return self::baseRecipientQuery()->get();
        }

        $ids = collect();
        foreach ($segments as $segment) {
            $ids = $ids->merge(match ($segment) {
                'wateja' => self::baseRecipientQuery()->where(function ($q) {
                    $q->whereIn('role', ['mteja', 'muajili'])
                        ->orWhereHas('roles', fn ($r) => $r->where('name', 'mteja'));
                })->pluck('id'),
                'wingas' => self::baseRecipientQuery()->where(function ($q) {
                    $q->whereIn('role', ['winga', 'mfanyakazi'])
                        ->orWhereHas('roles', fn ($r) => $r->where('name', 'winga'));
                })->pluck('id'),
                'subscribed' => self::baseRecipientQuery()->whereHas(
                    'subscriptions',
                    fn ($sub) => $sub->where('status', 'active')->where('expires_at', '>', now())
                )->pluck('id'),
                default => collect(),
            });
        }

        $uniqueIds = $ids->unique()->filter()->values();

        if ($uniqueIds->isEmpty()) {
            return collect();
        }

        return User::query()->whereIn('id', $uniqueIds)->get();
    }
}
