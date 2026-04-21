<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class WafanyakaziListingService
{
    /**
     * @param  array{search?: string, skill?: string, location?: string, filter?: string, per_page?: int}  $params
     * @return array{data: array<int, array<string, mixed>>, meta: array{current_page: int, last_page: int, per_page: int, total: int}}
     */
    public function list(array $params = []): array
    {
        $query = User::query()
            ->where('role', 'winga')
            ->where('onboarding_completed', true)
            ->with(['skills', 'portfolio', 'activeSubscription.subscriptionPlan'])
            ->withAvg('reviewsReceived', 'rating');

        if (! empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "{$search}%")
                    ->orWhere('bio', 'like', "{$search}%")
                    ->orWhereHas('skills', fn ($q) => $q->where('name', 'like', "{$search}%"));
            });
        }

        if (! empty($params['skill'])) {
            $skill = $params['skill'];
            $query->whereHas('skills', fn ($q) => $q->where('slug', $skill)->orWhere('name', 'like', "%{$skill}%"));
        }

        if (! empty($params['location'])) {
            $location = $params['location'];
            $query->where(function ($q) use ($location) {
                $q->where('mkoa', 'like', "%{$location}%")
                    ->orWhere('wilaya', 'like', "%{$location}%")
                    ->orWhere('mtaa', 'like', "%{$location}%")
                    ->orWhere('location', 'like', "%{$location}%");
            });
        }

        $filter = $params['filter'] ?? 'mpya';
        match ($filter) {
            'rating' => $query->withAvg('reviewsReceived', 'rating')->orderByDesc('reviews_received_avg_rating'),
            'karibu' => $query->orderByRaw('(latitude IS NOT NULL AND longitude IS NOT NULL) DESC'),
            default => $query->orderByDesc('created_at'),
        };

        $perPage = (int) ($params['per_page'] ?? 12);
        $paginator = $query->paginate($perPage);

        return [
            'data' => $this->mapCollection($paginator->getCollection()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function mapCollection(Collection $users): array
    {
        return $users->map(function (User $user) {
            $portfolioFirst = $user->portfolio->first();
            $bio = $user->bio ?? 'Mfanyakazi mwenye ujuzi. Tafadhali wasiliana kwa maelezo zaidi.';
            $plan = $user->activeSubscription?->plan;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar_url' => $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0d9488&color=fff&size=128',
                'location' => trim(implode(', ', array_filter([$user->mtaa, $user->wilaya, $user->mkoa]))),
                'rating' => round((float) ($user->reviews_received_avg_rating ?? 0), 1),
                'rating_percent' => $user->reviews_received_avg_rating ? 100 : null,
                'bei_aina' => $user->bei_aina ?? 'siku',
                'bei_wastani' => (int) ($user->bei_wastani ?? 0),
                'offer_title' => $portfolioFirst?->title ?? 'Huduma zangu',
                'offer_description' => $bio,
                'skills' => $user->skills->pluck('name')->all(),
                'phone' => $user->phone,
                'subscription' => $plan ? [
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'is_premium' => in_array($plan->slug, ['kawaida', 'bora']),
                    'border_class' => $this->getPlanBorderClass($plan->slug),
                    'badge_class' => $this->getPlanBadgeClass($plan->slug),
                ] : null,
            ];
        })->all();
    }

    private function getPlanBorderClass(string $slug): string
    {
        return match ($slug) {
            'bora' => 'ring-2 ring-amber-400 dark:ring-amber-500',
            'kawaida' => 'ring-2 ring-sky-400 dark:ring-sky-500',
            default => '',
        };
    }

    private function getPlanBadgeClass(string $slug): string
    {
        return match ($slug) {
            'bora' => 'bg-gradient-to-r from-amber-500 to-orange-500 text-white',
            'kawaida' => 'bg-gradient-to-r from-sky-500 to-cyan-500 text-white',
            default => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
        };
    }
}
