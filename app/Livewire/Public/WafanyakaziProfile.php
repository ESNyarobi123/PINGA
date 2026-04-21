<?php

namespace App\Livewire\Public;

use App\Models\ProfileView;
use App\Models\User;
use App\Services\SubscriptionLimitsService;
use Livewire\Component;

class WafanyakaziProfile extends Component
{
    public int $id;

    public bool $showLoginModal = false;

    public ?User $wafanyakazi = null;

    public array $highlights = [];

    public bool $canViewContact = false;

    protected SubscriptionLimitsService $limitsService;

    public function boot(SubscriptionLimitsService $limitsService): void
    {
        $this->limitsService = $limitsService;
    }

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->wafanyakazi = User::query()
            ->where('id', $id)
            ->where('role', 'winga')
            ->where('onboarding_completed', true)
            ->with(['skills', 'portfolio', 'activeSubscription.subscriptionPlan'])
            ->withAvg('reviewsReceived', 'rating')
            ->firstOrFail();

        // Track profile view
        $this->trackView();

        // Build highlights
        $this->highlights = $this->buildHighlights();

        // Task 6: Check if current user has paid for a job with this worker
        $this->canViewContact = $this->checkCanViewContact();
    }

    private function checkCanViewContact(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return \App\Models\Payment::where('employer_id', auth()->id())
            ->where('worker_id', $this->id)
            ->whereIn('status', ['escrowed', 'released'])
            ->exists();
    }

    private function trackView(): void
    {
        $viewerId = auth()->id();
        $ipAddress = request()->ip();

        // Don't count repeated views from same user/IP within 1 hour
        $recentView = ProfileView::where('worker_id', $this->id)
            ->where(function ($q) use ($viewerId, $ipAddress) {
                if ($viewerId) {
                    $q->where('viewer_id', $viewerId);
                } else {
                    $q->where('ip_address', $ipAddress);
                }
            })
            ->where('viewed_at', '>=', now()->subHour())
            ->first();

        if (! $recentView) {
            ProfileView::create([
                'worker_id' => $this->id,
                'viewer_id' => $viewerId,
                'ip_address' => $ipAddress,
                'viewed_at' => now(),
            ]);
        }
    }

    private function buildHighlights(): array
    {
        $highlights = [];
        $user = $this->wafanyakazi;

        // Subscription plan badge (all tiers)
        $plan = $user->activeSubscription?->subscriptionPlan;
        if ($plan) {
            $highlights['plan'] = [
                'name' => $plan->name,
                'slug' => $plan->slug,
                'class' => match ($plan->slug) {
                    'bora' => 'bg-gradient-to-r from-amber-500 to-orange-500 text-white',
                    'kawaida' => 'bg-gradient-to-r from-sky-500 to-cyan-500 text-white',
                    default => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
                },
            ];
        }

        // Verified badge (Kawaida and Bora only)
        if ($this->limitsService->hasVerifiedBadge($user) && $user->is_verified) {
            $highlights['verified'] = [
                'label' => 'Imethibitishwa',
                'icon' => '✓',
                'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
            ];
        }

        // Custom URL (Kawaida and Bora only)
        if ($user->custom_profile_slug) {
            $highlights['custom_url'] = [
                'slug' => $user->custom_profile_slug,
                'url' => url('/w/'.$user->custom_profile_slug),
            ];
        }

        // Chat response time badge (Bora only)
        if ($this->limitsService->hasChatBadge($user) && $user->avg_response_hours) {
            $hours = $user->avg_response_hours;
            $label = $hours < 1
                ? 'Majibu < Dakika 60'
                : 'Majibu ~Saa '.round($hours, 1);
            $highlights['response_time'] = [
                'label' => $label,
                'class' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400',
            ];
        }

        // Top Rated badge (Bora only, based on rating + completed jobs)
        if ($this->limitsService->isTopRatedEligible($user) && $user->is_top_rated) {
            $rating = round($user->reviews_received_avg_rating ?? 0, 1);
            $highlights['top_rated'] = [
                'label' => 'Top Rated ⭐ '.$rating,
                'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            ];
        }

        return $highlights;
    }

    public function openContactModal(): void
    {
        if (auth()->guest()) {
            $this->showLoginModal = true;

            return;
        }
        $this->showLoginModal = false;
    }

    public function closeLoginModal(): void
    {
        $this->showLoginModal = false;
    }

    public function render()
    {
        return view('livewire.public.wafanyakazi-profile', [
            'highlights' => $this->highlights,
        ])
            ->layout('layouts.public')
            ->title($this->wafanyakazi?->name ?? 'Mfanyakazi');
    }
}
