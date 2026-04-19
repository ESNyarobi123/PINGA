<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'initiator_id',
        'respondent_id',
        'status',
        'reason',
        'description',
        'priority',
        'escalated_at',
        'auto_resolve_at',
        'admin_notes',
    ];

    protected $casts = [
        'escalated_at' => 'datetime',
        'auto_resolve_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function respondent()
    {
        return $this->belongsTo(User::class, 'respondent_id');
    }

    public function evidence()
    {
        return $this->hasMany(DisputeEvidence::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInvestigating($query)
    {
        return $query->where('status', 'investigating');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeMediumPriority($query)
    {
        return $query->where('priority', 'medium');
    }

    public function scopeLowPriority($query)
    {
        return $query->where('priority', 'low');
    }

    public function scopeOverdue($query)
    {
        return $query->where('auto_resolve_at', '<', now())
            ->whereIn('status', ['open', 'investigating']);
    }

    public function getDaysOpenAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    public function getEscalationDaysAttribute(): int
    {
        if (!$this->escalated_at) {
            return 0;
        }
        return $this->escalated_at->diffInDays(now());
    }

    public function getAutoResolveDaysAttribute(): int
    {
        if (!$this->auto_resolve_at) {
            return 0;
        }
        return $this->auto_resolve_at->diffInDays(now());
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'High',
            'medium' => 'Medium',
            'low' => 'Low',
            default => 'Unknown',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'red',
            'medium' => 'amber',
            'low' => 'green',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open' => 'Open',
            'investigating' => 'Investigating',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open' => 'red',
            'investigating' => 'amber',
            'resolved' => 'green',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    public function escalate(): void
    {
        $this->update([
            'status' => 'investigating',
            'escalated_at' => now(),
        ]);
    }

    public function resolve(array $resolutionData = []): void
    {
        $this->update([
            'status' => 'resolved',
            'admin_notes' => $resolutionData['notes'] ?? $this->admin_notes,
        ]);
    }

    public function close(): void
    {
        $this->update([
            'status' => 'closed',
        ]);
    }

    public function isOverdue(): bool
    {
        return $this->auto_resolve_at && $this->auto_resolve_at->isPast();
    }

    public function canEscalate(): bool
    {
        return $this->status === 'open' && !$this->escalated_at;
    }

    public function canResolve(): bool
    {
        return in_array($this->status, ['open', 'investigating']);
    }

    public function canClose(): bool
    {
        return $this->status === 'resolved';
    }
}
