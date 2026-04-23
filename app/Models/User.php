<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'legacy_wp_user_id',
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'bio',
        'location',
        'latitude',
        'longitude',
        'mkoa',
        'wilaya',
        'mtaa',
        'payment_method',
        'payment_number',
        'bei_aina',
        'bei_wastani',
        'uzoefu_miaka',
        'siku_zinazopatikana',
        'nida',
        'veta',
        'role',
        'wallet_balance',
        'phone_verified_at',
        'onboarding_completed',
        'otp',
        'otp_expires_at',
        'otp_attempts',
        'two_factor_enabled',
        'whatsapp',
        'phone_visible',
        'is_verified',
        'suspended_at',
        'suspended_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
            'onboarding_completed' => 'boolean',
            'siku_zinazopatikana' => 'array',
            'phone_visible' => 'boolean',
            'is_verified' => 'boolean',
            'suspended_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if user has an active paid subscription
     */
    public function hasActiveSubscription(): bool
    {
        return (bool) ($this->has_active_subscription ?? false)
            || $this->subscriptions()->where('status', 'active')->where('expires_at', '>', now())->exists();
    }

    /**
     * Check if user is Mteja (Employer / Customer)
     */
    public function isMteja(): bool
    {
        return $this->role === 'mteja' || $this->hasRole('mteja');
    }

    /**
     * Check if user is Winga (Worker)
     */
    public function isWinga(): bool
    {
        return $this->role === 'winga' || $this->hasRole('winga');
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * Legacy methods for backward compatibility
     */
    public function isMuajili(): bool
    {
        return $this->isMteja();
    }

    public function isMfanyakazi(): bool
    {
        return $this->isWinga();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->where('expires_at', '>', now())->latestOfMany();
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    // Relationships
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteJobs()
    {
        return $this->morphedByMany(Job::class, 'favorable', 'favorites');
    }

    public function favoriteWorkers()
    {
        return $this->morphedByMany(User::class, 'favorable', 'favorites');
    }

    public function hasFavorited(string $type, int $id): bool
    {
        return $this->favorites()
            ->where('favorable_type', $type)
            ->where('favorable_id', $id)
            ->exists();
    }

    public function toggleFavorite(string $type, int $id): bool
    {
        $existing = $this->favorites()
            ->where('favorable_type', $type)
            ->where('favorable_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();

            return false;
        }

        $this->favorites()->create([
            'favorable_type' => $type,
            'favorable_id' => $id,
        ]);

        return true;
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'worker_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills');
    }

    public function sentPayments()
    {
        return $this->hasMany(Payment::class, 'employer_id');
    }

    public function receivedPayments()
    {
        return $this->hasMany(Payment::class, 'worker_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function portfolio()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function serviceRequestsAsClient()
    {
        return $this->hasMany(ServiceRequest::class, 'client_id');
    }

    public function portfolioImages()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    /**
     * Average rating from reviews received
     */
    public function averageRating(): float
    {
        return (float) $this->reviewsReceived()->avg('rating') ?? 0;
    }

    /**
     * Check if user is verified (has NIDA or VETA)
     */
    public function isVerified(): bool
    {
        return $this->is_verified || ! empty($this->nida) || ! empty($this->veta);
    }

    /**
     * @return array{email: string, role: string, role_label: string, reason: ?string}
     */
    public function suspensionAppealFlashData(): array
    {
        return [
            'email' => $this->email,
            'role' => (string) $this->role,
            'role_label' => match ($this->role) {
                'winga' => 'Winga',
                'mteja' => 'Mteja',
                'admin' => 'Msimamizi',
                default => ucfirst((string) $this->role),
            },
            'reason' => $this->suspended_reason,
        ];
    }

    /**
     * Get user's account status
     */
    public function getAccountStatusAttribute(): string
    {
        if ($this->suspended_at && $this->suspended_reason === 'Banned by admin') {
            return 'Banned';
        }
        if ($this->suspended_at) {
            return 'Suspended';
        }

        return $this->onboarding_completed ? 'Active' : 'Pending';
    }

    /**
     * Get number of completed jobs
     */
    public function getCompletedJobsCountAttribute(): int
    {
        if ($this->role === 'winga') {
            return $this->applications()->where('status', 'hired')->count();
        } elseif ($this->role === 'mteja') {
            return $this->jobs()->where('status', 'completed')->count();
        }

        return 0;
    }

    /**
     * Get user disputes
     */
    public function disputes()
    {
        return $this->hasMany(Dispute::class, 'initiator_id')
            ->orWhere('respondent_id', $this->id);
    }

    /**
     * Generate 6-digit OTP
     */
    public function generateOtp(): string
    {
        $code = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'otp' => \Hash::make($code),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts' => 0, // reset attempts
        ]);

        return $code;
    }

    /**
     * Verify OTP Code
     */
    public function verifyOtp(string $code): bool
    {
        if (! $this->otp_expires_at || now()->greaterThan($this->otp_expires_at)) {
            return false; // Ime-expire
        }

        if ($this->otp_attempts >= 5) {
            return false; // Majaribio yamezidi
        }

        if (! \Hash::check($code, $this->otp)) {
            $this->increment('otp_attempts');

            return false;
        }

        // Tuko sawa, clear OTP
        $this->update([
            'otp' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
        ]);

        return true;
    }

    /**
     * Determine if two-factor authentication has been enabled.
     * Required by built-in auth views.
     */
    public function hasEnabledTwoFactorAuthentication(): bool
    {
        return (bool) $this->two_factor_enabled;
    }

    /**
     * Update profile photo (avatar) from uploaded file.
     */
    public function updateProfilePhoto($photo): void
    {
        if (! $photo) {
            return;
        }
        $path = $photo->store('profile-photos', 'public');
        $this->update(['avatar' => $path]);
    }
}
