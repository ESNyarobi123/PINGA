<?php

namespace App\Livewire\Mteja;

use App\Models\Application;
use App\Models\Job;
use App\Models\Payment;
use Livewire\Component;

class Analytics extends Component
{
    public bool $ready = false;

    public string $period = '30'; // 7, 30, 90, all

    public array $stats = [];

    public array $applicationsTrend = [];

    public array $budgetData = [];

    public array $jobStatusData = [];

    public array $topCategories = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function updatedPeriod(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $user = auth()->user();
        $period = in_array($this->period, ['7', '30', '90', 'all'], true) ? $this->period : '30';
        $days = $period === 'all' ? null : (int) $period;
        $since = $days === null ? null : now()->subDays($days);

        // Core stats
        $jobsQuery = Job::query()
            ->where('employer_id', $user->id)
            ->when($since, fn ($q) => $q->where('created_at', '>=', $since));

        $applicationsQuery = Application::query()
            ->whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
            ->when($since, fn ($q) => $q->where('created_at', '>=', $since));

        $paymentsQuery = Payment::query()
            ->where('employer_id', $user->id)
            ->when($since, fn ($q) => $q->where('created_at', '>=', $since));

        $totalJobs = (clone $jobsQuery)->count();
        $activeJobs = (clone $jobsQuery)->where('status', 'open')->count();
        $completedJobs = (clone $jobsQuery)->where('status', 'completed')->count();
        $totalApplications = (clone $applicationsQuery)->count();
        $pendingApplications = (clone $applicationsQuery)->where('status', 'pending')->count();

        $totalSpent = (clone $paymentsQuery)->whereIn('status', ['released', 'escrowed'])->sum('amount');
        $platformFees = (clone $paymentsQuery)->whereIn('status', ['released'])->sum('platform_fee');
        $walletBalance = (float) ($user->wallet_balance ?? 0);

        $this->stats = [
            'total_jobs' => $totalJobs,
            'active_jobs' => $activeJobs,
            'completed_jobs' => $completedJobs,
            'completion_rate' => $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100) : 0,
            'total_applications' => $totalApplications,
            'pending_applications' => $pendingApplications,
            'total_spent' => (float) $totalSpent,
            'platform_fees' => (float) $platformFees,
            'wallet_balance' => $walletBalance,
            'avg_per_job' => $completedJobs > 0 ? round($totalSpent / $completedJobs) : 0,
        ];

        // Applications trend
        $this->applicationsTrend = [];
        if ($days !== null) {
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $count = Application::query()
                    ->whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
                    ->whereDate('created_at', $date)
                    ->count();

                $this->applicationsTrend[] = [
                    'date' => now()->subDays($i)->format('d M'),
                    'count' => $count,
                ];
            }
        } else {
            $firstApplicationAt = Application::query()
                ->whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
                ->min('created_at');

            $start = $firstApplicationAt ? now()->parse($firstApplicationAt)->startOfMonth() : now()->startOfMonth();
            $end = now()->startOfMonth();
            $months = min(max($start->diffInMonths($end) + 1, 1), 24);

            for ($i = $months - 1; $i >= 0; $i--) {
                $monthStart = $end->copy()->subMonths($i);
                $monthEnd = $monthStart->copy()->endOfMonth();

                $count = Application::query()
                    ->whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();

                $this->applicationsTrend[] = [
                    'date' => $monthStart->format('M Y'),
                    'count' => $count,
                ];
            }
        }

        // Budget breakdown
        $budgetRanges = [
            'Chini 50K' => [0, 50000],
            '50K-200K' => [50000, 200000],
            '200K-500K' => [200000, 500000],
            '500K+' => [500000, 999999999],
        ];
        $this->budgetData = [];
        foreach ($budgetRanges as $label => [$min, $max]) {
            $count = Job::query()
                ->where('employer_id', $user->id)
                ->when($since, fn ($q) => $q->where('created_at', '>=', $since))
                ->where('budget_min', '>=', $min)
                ->where('budget_min', '<', $max)
                ->count();
            $this->budgetData[] = ['label' => $label, 'count' => $count];
        }

        // Job status donut
        $statuses = ['open', 'in_progress', 'completed', 'cancelled', 'disputed'];
        $this->jobStatusData = [];
        foreach ($statuses as $status) {
            $count = Job::query()
                ->where('employer_id', $user->id)
                ->when($since, fn ($q) => $q->where('created_at', '>=', $since))
                ->where('status', $status)
                ->count();
            $this->jobStatusData[] = ['status' => $status, 'count' => $count];
        }

        $this->ready = true;
    }

    public function render()
    {
        return view('livewire.mteja.analytics')
            ->layout('layouts.mteja')
            ->title('Analytics — Takwimu Zangu');
    }
}
