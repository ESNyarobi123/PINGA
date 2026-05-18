<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $type
 * @property array<int, string> $audiences
 * @property bool $is_active
 * @property bool $is_dismissible
 * @property int $min_view_seconds
 * @property ?string $cta_label
 * @property ?string $cta_url
 * @property ?\Illuminate\Support\Carbon $starts_at
 * @property ?\Illuminate\Support\Carbon $ends_at
 * @property ?int $created_by
 */
class SiteAnnouncement extends Model
{
    /** @use HasFactory<\Database\Factories\SiteAnnouncementFactory> */
    use HasFactory;

    public const AUDIENCE_PUBLIC = 'public';

    public const AUDIENCE_MTEJA = 'mteja';

    public const AUDIENCE_WINGA = 'winga';

    /** @var list<string> */
    public const AUDIENCES = [
        self::AUDIENCE_PUBLIC,
        self::AUDIENCE_MTEJA,
        self::AUDIENCE_WINGA,
    ];

    /** @var list<string> */
    public const TYPES = ['info', 'success', 'warning', 'danger'];

    protected $fillable = [
        'title',
        'body',
        'type',
        'audiences',
        'is_active',
        'is_dismissible',
        'min_view_seconds',
        'cta_label',
        'cta_url',
        'starts_at',
        'ends_at',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'audiences' => 'array',
            'is_active' => 'boolean',
            'is_dismissible' => 'boolean',
            'min_view_seconds' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['viewed_at', 'dismissed_at'])
            ->withTimestamps();
    }

    /**
     * Active = is_active AND within optional time window.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeActive(Builder $query): Builder
    {
        $now = now();

        return $query->where('is_active', true)
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }

    /**
     * Filter by audience (uses JSON contains).
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeForAudience(Builder $query, string $audience): Builder
    {
        return $query->whereJsonContains('audiences', $audience);
    }

    public function wasDismissedBy(User $user): bool
    {
        return $this->users()
            ->where('users.id', $user->id)
            ->wherePivotNotNull('dismissed_at')
            ->exists();
    }
}
