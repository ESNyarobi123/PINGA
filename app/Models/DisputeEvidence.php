<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisputeEvidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispute_id',
        'submitted_by',
        'content',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function dispute()
    {
        return $this->belongsTo(Dispute::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function getImageUrlsAttribute(): array
    {
        if (!$this->images) {
            return [];
        }

        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    public function hasImages(): bool
    {
        return !empty($this->images);
    }

    public function hasContent(): bool
    {
        return !empty($this->content);
    }

    public function getTypeAttribute(): string
    {
        if ($this->hasImages() && $this->hasContent()) {
            return 'mixed';
        } elseif ($this->hasImages()) {
            return 'images';
        } elseif ($this->hasContent()) {
            return 'text';
        }
        return 'empty';
    }
}
