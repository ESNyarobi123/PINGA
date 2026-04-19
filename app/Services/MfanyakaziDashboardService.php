<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Contracts\Auth\Authenticatable;

class MfanyakaziDashboardService
{
    /**
     * @return array{stats: array{kazi_karibu: int, maombi_active: int, kazi_zilizomalika: int, mapato_jumla: float, maombi_wiki: int, kukubaliwa_wiki: int, mapato_wiki: float}, recent_jobs: \Illuminate\Support\Collection}
     */
    public function data(Authenticatable $user): array
    {
        $workerId = $user->getAuthIdentifier();

        $kaziKaribu = Job::where('status', 'open')->count();

        $maombiActive = Application::where('worker_id', $workerId)->whereIn('status', ['pending', 'accepted'])->count();

        $kaziZilizomalika = Job::where('hired_worker_id', $workerId)->where('status', 'completed')->count();

        $mapatoJumla = (float) Payment::where('worker_id', $workerId)
            ->where('status', 'released')
            ->sum('worker_amount');

        $weekStart = now()->startOfWeek();
        $maombiWiki = Application::where('worker_id', $workerId)->where('created_at', '>=', $weekStart)->count();
        $kukubaliwaWiki = Application::where('worker_id', $workerId)->where('status', 'accepted')->where('updated_at', '>=', $weekStart)->count();
        $mapatoWiki = (float) Transaction::where('user_id', $workerId)
            ->where('type', 'credit')
            ->where('created_at', '>=', $weekStart)
            ->sum('amount');

        $recentJobs = Job::where('status', 'open')
            ->where('is_approved', true)
            ->with(['employer', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $ongoingJob = Job::where('hired_worker_id', $workerId)
            ->where('status', 'in_progress')
            ->with(['employer:id,name,phone,whatsapp,avatar'])
            ->first();

        return [
            'stats' => [
                'kazi_karibu'       => $kaziKaribu,
                'maombi_active'     => $maombiActive,
                'kazi_zilizomalika' => $kaziZilizomalika,
                'mapato_jumla'      => $mapatoJumla,
                'maombi_wiki'       => $maombiWiki,
                'kukubaliwa_wiki'   => $kukubaliwaWiki,
                'mapato_wiki'       => $mapatoWiki,
            ],
            'recent_jobs' => $recentJobs,
            'ongoing_job' => $ongoingJob,
        ];
    }
}
