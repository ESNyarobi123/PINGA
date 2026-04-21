<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Service;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SubscriptionLimitsService
{
    /** @var list<string> */
    private const LIMIT_KEYS = [
        'max_services',
        'daily_bids',
        'portfolio_imgs',
        'analytics',
        'smart_match_priority',
        'search_boost',
        'custom_url',
        'verified_badge',
        'chat_badge',
        'top_rated_eligible',
        'featured_category',
        'priority_support',
    ];

    /**
     * Returns all limits for a user based on their active subscription plan.
     */
    public function getLimits(User $user): array
    {
        $sub = app(SubscriptionService::class)->getActivePlan($user);

        if (! $sub) {
            return $this->freeTierLimits();
        }

        $plan = $sub->subscriptionPlan;
        $slug = (string) ($sub->plan_slug ?? '');

        if ($plan && is_array($plan->limits) && $plan->limits !== []) {
            return $this->applyLimitOverrides($this->freeTierLimits(), $plan->limits);
        }

        return match ($slug) {
            'msingi', 'basic' => $this->tierMsingi(),
            'kawaida', 'pro' => $this->tierKawaida(),
            'bora', 'premium' => $this->tierBora(),
            'winga-complex' => $this->wingaComplexDefaults(),
            'winga-karume' => $this->wingaKarumeDefaults(),
            'winga-kkoo' => $this->wingaKkooDefaults(),
            default => $this->paidUnknownDefaults(),
        };
    }

    /**
     * Get a single limit value for a user.
     */
    public function getLimit(User $user, string $key): mixed
    {
        return $this->getLimits($user)[$key] ?? null;
    }

    /**
     * Check if user can post a new service (within their plan limit).
     */
    public function canPostService(User $user): bool
    {
        $limit = $this->getLimit($user, 'max_services');
        $currentCount = Service::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        return $currentCount < $limit;
    }

    /**
     * Get remaining service slots for display in UI.
     */
    public function remainingServiceSlots(User $user): int
    {
        $limit = $this->getLimit($user, 'max_services');
        $currentCount = Service::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        return max(0, $limit - $currentCount);
    }

    /**
     * Check if user can place a bid today (within daily limit).
     */
    public function canBidToday(User $user): bool
    {
        $limit = $this->getLimit($user, 'daily_bids');
        $todayCount = Application::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        return $todayCount < $limit;
    }

    /**
     * Get remaining bids for today.
     */
    public function remainingBidsToday(User $user): int
    {
        $limit = $this->getLimit($user, 'daily_bids');
        $todayCount = Application::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        return max(0, $limit - $todayCount);
    }

    /**
     * Check if user can upload more portfolio images.
     */
    public function canUploadPortfolio(User $user, ?int $additionalCount = 1): bool
    {
        $limit = $this->getLimit($user, 'portfolio_imgs');
        $currentCount = $user->portfolioImages()->count();

        return ($currentCount + $additionalCount) <= $limit;
    }

    /**
     * Get remaining portfolio slots.
     */
    public function remainingPortfolioSlots(User $user): int
    {
        $limit = $this->getLimit($user, 'portfolio_imgs');
        $currentCount = $user->portfolioImages()->count();

        return max(0, $limit - $currentCount);
    }

    /**
     * Get analytics tier level (none, basic, advanced, full).
     */
    public function hasAnalytics(User $user): string
    {
        return $this->getLimit($user, 'analytics');
    }

    /**
     * Get search ranking boost points.
     */
    public function getSearchBoost(User $user): int
    {
        return (int) $this->getLimit($user, 'search_boost');
    }

    /**
     * Check if user is eligible for Top Rated badge.
     * Requires: Kawaida/Bora plan, rating >= 4.5, >=5 completed jobs.
     */
    public function isTopRatedEligible(User $user): bool
    {
        if (! $this->getLimit($user, 'top_rated_eligible')) {
            return false;
        }

        if ($user->rating < 4.5) {
            return false;
        }

        $completedJobs = Application::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return $completedJobs >= 5;
    }

    /**
     * Check if user should display verified badge.
     */
    public function hasVerifiedBadge(User $user): bool
    {
        return $this->getLimit($user, 'verified_badge') && $user->is_verified;
    }

    /**
     * Check if user should display chat response time badge.
     */
    public function hasChatBadge(User $user): bool
    {
        return $this->getLimit($user, 'chat_badge') && $user->avg_response_hours !== null;
    }

    /**
     * Check if user can set custom profile URL.
     */
    public function canSetCustomUrl(User $user): bool
    {
        return (bool) $this->getLimit($user, 'custom_url');
    }

    /**
     * Check if user appears in featured category section.
     */
    public function isFeaturedCategoryEligible(User $user): bool
    {
        return (bool) $this->getLimit($user, 'featured_category');
    }

    /**
     * Check if user has priority support access.
     */
    public function hasPrioritySupport(User $user): bool
    {
        return (bool) $this->getLimit($user, 'priority_support');
    }

    /**
     * Get plan display name with proper styling hints.
     */
    public function getPlanDisplayData(User $user): array
    {
        $sub = app(SubscriptionService::class)->getActivePlan($user);

        if (! $sub) {
            return [
                'name' => 'Free',
                'badge' => null,
                'badge_class' => '',
                'border_class' => 'border-zinc-200 dark:border-zinc-700',
                'verified' => false,
            ];
        }

        $plan = $sub->subscriptionPlan;
        if ($plan instanceof SubscriptionPlan) {
            return [
                'name' => $plan->name,
                'badge' => $plan->badge_label ?: $plan->name,
                'badge_class' => $this->badgeColorToClasses($plan->badge_color)['badge_class'],
                'border_class' => $this->badgeColorToClasses($plan->badge_color)['border_class'],
                'verified' => (bool) $this->getLimit($user, 'verified_badge'),
            ];
        }

        $slug = (string) ($sub->plan_slug ?? 'free');

        return match ($slug) {
            'bora', 'premium' => [
                'name' => 'Bora',
                'badge' => '🏆 Winga Bora',
                'badge_class' => 'bg-winga-100 dark:bg-winga-900/40 text-winga-700 dark:text-winga-400',
                'border_class' => 'border-winga-400 dark:border-winga-600 ring-2 ring-winga-200 dark:ring-winga-800',
                'verified' => true,
            ],
            'kawaida', 'pro' => [
                'name' => 'Kawaida',
                'badge' => '✨ Winga Plus',
                'badge_class' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400',
                'border_class' => 'border-blue-400 dark:border-blue-600 ring-2 ring-blue-200 dark:ring-blue-800',
                'verified' => false,
            ],
            'msingi', 'basic' => [
                'name' => 'Msingi',
                'badge' => '⭐ Winga Bora',
                'badge_class' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
                'border_class' => 'border-amber-300 dark:border-amber-700',
                'verified' => false,
            ],
            default => $this->planDisplayFromSlugRow($slug, $user),
        };
    }

    /**
     * When subscription row has plan_slug but no subscription_plan_id, resolve display from plans table.
     *
     * @return array{name: string, badge: string|null, badge_class: string, border_class: string, verified: bool}
     */
    private function planDisplayFromSlugRow(string $slug, User $user): array
    {
        $row = SubscriptionPlan::query()->where('slug', $slug)->first();
        if ($row instanceof SubscriptionPlan) {
            $classes = $this->badgeColorToClasses($row->badge_color);

            return [
                'name' => $row->name,
                'badge' => $row->badge_label ?: $row->name,
                'badge_class' => $classes['badge_class'],
                'border_class' => $classes['border_class'],
                'verified' => (bool) $this->getLimit($user, 'verified_badge'),
            ];
        }

        return [
            'name' => $slug !== '' && $slug !== 'free' ? Str::title(str_replace('-', ' ', $slug)) : 'Free',
            'badge' => $slug !== '' && $slug !== 'free' ? __('messages.subscription.subscribed_badge') : null,
            'badge_class' => 'bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200',
            'border_class' => 'border-zinc-300 dark:border-zinc-600',
            'verified' => (bool) $this->getLimit($user, 'verified_badge'),
        ];
    }

    /**
     * Get next plan tier suggestion for upgrade prompts.
     */
    public function getSuggestedUpgrade(User $user): ?array
    {
        $sub = app(SubscriptionService::class)->getActivePlan($user);
        $currentPlan = $sub?->subscriptionPlan;

        $next = SubscriptionPlan::query()
            ->active()
            ->when($currentPlan, function ($q) use ($currentPlan) {
                $q->where(function ($q2) use ($currentPlan) {
                    $q2->where('sort_order', '>', $currentPlan->sort_order)
                        ->orWhere(function ($q3) use ($currentPlan) {
                            $q3->where('sort_order', '=', $currentPlan->sort_order)
                                ->where('id', '>', $currentPlan->id);
                        });
                });
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();

        if (! $next && $currentPlan) {
            return null;
        }

        if (! $next) {
            $next = SubscriptionPlan::query()->active()->orderBy('sort_order')->orderBy('id')->first();
        }

        if (! $next) {
            return null;
        }

        $benefit = collect($next->features ?? [])->first()
            ?? __('messages.upgrade.default_benefit');

        return [
            'plan' => $next->slug,
            'plan_id' => $next->id,
            'name' => $next->name,
            'price' => $next->price,
            'benefit' => $benefit,
        ];
    }

    private function freeTierLimits(): array
    {
        return [
            'max_services' => 2,
            'daily_bids' => 3,
            'portfolio_imgs' => 2,
            'analytics' => 'none',
            'smart_match_priority' => 'none',
            'search_boost' => 0,
            'custom_url' => false,
            'verified_badge' => false,
            'chat_badge' => false,
            'top_rated_eligible' => false,
            'featured_category' => false,
            'priority_support' => false,
        ];
    }

    private function tierMsingi(): array
    {
        return [
            'max_services' => 5,
            'daily_bids' => 10,
            'portfolio_imgs' => 5,
            'analytics' => 'basic',
            'smart_match_priority' => 'normal',
            'search_boost' => 10,
            'custom_url' => false,
            'verified_badge' => false,
            'chat_badge' => false,
            'top_rated_eligible' => false,
            'featured_category' => false,
            'priority_support' => false,
        ];
    }

    private function tierKawaida(): array
    {
        return [
            'max_services' => 15,
            'daily_bids' => 25,
            'portfolio_imgs' => 10,
            'analytics' => 'advanced',
            'smart_match_priority' => 'high',
            'search_boost' => 25,
            'custom_url' => true,
            'verified_badge' => false,
            'chat_badge' => true,
            'top_rated_eligible' => true,
            'featured_category' => false,
            'priority_support' => false,
        ];
    }

    private function tierBora(): array
    {
        return [
            'max_services' => PHP_INT_MAX,
            'daily_bids' => PHP_INT_MAX,
            'portfolio_imgs' => 20,
            'analytics' => 'full',
            'smart_match_priority' => 'highest',
            'search_boost' => 50,
            'custom_url' => true,
            'verified_badge' => true,
            'chat_badge' => true,
            'top_rated_eligible' => true,
            'featured_category' => true,
            'priority_support' => true,
        ];
    }

    /**
     * Defaults aligned with SubscriptionPlansSeeder marketing copy (when limits JSON is empty).
     */
    private function wingaComplexDefaults(): array
    {
        return [
            'max_services' => 5,
            'daily_bids' => 5,
            'portfolio_imgs' => 3,
            'analytics' => 'basic',
            'smart_match_priority' => 'normal',
            'search_boost' => 10,
            'custom_url' => false,
            'verified_badge' => false,
            'chat_badge' => false,
            'top_rated_eligible' => false,
            'featured_category' => false,
            'priority_support' => false,
        ];
    }

    private function wingaKarumeDefaults(): array
    {
        return [
            'max_services' => 15,
            'daily_bids' => 15,
            'portfolio_imgs' => 10,
            'analytics' => 'advanced',
            'smart_match_priority' => 'high',
            'search_boost' => 25,
            'custom_url' => false,
            'verified_badge' => true,
            'chat_badge' => true,
            'top_rated_eligible' => true,
            'featured_category' => false,
            'priority_support' => false,
        ];
    }

    private function wingaKkooDefaults(): array
    {
        $limits = $this->tierBora();
        $limits['portfolio_imgs'] = PHP_INT_MAX;

        return $limits;
    }

    /** Active paid subscription but unknown slug and no limits row — use Msingi-style, not free. */
    private function paidUnknownDefaults(): array
    {
        return $this->tierMsingi();
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function applyLimitOverrides(array $base, array $overrides): array
    {
        $out = $base;
        foreach ($overrides as $key => $value) {
            if (! in_array($key, self::LIMIT_KEYS, true)) {
                continue;
            }
            if (in_array($key, ['max_services', 'daily_bids', 'portfolio_imgs'], true)) {
                $n = is_numeric($value) ? (int) $value : 0;
                $out[$key] = $n === 0 ? PHP_INT_MAX : $n;

                continue;
            }
            if ($key === 'search_boost') {
                $out[$key] = max(0, is_numeric($value) ? (int) $value : 0);

                continue;
            }
            if (in_array($key, [
                'custom_url',
                'verified_badge',
                'chat_badge',
                'top_rated_eligible',
                'featured_category',
                'priority_support',
            ], true)) {
                $out[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN)
                    || $value === 1
                    || $value === '1'
                    || $value === 'true';

                continue;
            }
            if ($key === 'analytics' && is_string($value) && in_array($value, ['none', 'basic', 'advanced', 'full'], true)) {
                $out[$key] = $value;

                continue;
            }
            if ($key === 'smart_match_priority' && is_string($value)) {
                $out[$key] = in_array($value, ['none', 'normal', 'high', 'highest'], true)
                    ? $value
                    : $out[$key];
            }
        }

        return $out;
    }

    /**
     * @return array{badge_class: string, border_class: string}
     */
    private function badgeColorToClasses(string $color): array
    {
        return match ($color) {
            'blue' => [
                'badge_class' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400',
                'border_class' => 'border-blue-400 dark:border-blue-600 ring-2 ring-blue-200 dark:ring-blue-800',
            ],
            'winga', 'green' => [
                'badge_class' => 'bg-winga-100 dark:bg-winga-900/40 text-winga-700 dark:text-winga-400',
                'border_class' => 'border-winga-400 dark:border-winga-600 ring-2 ring-winga-200 dark:ring-winga-800',
            ],
            'red' => [
                'badge_class' => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400',
                'border_class' => 'border-red-300 dark:border-red-700',
            ],
            'purple' => [
                'badge_class' => 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400',
                'border_class' => 'border-purple-300 dark:border-purple-700',
            ],
            default => [
                'badge_class' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
                'border_class' => 'border-amber-300 dark:border-amber-700',
            ],
        };
    }
}
