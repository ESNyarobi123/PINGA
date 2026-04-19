<?php

namespace App\Livewire\Winga;

use App\Models\ProfileView;
use App\Services\SubscriptionLimitsService;
use Livewire\Component;

class Analytics extends Component
{
    public bool $ready = false;

    public string $period = '30';

    public array $stats = [];

    public array $viewsTrend = [];

    public string $analyticsLevel = 'basic';

    protected SubscriptionLimitsService $limitsService;

    public function boot(SubscriptionLimitsService $limitsService): void
    {
        $this->limitsService = $limitsService;
    }

    public function mount(): void
    {
        $user = auth()->user();
        $this->analyticsLevel = $this->limitsService->hasAnalytics($user);
        $this->loadData();
    }

    public function updatedPeriod(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $user = auth()->user();
        $days = (int) $this->period;
        $since = now()->subDays($days);

        // Base stats - available to all tiers
        $profileViews = ProfileView::where('worker_id', $user->id)
            ->where('viewed_at', '>=', $since)
            ->count();

        $totalViews = ProfileView::where('worker_id', $user->id)->count();

        $uniqueViewers = ProfileView::where('worker_id', $user->id)
            ->where('viewed_at', '>=', $since)
            ->distinct('viewer_id')
            ->count('viewer_id');

        $this->stats = [
            'profile_views' => $profileViews,
            'total_views' => $totalViews,
            'unique_viewers' => $uniqueViewers,
        ];

        // Views trend - available to all tiers
        $this->viewsTrend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $count = ProfileView::where('worker_id', $user->id)
                ->whereDate('viewed_at', $date)
                ->count();
            $this->viewsTrend[] = [
                'date' => now()->subDays($i)->format('d M'),
                'count' => $count,
            ];
        }

        // Advanced stats - Kawaida and Bora only
        if (in_array($this->analyticsLevel, ['advanced', 'full'])) {
            $applications = $user->applications()
                ->where('created_at', '>=', $since)
                ->count();

            $acceptedApplications = $user->applications()
                ->where('status', 'accepted')
                ->where('updated_at', '>=', $since)
                ->count();

            $this->stats['applications'] = $applications;
            $this->stats['accepted_applications'] = $acceptedApplications;
            $this->stats['conversion_rate'] = $applications > 0
                ? round(($acceptedApplications / $applications) * 100, 1)
                : 0;

            // Rating stats
            $avgRating = $user->reviewsReceived()
                ->where('created_at', '>=', $since)
                ->avg('rating') ?? 0;
            $this->stats['avg_rating'] = round($avgRating, 1);
        }

        // Full analytics - Bora only
        if ($this->analyticsLevel === 'full') {
            // Earnings data
            $earnings = $user->payments()
                ->where('status', 'released')
                ->where('created_at', '>=', $since)
                ->sum('amount');

            $this->stats['earnings'] = (float) $earnings;
            $this->stats['earnings_per_application'] = $applications > 0
                ? round($earnings / $applications, 2)
                : 0;

            // Top performing days
            $topDay = ProfileView::where('worker_id', $user->id)
                ->where('viewed_at', '>=', $since)
                ->selectRaw('DATE(viewed_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderByDesc('count')
                ->first();
            $this->stats['top_day_views'] = $topDay?->count ?? 0;
            $this->stats['top_day_date'] = $topDay ? date('d M', strtotime($topDay->date)) : '-';
        }

        $this->ready = true;
    }

    public function render()
    {
        return view('livewire.winga.analytics', [
            'analyticsLevel' => $this->analyticsLevel,
            'canAccessAdvanced' => in_array($this->analyticsLevel, ['advanced', 'full']),
            'canAccessFull' => $this->analyticsLevel === 'full',
        ])
            ->layout('layouts.winga')
            ->title('Analytics — Takwimu Zangu');
    }
}
