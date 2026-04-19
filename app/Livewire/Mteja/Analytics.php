<?php

namespace App\Livewire\Mteja;

use App\Models\Application;
use App\Models\Job;
use App\Models\Payment;
use Livewire\Component;

class Analytics extends Component
{
    public bool $ready = false;

    public string $period = '30'; // 7, 30, 90 days

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
        $days = (int) $this->period;
        $since = now()->subDays($days);

        // Core stats
        $totalJobs = Job::where('employer_id', $user->id)->count();
        $activeJobs = Job::where('employer_id', $user->id)->where('status', 'open')->count();
        $completedJobs = Job::where('employer_id', $user->id)->where('status', 'completed')->count();
        $totalApplications = Application::whereHas('job', fn ($q) => $q->where('employer_id', $user->id))->count();
        $pendingApplications = Application::whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
            ->where('status', 'pending')->count();

        $totalSpent = Payment::where('employer_id', $user->id)
            ->whereIn('status', ['released', 'escrowed'])->sum('amount');
        $platformFees = Payment::where('employer_id', $user->id)
            ->whereIn('status', ['released'])->sum('platform_fee');
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

        // Applications trend per day
        $this->applicationsTrend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $count = Application::whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
                ->whereDate('created_at', $date)->count();
            $this->applicationsTrend[] = [
                'date' => now()->subDays($i)->format('d M'),
                'count' => $count,
            ];
        }

        // Budget breakdown
        $jobIds = Job::where('employer_id', $user->id)->pluck('id');
        $budgetRanges = [
            'Chini 50K' => [0, 50000],
            '50K-200K' => [50000, 200000],
            '200K-500K' => [200000, 500000],
            '500K+' => [500000, 999999999],
        ];
        $this->budgetData = [];
        foreach ($budgetRanges as $label => [$min, $max]) {
            $count = Job::where('employer_id', $user->id)
                ->where('budget_min', '>=', $min)
                ->where('budget_min', '<', $max)
                ->count();
            $this->budgetData[] = ['label' => $label, 'count' => $count];
        }

        // Job status donut
        $statuses = ['open', 'in_progress', 'completed', 'cancelled', 'disputed'];
        $this->jobStatusData = [];
        foreach ($statuses as $status) {
            $count = Job::where('employer_id', $user->id)->where('status', $status)->count();
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
