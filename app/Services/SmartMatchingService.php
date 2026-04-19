<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;

class SmartMatchingService
{
    public function __construct(
        protected SubscriptionLimitsService $limitsService
    ) {
    }

    /**
     * Score and return best workers for a given job.
     *
     * @return \Illuminate\Support\Collection<int, array{user: User, score: float, reasons: array}>
     */
    public function matchWorkersForJob(Job $job, int $limit = 10): \Illuminate\Support\Collection
    {
        $job->load(['skills', 'employer']);

        $workers = User::where('role', 'mfanyakazi')
            ->where('onboarding_completed', true)
            ->with(['skills', 'reviewsReceived', 'activeSubscription.plan'])
            ->withAvg('reviewsReceived', 'rating')
            ->withCount(['applications as completed_jobs' => function ($q) {
                $q->whereHas('job', fn ($j) => $j->where('status', 'completed'));
            }])
            ->get();

        $jobSkillIds = $job->skills->pluck('id')->toArray();
        $jobLat = $job->latitude;
        $jobLng = $job->longitude;

        return $workers->map(function (User $worker) use ($jobSkillIds, $jobLat, $jobLng, $job) {
            $score = 0;
            $reasons = [];

            // 1. Skills match (up to 40 points)
            $workerSkillIds = $worker->skills->pluck('id')->toArray();
            $matched = count(array_intersect($jobSkillIds, $workerSkillIds));
            $total = count($jobSkillIds);
            $skillScore = $total > 0 ? ($matched / $total) * 40 : 0;
            $score += $skillScore;
            if ($matched > 0) {
                $reasons[] = "Skills {$matched}/{$total} zinaoanisha";
            }

            // 2. Rating (up to 25 points)
            $rating = (float) ($worker->reviews_received_avg_rating ?? 0);
            $ratingScore = ($rating / 5) * 25;
            $score += $ratingScore;
            if ($rating >= 4.0) {
                $reasons[] = "Rating ya juu: ⭐ {$rating}";
            }

            // 3. Location proximity (up to 20 points)
            if ($jobLat && $jobLng && $worker->latitude && $worker->longitude) {
                $distance = $this->haversineKm($jobLat, $jobLng, $worker->latitude, $worker->longitude);
                if ($distance <= 5) {
                    $score += 20;
                    $reasons[] = "Karibu sana (~{$distance}km)";
                } elseif ($distance <= 20) {
                    $score += 15;
                    $reasons[] = "Karibu (~{$distance}km)";
                } elseif ($distance <= 50) {
                    $score += 8;
                } elseif ($distance <= 100) {
                    $score += 3;
                }
            } elseif ($job->location && $worker->mkoa) {
                // Text-based location match
                if (str_contains(strtolower($job->location), strtolower($worker->mkoa))) {
                    $score += 15;
                    $reasons[] = "Eneo linaoanisha ({$worker->mkoa})";
                }
            }

            // 4. Experience (up to 10 points)
            $exp = (int) ($worker->uzoefu_miaka ?? 0);
            $expScore = min($exp * 2, 10);
            $score += $expScore;
            if ($exp >= 3) {
                $reasons[] = "Uzoefu wa miaka {$exp}";
            }

            // 5. Completed jobs reliability (up to 5 points)
            $completedJobs = (int) ($worker->completed_jobs ?? 0);
            $reliabilityScore = min($completedJobs * 0.5, 5);
            $score += $reliabilityScore;
            if ($completedJobs > 0) {
                $reasons[] = "Kazi {$completedJobs} zilizokamilika";
            }

            // 6. Subscription plan boost (NEW)
            $boost = $this->limitsService->getSearchBoost($worker);
            $score += $boost;
            if ($boost > 0) {
                $planName = $worker->activeSubscription?->plan?->name ?? 'Winga Bora';
                $reasons[] = "⭐ {$planName} (+{$boost})";
            }

            return [
                'user' => $worker,
                'score' => round($score, 1),
                'reasons' => $reasons,
                'matched_skills' => $worker->skills->whereIn('id', $jobSkillIds)->pluck('name'),
                'rating' => round($rating, 1),
                'distance_label' => $this->getDistanceLabel($jobLat, $jobLng, $worker->latitude, $worker->longitude),
                'subscription_boost' => $boost,
            ];
        })
            ->sortByDesc('score')
            ->take($limit)
            ->values();
    }

    /**
     * Get best matching open jobs for a given worker.
     */
    public function matchJobsForWorker(User $worker, int $limit = 6): \Illuminate\Support\Collection
    {
        $worker->load(['skills']);
        $workerSkillIds = $worker->skills->pluck('id')->toArray();

        $jobs = Job::where('status', 'open')
            ->with(['employer', 'category', 'skills'])
            ->latest()
            ->limit(100)
            ->get();

        return $jobs->map(function (Job $job) use ($workerSkillIds, $worker) {
            $score = 0;

            // Skills match
            $jobSkillIds = $job->skills->pluck('id')->toArray();
            $matched = count(array_intersect($jobSkillIds, $workerSkillIds));
            $total = count($jobSkillIds);
            $score += $total > 0 ? ($matched / $total) * 50 : 10; // 10 points if no skills required

            // Location match
            if ($job->location && $worker->mkoa) {
                if (str_contains(strtolower($job->location), strtolower($worker->mkoa))) {
                    $score += 30;
                }
            }

            // Budget match with worker's rate
            $workerRate = (int) ($worker->bei_wastani ?? 0);
            if ($workerRate > 0 && $job->budget_min) {
                if ($job->budget_min >= $workerRate) {
                    $score += 20;
                } elseif ($job->budget_min >= $workerRate * 0.7) {
                    $score += 10;
                }
            }

            // Urgency bonus
            if ($job->urgency === 'very_urgent') {
                $score += 5;
            }

            return [
                'job' => $job,
                'score' => round($score, 1),
                'matched_skills' => $job->skills->whereIn('id', $workerSkillIds)->pluck('name'),
            ];
        })
            ->sortByDesc('score')
            ->take($limit)
            ->values();
    }

    private function haversineKm(?float $lat1, ?float $lng1, ?float $lat2, ?float $lng2): float
    {
        if (! $lat1 || ! $lng1 || ! $lat2 || ! $lng2) {
            return 999;
        }

        $R = 6371; // Earth radius in km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($R * $c, 1);
    }

    private function getDistanceLabel(?float $lat1, ?float $lng1, ?float $lat2, ?float $lng2): string
    {
        $km = $this->haversineKm($lat1, $lng1, $lat2, $lng2);
        if ($km >= 999) {
            return '—';
        }
        if ($km < 1) {
            return '<1km';
        }

        return "{$km}km";
    }
}
