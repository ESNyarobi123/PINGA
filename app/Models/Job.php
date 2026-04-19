<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_listings'; // Avoid conflict with Laravel's jobs table

    protected $fillable = [
        'employer_id',
        'category_id',
        'title',
        'title_en',
        'slug',
        'description',
        'description_en',
        'requirements',
        'requirements_en',
        'location',
        'latitude',
        'longitude',
        'budget_min',
        'budget_max',
        'budget_type', // 'fixed' or 'hourly'
        'duration',
        'status', // draft, open, in_progress, completed, cancelled, disputed
        'completion_code',
        'code_generated_at',
        'code_used_at',
        'hired_worker_id',
        'urgency', // normal, urgent, very_urgent
        'remote_allowed',
        'views_count',
        'applications_count',
        'is_approved',
        'approved_at',
        'code_hold_until',
        'hold_comment',
        'hold_extended',
        'translation_status',
    ];

    protected function casts(): array
    {
        return [
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'remote_allowed' => 'boolean',
            'code_generated_at' => 'datetime',
            'code_used_at' => 'datetime',
            'approved_at' => 'datetime',
            'code_hold_until' => 'datetime',
            'is_approved' => 'boolean',
            'remote_allowed' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title).'-'.Str::random(6);
            }
        });
    }

    /**
     * Generate 6-digit completion code
     */
    public function generateCompletionCode(): string
    {
        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $this->update([
            'completion_code' => $code, // Store plain text for easy display
            'code_generated_at' => now(),
        ]);

        return $code;
    }

    /**
     * Verify the completion code entered by worker
     */
    public function verifyCompletionCode(string $code): bool
    {
        return $this->completion_code === $code;
    }

    // Relationships
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function hiredWorker()
    {
        return $this->belongsTo(User::class, 'hired_worker_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skills');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Check if the completion code is currently on hold
     */
    public function isOnCodeHold(): bool
    {
        return $this->code_hold_until !== null && $this->code_hold_until->isFuture();
    }

    /**
     * Place a 3-hour hold on the completion code
     */
    public function holdCode(int $hours = 3, ?string $comment = null): void
    {
        $this->update([
            'code_hold_until' => now()->addHours($hours),
            'hold_comment' => $comment,
        ]);
    }

    /**
     * Extend hold by 3 more hours (can only extend once)
     */
    public function extendHold(string $comment): bool
    {
        if ($this->hold_extended) {
            return false;
        }

        $newHoldUntil = $this->code_hold_until && $this->code_hold_until->isFuture()
            ? $this->code_hold_until->addHours(3)
            : now()->addHours(3);

        $this->update([
            'code_hold_until' => $newHoldUntil,
            'hold_comment' => $comment,
            'hold_extended' => true,
        ]);

        return true;
    }

    /**
     * Check if hold has been extended already
     */
    public function hasExtendedHold(): bool
    {
        return (bool) $this->hold_extended;
    }

    /**
     * Strip phone numbers from text (regex security)
     */
    public static function sanitizePhoneNumbers(string $text): string
    {
        return preg_replace(
            '/\b(\+?255|0)[\s\-]?[67]\d[\s\-]?\d{3}[\s\-]?\d{4}\b/',
            '[NAMBA IMEFUTWA]',
            $text
        );
    }

    /**
     * Check if text contains a phone number
     */
    public static function containsPhoneNumber(string $text): bool
    {
        return (bool) preg_match(
            '/\b(\+?255|0)[\s\-]?[67]\d[\s\-]?\d{3}[\s\-]?\d{4}\b/',
            $text
        );
    }

    public function getLocalizedTitle(): string
    {
        $locale = app()->getLocale();

        return $locale === 'en' && $this->title_en
            ? $this->title_en
            : ($this->title ?? '');
    }

    public function getLocalizedDescription(): string
    {
        $locale = app()->getLocale();

        return $locale === 'en' && $this->description_en
            ? $this->description_en
            : ($this->description ?? '');
    }

    public function getLocalizedRequirements(): ?string
    {
        if (empty($this->requirements)) {
            return $this->requirements;
        }

        $locale = app()->getLocale();

        return $locale === 'en' && $this->requirements_en
            ? $this->requirements_en
            : $this->requirements;
    }

    public function isTranslationPending(): bool
    {
        return $this->translation_status === 'pending';
    }

    public function scopeNearby($query, $lat, $lng, $radiusKm = 50)
    {
        return $query->selectRaw('
            *, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
        ', [$lat, $lng, $lat])
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance');
    }
}
