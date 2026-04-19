<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Payment;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Str;

class AdminDashboardService
{
    /**
     * @return array{stats: array, user_growth: array<int, int>, categories: array<int, array{name: string, percent: float, color: string}>, locations: array<int, array{name: string, percent: float, color: string}>, activities: array<int, array{icon: string, text: string, time: string, color: string}>}
     */
    public function data(): array
    {
        $watumiajiWote = User::count();
        $watumiajiWapyaLeo = User::whereDate('created_at', today())->count();

        $kaziZote = Job::count();
        $kaziWazi = Job::where('status', 'open')->count();
        $kaziCompleted = Job::where('status', 'completed')->count();
        $completionRate = $kaziZote > 0 ? round(($kaziCompleted / $kaziZote) * 100, 1) : 0;

        $paymentsReleased = Payment::where('status', 'released');
        $mapatoLeo = (float) (clone $paymentsReleased)->whereDate('escrow_released_at', today())->sum('platform_fee');
        $mapatoWiki = (float) (clone $paymentsReleased)->where('escrow_released_at', '>=', now()->startOfWeek())->sum('platform_fee');
        $mapatoMwezi = (float) (clone $paymentsReleased)->whereMonth('escrow_released_at', now()->month)->sum('platform_fee');

        $disputesCount = Payment::where('status', 'disputed')->count();
        $paymentsTotal = Payment::count();
        $disputesPercent = $paymentsTotal > 0 ? round(($disputesCount / $paymentsTotal) * 100, 1) : 0;

        // Payout stats
        $payoutsToday       = Payment::where('payout_status', 'completed')->whereDate('escrow_released_at', today())->count();
        $payoutsTodayAmount = (float) Payment::where('payout_status', 'completed')->whereDate('escrow_released_at', today())->sum('worker_amount');
        $failedPayouts      = Payment::where('payout_status', 'failed')->count()
            + WithdrawalRequest::where('payout_status', 'failed')->count();
        $completedPayouts   = Payment::where('payout_status', 'completed')->count()
            + WithdrawalRequest::where('payout_status', 'completed')->count();
        $totalPayoutsAll    = $completedPayouts + $failedPayouts
            + Payment::where('payout_status', 'processing')->count();
        $payoutSuccessRate  = $totalPayoutsAll > 0 ? round(($completedPayouts / $totalPayoutsAll) * 100, 1) : 100.0;
        $totalFeesCollected = (float) Payment::where('status', 'released')->sum('platform_fee');

        $userGrowth = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = User::whereDate('created_at', $date)->count();
            $userGrowth[] = $count;
        }
        $maxGrowth = max($userGrowth ?: [1]);
        $userGrowth = array_map(fn ($c) => $maxGrowth > 0 ? (int) round(($c / $maxGrowth) * 100) : 0, $userGrowth);

        $categoriesRaw = Job::query()
            ->join('categories', 'job_listings.category_id', '=', 'categories.id')
            ->where('job_listings.status', 'open')
            ->selectRaw('categories.name as name, count(*) as total')
            ->groupBy('categories.id', 'categories.name')
            ->get();
        $totalByCat = $categoriesRaw->sum('total');
        $colors = ['bg-winga-500', 'bg-blue-500', 'bg-pink-500', 'bg-green-500', 'bg-amber-500'];
        $categories = $categoriesRaw->take(5)->values()->map(function ($row, $i) use ($totalByCat, $colors) {
            $percent = $totalByCat > 0 ? round(($row->total / $totalByCat) * 100, 1) : 0;

            return ['name' => $row->name, 'percent' => $percent, 'color' => $colors[$i % count($colors)]];
        })->all();

        $locationsRaw = Job::where('status', 'open')->whereNotNull('location')->where('location', '!=', '')->get();
        $locCounts = [];
        foreach ($locationsRaw as $job) {
            $loc = trim(explode(',', $job->location)[0] ?? $job->location);
            $locCounts[$loc] = ($locCounts[$loc] ?? 0) + 1;
        }
        arsort($locCounts);
        $locCounts = array_slice($locCounts, 0, 5, true);
        $totalByLoc = array_sum($locCounts);
        $locations = [];
        $i = 0;
        foreach ($locCounts as $name => $total) {
            $percent = $totalByLoc > 0 ? round(($total / $totalByLoc) * 100, 1) : 0;
            $locations[] = ['name' => $name, 'percent' => $percent, 'color' => $colors[$i % count($colors)]];
            $i++;
        }

        $activities = $this->buildActivities();

        return [
            'stats' => [
                'watumiaji_wote'       => $watumiajiWote,
                'watumiaji_wapya_leo'  => $watumiajiWapyaLeo,
                'kazi_zote'            => $kaziZote,
                'kazi_wazi'            => $kaziWazi,
                'mapato_leo'           => $mapatoLeo,
                'mapato_wiki'          => $mapatoWiki,
                'mapato_mwezi'         => $mapatoMwezi,
                'disputes_percent'     => $disputesPercent,
                'completion_rate'      => $completionRate,
                'payouts_today'        => $payoutsToday,
                'payouts_today_amount' => $payoutsTodayAmount,
                'failed_payouts'       => $failedPayouts,
                'payout_success_rate'  => $payoutSuccessRate,
                'total_fees_collected' => $totalFeesCollected,
            ],
            'user_growth' => $userGrowth,
            'categories' => $categories,
            'locations' => $locations,
            'activities' => $activities,
        ];
    }

    /**
     * @return array<int, array{icon: string, text: string, time: string, color: string}>
     */
    private function buildActivities(): array
    {
        $activities = [];

        $recentJob = Job::latest()->first();
        if ($recentJob) {
            $activities[] = [
                'icon' => 'briefcase',
                'text' => 'Kazi mpya imepostiwa "'.Str::limit($recentJob->title, 35).'"',
                'time' => $recentJob->created_at->diffForHumans(),
                'color' => 'winga',
            ];
        }

        $recentPayment = Payment::where('status', 'released')->latest('escrow_released_at')->first();
        if ($recentPayment) {
            $activities[] = [
                'icon' => 'banknotes',
                'text' => 'Malipo TSh '.number_format($recentPayment->amount).' yameingia Escrow',
                'time' => $recentPayment->escrow_released_at?->diffForHumans() ?? $recentPayment->updated_at->diffForHumans(),
                'color' => 'green',
            ];
        }

        $disputed = Payment::where('status', 'disputed')->latest()->first();
        if ($disputed) {
            $activities[] = [
                'icon' => 'exclamation-triangle',
                'text' => 'Mgogoro kwenye malipo umefunguliwa',
                'time' => $disputed->updated_at->diffForHumans(),
                'color' => 'red',
            ];
        }

        $newUsersToday = User::whereDate('created_at', today())->count();
        if ($newUsersToday > 0) {
            $activities[] = [
                'icon' => 'user-plus',
                'text' => "Watumiaji {$newUsersToday} wapya wamejisajili leo",
                'time' => 'Leo',
                'color' => 'blue',
            ];
        }

        return array_slice(array_reverse($activities), -4);
    }
}
