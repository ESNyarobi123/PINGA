<?php

namespace App\Livewire\Admin;

use App\Models\Dispute;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public array $stats = [];

    public array $charts = [];

    public array $activityFeed = [];

    public string $dateRange = '30';

    public bool $ready = false;

    public function mount(): void
    {
        $this->loadData();
    }

    public function updatedDateRange(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->stats = $this->getStats();
        $this->charts = $this->getCharts();
        $this->activityFeed = $this->getActivityFeed();
        $this->ready = true;
    }

    private function getStats(): array
    {
        $today = now()->startOfDay();

        return [
            'total_users' => User::count(),
            'wingas_count' => User::where('role', 'winga')->count(),
            'wateja_count' => User::where('role', 'mteja')->count(),
            'today_signups' => User::whereDate('created_at', today())->count(),

            'total_jobs' => Job::count(),
            'open_jobs' => Job::where('status', 'open')->count(),
            'in_progress_jobs' => Job::where('status', 'in_progress')->count(),
            'completed_jobs' => Job::where('status', 'completed')->count(),
            'disputed_jobs' => Job::where('status', 'disputed')->count(),

            'revenue_today' => Payment::whereDate('created_at', today())
                ->where('status', 'released')
                ->sum('platform_fee'),

            'escrow_balance' => Payment::where('status', 'escrowed')
                ->sum('amount'),

            'subscriptions_active' => Subscription::active()->count(),
            'msingi_active' => Subscription::whereHas('subscriptionPlan', fn ($q) => $q->where('slug', 'msingi'))
                ->active()->count(),
            'kawaida_active' => Subscription::whereHas('subscriptionPlan', fn ($q) => $q->where('slug', 'kawaida'))
                ->active()->count(),
            'bora_active' => Subscription::whereHas('subscriptionPlan', fn ($q) => $q->where('slug', 'bora'))
                ->active()->count(),

            'pending_approvals' => Job::where('is_approved', false)->count(),
            'disputes_open' => Dispute::where('status', 'open')->count(),
            'failed_payouts' => Payment::where('payout_status', 'failed')
                ->count(),
        ];
    }

    private function getCharts(): array
    {
        $days = (int) $this->dateRange;
        $startDate = now()->subDays($days - 1)->startOfDay();

        // Revenue Chart
        $revenueData = Payment::where('status', 'released')
            ->where('created_at', '>=', $startDate)
            ->groupByRaw('DATE(created_at)')
            ->selectRaw('DATE(created_at) as date, SUM(platform_fee) as revenue')
            ->orderBy('date')
            ->get()
            ->map(fn ($item) => [
                'date' => \Carbon\Carbon::parse($item->date)->format('d M'),
                'revenue' => (float) $item->revenue,
            ])
            ->toArray();

        // Users Growth Chart
        $usersData = User::where('created_at', '>=', $startDate)
            ->groupByRaw('DATE(created_at), role')
            ->selectRaw('DATE(created_at) as date, role, COUNT(*) as count')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(fn ($group, $date) => [
                'date' => \Carbon\Carbon::parse($date)->format('d M'),
                'wingas' => $group->firstWhere('role', 'winga')?->count ?? 0,
                'wateja' => $group->firstWhere('role', 'mteja')?->count ?? 0,
            ])
            ->values()
            ->toArray();

        // Jobs Overview Chart
        $jobsData = Job::where('created_at', '>=', $startDate)
            ->groupByRaw('DATE(created_at)')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as posted')
            ->orderBy('date')
            ->get()
            ->map(fn ($item) => [
                'date' => \Carbon\Carbon::parse($item->date)->format('d M'),
                'posted' => $item->posted,
                'completed' => Job::whereDate('updated_at', $item->date)
                    ->where('status', 'completed')
                    ->count(),
            ])
            ->toArray();

        // Subscription Revenue Donut
        $subscriptionRevenue = Subscription::whereIn('status', ['active', 'expired'])
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->groupBy('subscription_plans.name')
            ->selectRaw('subscription_plans.name, SUM(amount_paid) as total')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'value' => (float) $item->total,
            ])
            ->toArray();

        // Payment Methods Pie
        $paymentMethods = Payment::where('created_at', '>=', $startDate)
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count')
            ->get()
            ->map(fn ($item) => [
                'name' => match ($item->payment_method) {
                    'mpesa' => 'M-Pesa',
                    'tigopesa' => 'Tigo Pesa',
                    'airtelmoney' => 'Airtel Money',
                    'card' => 'Card',
                    'wallet' => 'Wallet',
                    default => $item->payment_method,
                },
                'value' => $item->count,
            ])
            ->toArray();

        // Top Categories
        $topCategories = Job::where('job_listings.created_at', '>=', $startDate)
            ->join('categories', 'job_listings.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->selectRaw('categories.name, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'count' => $item->count,
            ])
            ->toArray();

        return [
            'revenue' => $revenueData,
            'users' => $usersData,
            'jobs' => $jobsData,
            'subscriptions' => $subscriptionRevenue,
            'payment_methods' => $paymentMethods,
            'categories' => $topCategories,
        ];
    }

    private function getActivityFeed(): array
    {
        $activities = collect();

        // Recent registrations
        User::latest('created_at')
            ->limit(10)
            ->get()
            ->each(fn ($user) => $activities->push([
                'type' => 'user_registered',
                'icon' => '👤',
                'title' => 'Mtu mpya amejiunga',
                'description' => "{$user->name} ({$user->role}) amesajiliwa",
                'time' => $user->created_at->diffForHumans(),
                'timestamp' => $user->created_at->toIso8601String(),
            ]));

        // Recent jobs
        Job::latest('created_at')
            ->limit(10)
            ->get()
            ->each(fn ($job) => $activities->push([
                'type' => 'job_posted',
                'icon' => '📋',
                'title' => 'Kazi mpya imewasilishwa',
                'description' => $job->title,
                'time' => $job->created_at->diffForHumans(),
                'timestamp' => $job->created_at->toIso8601String(),
            ]));

        // Recent payments
        Payment::latest('created_at')
            ->limit(10)
            ->get()
            ->each(fn ($payment) => $activities->push([
                'type' => 'payment_received',
                'icon' => '💰',
                'title' => 'Malipo yamepokelewa',
                'description' => 'TZS '.number_format($payment->amount),
                'time' => $payment->created_at->diffForHumans(),
                'timestamp' => $payment->created_at->toIso8601String(),
            ]));

        // Recent subscriptions
        Subscription::with(['subscriptionPlan', 'user'])->latest('created_at')
            ->limit(10)
            ->get()
            ->each(fn ($sub) => $activities->push([
                'type' => 'subscription_activated',
                'icon' => '⭐',
                'title' => 'Subscription mpya',
                'description' => "{$sub->subscriptionPlan?->name} - {$sub->user?->name}",
                'time' => $sub->created_at->diffForHumans(),
                'timestamp' => $sub->created_at->toIso8601String(),
            ]));

        // Recent disputes
        Dispute::latest('created_at')
            ->limit(5)
            ->get()
            ->each(fn ($dispute) => $activities->push([
                'type' => 'dispute_opened',
                'icon' => '⚠️',
                'title' => 'Migogoro mpya',
                'description' => $dispute->reason,
                'time' => $dispute->created_at->diffForHumans(),
                'timestamp' => $dispute->created_at->toIso8601String(),
            ]));

        // Failed payouts
        Payment::where('payout_status', 'failed')
            ->latest('updated_at')
            ->limit(5)
            ->get()
            ->each(fn ($payment) => $activities->push([
                'type' => 'failed_payout',
                'icon' => '❌',
                'title' => 'Malipo yameshindikana',
                'description' => 'TZS '.number_format($payment->amount),
                'time' => $payment->updated_at->diffForHumans(),
                'timestamp' => $payment->updated_at->toIso8601String(),
            ]));

        return $activities
            ->sortByDesc(fn ($activity) => $activity['timestamp'])
            ->take(50)
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'showSkeleton' => ! $this->ready,
        ])
            ->layout('layouts.admin')
            ->title('Admin Dashboard');
    }
}
