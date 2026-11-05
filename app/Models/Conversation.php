<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $fillable = [
        'job_id',
        'service_request_id',
        'employer_id',
        'worker_id',
        'employer_last_read',
        'worker_last_read',
    ];

    protected function casts(): array
    {
        return [
            'employer_last_read' => 'datetime',
            'worker_last_read' => 'datetime',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Conversations the given user takes part in.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where(function (Builder $q) use ($userId) {
            $q->where('employer_id', $userId)->orWhere('worker_id', $userId);
        });
    }

    public function otherPartyId(int $userId): ?int
    {
        return $this->employer_id === $userId
            ? $this->worker_id
            : ($this->worker_id === $userId ? $this->employer_id : null);
    }

    public function otherParty(int $userId): ?User
    {
        $otherId = $this->otherPartyId($userId);

        return $otherId ? User::find($otherId) : null;
    }

    /**
     * Title of the job or service this conversation belongs to.
     */
    public function contextLabel(): string
    {
        if ($this->job_id) {
            return (string) ($this->job?->title ?? '—');
        }

        if ($this->service_request_id) {
            return (string) ($this->serviceRequest?->service?->title ?? '—');
        }

        return __('messages.chat_box.direct');
    }

    /**
     * Count unread messages for a given user
     */
    public function unreadCount(int $userId): int
    {
        $lastRead = $this->employer_id === $userId
            ? $this->employer_last_read
            : $this->worker_last_read;

        $query = $this->messages()->where('sender_id', '!=', $userId);

        if ($lastRead) {
            $query->where('created_at', '>', $lastRead);
        }

        return $query->count();
    }

    /**
     * Mark all messages as read for a given user
     */
    public function markAsRead(int $userId): void
    {
        if ($this->employer_id === $userId) {
            $this->update(['employer_last_read' => now()]);
        } else {
            $this->update(['worker_last_read' => now()]);
        }
    }
}
