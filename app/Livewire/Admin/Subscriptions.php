<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionService;
use Livewire\Component;
use Livewire\WithPagination;

class Subscriptions extends Component
{
    use WithPagination;

    public string $filterStatus = 'all';
    public string $filterPlan   = '';
    public string $search       = '';

    // Manual activation form
    public bool   $showManualForm   = false;
    public string $manualUserId     = '';
    public string $manualPlanSlug   = '';
    public string $manualNotes      = '';

    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterPlan(): void   { $this->resetPage(); }
    public function updatingSearch(): void       { $this->resetPage(); }

    public function activate(int $subscriptionId): void
    {
        $sub = Subscription::with('plan')->find($subscriptionId);
        if (! $sub) { return; }

        $plan = $sub->plan;
        if (! $plan) {
            $this->dispatch('toast', message: 'Mpango haupatikani.', type: 'error');
            return;
        }

        app(SubscriptionService::class)->activate(
            $sub->user,
            $plan,
            'admin-manual-' . $sub->id . '-' . now()->timestamp,
            'admin'
        );

        $this->dispatch('toast', message: 'Subscription imewashwa.', type: 'success');
    }

    public function deactivate(int $subscriptionId): void
    {
        Subscription::where('id', $subscriptionId)->update(['status' => 'cancelled']);
        $this->dispatch('toast', message: 'Subscription imezimwa.', type: 'success');
    }

    public function submitManual(): void
    {
        $this->validate([
            'manualUserId'   => 'required|exists:users,id',
            'manualPlanSlug' => 'required|exists:subscription_plans,slug',
        ]);

        $user = User::find($this->manualUserId);
        $plan = SubscriptionPlan::where('slug', $this->manualPlanSlug)->first();

        app(SubscriptionService::class)->activate(
            $user,
            $plan,
            'admin-grant-' . $user->id . '-' . now()->timestamp,
            'admin'
        );

        $this->reset(['showManualForm', 'manualUserId', 'manualPlanSlug', 'manualNotes']);
        $this->dispatch('toast', message: "Subscription ya {$plan->name} imewashwa kwa {$user->name}.", type: 'success');
    }

    private function getChartData(): array
    {
        // Revenue by plan (pie chart data)
        $revenueByPlan = Subscription::selectRaw('plan_slug, SUM(amount_paid) as total')
            ->whereIn('status', ['active', 'expired'])
            ->groupBy('plan_slug')
            ->pluck('total', 'plan_slug')
            ->toArray();

        // Subscriptions by plan (bar chart data)
        $subsByPlan = Subscription::selectRaw('plan_slug, COUNT(*) as count')
            ->whereIn('status', ['active', 'expired'])
            ->groupBy('plan_slug')
            ->pluck('count', 'plan_slug')
            ->toArray();

        // Monthly revenue trend (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Subscription::whereMonth('starts_at', $month->month)
                ->whereYear('starts_at', $month->year)
                ->sum('amount_paid');
            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => (float) $revenue,
            ];
        }

        // Daily new subscriptions (last 30 days)
        $dailySubs = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $count = Subscription::whereDate('starts_at', $date)->count();
            $dailySubs[] = [
                'date' => now()->subDays($i)->format('d M'),
                'count' => $count,
            ];
        }

        return [
            'revenue_by_plan' => $revenueByPlan,
            'subs_by_plan' => $subsByPlan,
            'monthly_revenue' => $monthlyRevenue,
            'daily_subs' => $dailySubs,
        ];
    }

    public function render()
    {
        $query = Subscription::with(['user', 'plan'])->latest();

        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'active') {
                $query->active();
            } else {
                $query->where('status', $this->filterStatus);
            }
        }

        if ($this->filterPlan !== '') {
            $query->where('plan_slug', $this->filterPlan);
        }

        if ($this->search !== '') {
            $query->whereHas('user', fn ($q) => $q
                ->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
            );
        }

        $plans       = SubscriptionPlan::active()->get();
        $stats       = [
            'total_active'  => Subscription::active()->count(),
            'total_expired' => Subscription::where('status', 'expired')->count(),
            'revenue_total' => Subscription::whereIn('status', ['active', 'expired'])
                ->sum('amount_paid'),
            'revenue_month' => Subscription::where('status', 'active')
                ->where('starts_at', '>=', now()->startOfMonth())
                ->sum('amount_paid'),
        ];

        // Analytics charts data
        $chartData = $this->getChartData();

        return view('livewire.admin.subscriptions', [
            'subscriptions' => $query->paginate(20),
            'plans'         => $plans,
            'stats'         => $stats,
            'chartData'     => $chartData,
        ])
            ->layout('layouts.admin')
            ->title('Admin — Subscriptions');
    }
}
