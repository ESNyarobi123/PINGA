<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Contracts\Auth\Authenticatable;

class MuajiliDashboardService
{
    /**
     * @return array{stats: array{total_kazi: int, kazi_active: int, maombi_mapya: int, wallet: float}, recent_jobs: \Illuminate\Support\Collection}
     */
    public function data(Authenticatable $user): array
    {
        $employerId = $user->getAuthIdentifier();

        $totalKazi = Job::where('employer_id', $employerId)->count();
        $kaziActive = Job::where('employer_id', $employerId)->where('status', 'open')->count();

        $jobIds = Job::where('employer_id', $employerId)->pluck('id');
        $maombiMapya = Application::whereIn('job_id', $jobIds)->where('status', 'pending')->where('created_at', '>=', now()->subDays(7))->count();

        $wallet = (float) ($user->wallet_balance ?? 0);

        $recentJobs = Job::where('employer_id', $employerId)
            ->withCount('applications')
            ->with('skills')
            ->latest()
            ->take(5)
            ->get();

        return [
            'stats' => [
                'total_kazi' => $totalKazi,
                'kazi_active' => $kaziActive,
                'maombi_mapya' => $maombiMapya,
                'wallet' => $wallet,
            ],
            'recent_jobs' => $recentJobs,
        ];
    }
}
