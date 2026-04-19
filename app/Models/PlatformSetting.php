<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'string',
        'type' => 'string',
        'group' => 'string',
        'description' => 'string',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    public function setTypedValueAttribute($value)
    {
        if ($this->type === 'json') {
            $value = json_encode($value);
        }

        $this->attributes['value'] = (string) $value;
    }

    // Scopes for groups
    public function scopeGeneral($query)
    {
        return $query->where('group', 'general');
    }

    public function scopePayment($query)
    {
        return $query->where('group', 'payment');
    }

    public function scopeSecurity($query)
    {
        return $query->where('group', 'security');
    }

    public function scopeNotifications($query)
    {
        return $query->where('group', 'notifications');
    }

    public function scopeSubscription($query)
    {
        return $query->where('group', 'subscription');
    }

    public function scopeSmartMatch($query)
    {
        return $query->where('group', 'smart_match');
    }

    public function scopeContent($query)
    {
        return $query->where('group', 'content');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('group', 'maintenance');
    }
}
