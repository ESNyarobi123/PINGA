<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'worker_id',
        'cover_letter',
        'proposed_budget',
        'proposed_duration',
        'status', // pending, accepted, rejected, withdrawn
        'rejection_comment',
    ];

    protected function casts(): array
    {
        return [
            'proposed_budget' => 'decimal:2',
        ];
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
}
