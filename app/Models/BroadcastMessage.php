<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BroadcastMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'title',
        'body',
        'channels',
        'target_type',
        'target_value',
        'scheduled_at',
        'sent_at',
        'recipient_count',
        'status',
    ];

    protected $casts = [
        'channels' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
        return match ($this->target_type) {
            'all' => 'All Users',
            'wingas' => 'Wingas (Service Providers)',
            'wateja' => 'Wateja (Clients)',
            'subscribed' => 'Subscribed Users',
            'mkoa' => 'Region: ' . ($this->target_value ?? 'Unknown'),
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

        return array_map(fn($channel) => $labels[$channel] ?? $channel, $this->channels ?? []);
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

    public function getRecipients(): \Illuminate\Support\Collection
    {
        $query = User::query();

        return match ($this->target_type) {
            'all' => $query->get(),
            'wingas' => $query->where('role', 'mfanyakazi')->get(),
            'wateja' => $query->where('role', 'muajili')->get(),
            'subscribed' => $query->whereHas('subscriptions', fn($q) => $q->where('status', 'active'))->get(),
            'mkoa' => $query->where('location', 'like', '%' . $this->target_value . '%')->get(),
            'individual' => $query->where('id', $this->target_value)->get(),
            default => collect(),
        };
    }
}
