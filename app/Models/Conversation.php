<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'job_id',
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

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
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
