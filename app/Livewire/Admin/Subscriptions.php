<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Subscriptions extends Component
{
    use WithPagination;

    public string $filterStatus = 'all';

    public string $filterPlan = '';

    public string $search = '';

    // Manual activation form
    public bool $showManualForm = false;

    public string $manualUserId = '';

    public string $manualPlanSlug = '';

    public string $manualNotes = '';

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterPlan(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function activate(int $subscriptionId): void
    {
        $sub = Subscription::with('subscriptionPlan')->find($subscriptionId);
        if (! $sub) {
            $this->dispatch('toast', message: __('messages.admin_subs.subscription_not_found'), type: 'error');

            return;
        }

        $plan = $sub->subscriptionPlan;
        if (! $plan) {
            $this->dispatch('toast', message: 'Mpango haupatikani.', type: 'error');

            return;
        }

        app(SubscriptionService::class)->activate(
            $sub->user,
            $plan,
            'admin-manual-'.$sub->id.'-'.now()->timestamp,
            'admin',
            forceReplace: true
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
            'manualUserId' => 'required|exists:users,id',
            'manualPlanSlug' => 'required|exists:subscription_plans,slug',
        ]);

        $user = User::find($this->manualUserId);
        $plan = SubscriptionPlan::where('slug', $this->manualPlanSlug)->first();

        app(SubscriptionService::class)->activate(
            $user,
            $plan,
            'admin-grant-'.$user->id.'-'.now()->timestamp,
            'admin',
            forceReplace: true
        );

        $this->reset(['showManualForm', 'manualUserId', 'manualPlanSlug', 'manualNotes']);
        $this->dispatch('toast', message: "Subscription ya {$plan->name} imewashwa kwa {$user->name}.", type: 'success');
    }

    /**
     * Paid, non-pending subscription rows (excludes placeholder pending Snippe rows).
     */
    private function paidSubscriptionQuery(): Builder
    {
        return Subscription::query()
            ->whereIn('status', ['active', 'expired'])
            ->where('payment_status', 'completed');
    }

    private function getChartData(): array
    {
        $paid = fn (): Builder => clone $this->paidSubscriptionQuery();

        // Revenue by plan — cumulative amount_paid on each row (includes renewals on same row).
        $revenueByPlan = $paid()->selectRaw('plan_slug, SUM(amount_paid) as total')
            ->groupBy('plan_slug')
            ->pluck('total', 'plan_slug')
            ->toArray();

        // Subscription rows by plan (historical active + expired, paid).
        $subsByPlan = $paid()->selectRaw('plan_slug, COUNT(*) as count')
            ->groupBy('plan_slug')
            ->pluck('count', 'plan_slug')
            ->toArray();

        // New subscription rows created per month (renewals update same row → not counted here).
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = $paid()
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount_paid');
            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => (float) $revenue,
            ];
        }

        // New paid subscription rows per day (by created_at).
        $dailySubs = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $count = $paid()->whereDate('created_at', $day->toDateString())->count();
            $dailySubs[] = [
                'date' => $day->format('d M'),
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
        $query = Subscription::with(['user', 'subscriptionPlan'])->latest();

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
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                })
            );
        }

        $plans = SubscriptionPlan::active()->get();
        $stats = [
            'total_active' => Subscription::active()->count(),
            'total_expired' => Subscription::where('status', 'expired')->count(),
            'revenue_total' => (clone $this->paidSubscriptionQuery())->sum('amount_paid'),
            // New paid rows created this month (excludes renewal-only updates on existing rows).
            'revenue_month' => (clone $this->paidSubscriptionQuery())
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('amount_paid'),
        ];

        // Analytics charts data
        $chartData = $this->getChartData();

        return view('livewire.admin.subscriptions', [
            'subscriptions' => $query->paginate(20),
            'plans' => $plans,
            'stats' => $stats,
            'chartData' => $chartData,
        ])
            ->layout('layouts.admin')
            ->title('Admin — Subscriptions');
    }
}
