<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionLimitsService
{
    /**
     * Returns all limits for a user based on their active subscription plan.
     */
    public function getLimits(User $user): array
    {
        $plan = $user->activeSubscription()?->plan_slug ?? 'free';

        return match ($plan) {
            'msingi' => [
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
            ],
            'kawaida' => [
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
            ],
            'bora' => [
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
            ],
            default => [ // free
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
            ],
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
        return $this->getLimit($user, 'search_boost');
    }

    /**
     * Check if user is eligible for Top Rated badge.
     * Requires: Kawaida/Bora plan, rating >= 4.5, >=5 completed jobs.
     */
    public function isTopRatedEligible(User $user): bool
    {
        // First check plan eligibility
        if (! $this->getLimit($user, 'top_rated_eligible')) {
            return false;
        }

        // Check rating threshold
        if ($user->rating < 4.5) {
            return false;
        }

        // Check completed jobs count
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
        return $this->getLimit($user, 'custom_url');
    }

    /**
     * Check if user appears in featured category section.
     */
    public function isFeaturedCategoryEligible(User $user): bool
    {
        return $this->getLimit($user, 'featured_category');
    }

    /**
     * Check if user has priority support access.
     */
    public function hasPrioritySupport(User $user): bool
    {
        return $this->getLimit($user, 'priority_support');
    }

    /**
     * Get plan display name with proper styling hints.
     */
    public function getPlanDisplayData(User $user): array
    {
        $plan = $user->activeSubscription()?->plan_slug ?? 'free';

        return match ($plan) {
            'bora' => [
                'name' => 'Bora',
                'badge' => '🏆 Winga Bora',
                'badge_class' => 'bg-winga-100 dark:bg-winga-900/40 text-winga-700 dark:text-winga-400',
                'border_class' => 'border-winga-400 dark:border-winga-600 ring-2 ring-winga-200 dark:ring-winga-800',
                'verified' => true,
            ],
            'kawaida' => [
                'name' => 'Kawaida',
                'badge' => '✨ Winga Plus',
                'badge_class' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400',
                'border_class' => 'border-blue-400 dark:border-blue-600 ring-2 ring-blue-200 dark:ring-blue-800',
                'verified' => false,
            ],
            'msingi' => [
                'name' => 'Msingi',
                'badge' => '⭐ Winga Bora',
                'badge_class' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
                'border_class' => 'border-amber-300 dark:border-amber-700',
                'verified' => false,
            ],
            default => [
                'name' => 'Free',
                'badge' => null,
                'badge_class' => '',
                'border_class' => 'border-zinc-200 dark:border-zinc-700',
                'verified' => false,
            ],
        };
    }

    /**
     * Get next plan tier suggestion for upgrade prompts.
     */
    public function getSuggestedUpgrade(User $user): ?array
    {
        $plan = $user->activeSubscription()?->plan_slug ?? 'free';

        return match ($plan) {
            'free' => [
                'plan' => 'msingi',
                'name' => 'Msingi',
                'price' => 5000,
                'benefit' => 'Huduma 5, maombi 10/ziada',
            ],
            'msingi' => [
                'plan' => 'kawaida',
                'name' => 'Kawaida',
                'price' => 12000,
                'benefit' => 'Huduma 15, Analytics, Custom URL',
            ],
            'kawaida' => [
                'plan' => 'bora',
                'name' => 'Bora',
                'price' => 20000,
                'benefit' => 'Huduma zisizo na kikomo, Verified tick, Priority',
            ],
            default => null, // Bora is top tier
        };
    }
}
