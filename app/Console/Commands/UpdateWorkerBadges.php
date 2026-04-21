<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SubscriptionLimitsService;
use Illuminate\Console\Command;

class UpdateWorkerBadges extends Command
{
    protected $signature = 'workers:update-badges
                            {--dry-run : Preview changes without applying}
                            {--worker= : Specific worker ID to update}';

    protected $description = 'Update worker badges (verified, top_rated, avg_response_hours) based on subscription and performance';

    public function handle(SubscriptionLimitsService $limitsService): int
    {
        $dryRun = $this->option('dry-run');
        $workerId = $this->option('worker');

        $query = User::where('role', 'mfanyakazi')
            ->when($workerId, fn ($q) => $q->where('id', $workerId))
            ->with(['activeSubscription.subscriptionPlan', 'reviewsReceived', 'applications']);

        $workers = $query->get();

        $this->info("Processing {$workers->count()} workers...");
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be saved');
        }

        $stats = [
            'verified_updated' => 0,
            'top_rated_updated' => 0,
            'response_time_updated' => 0,
        ];

        foreach ($workers as $worker) {
            $updates = [];

            // Update verified status (Kawaida and Bora with good standing)
            $shouldBeVerified = $limitsService->hasVerifiedBadge($worker)
                && $worker->reviewsReceived()->count() >= 3
                && $worker->reviews_received_avg_rating >= 4.0;

            if ($worker->is_verified !== $shouldBeVerified) {
                $updates['is_verified'] = $shouldBeVerified;
                $stats['verified_updated']++;
            }

            // Update top_rated status (Bora with excellent performance)
            $shouldBeTopRated = $limitsService->isTopRatedEligible($worker)
                && $worker->reviews_received_avg_rating >= 4.5
                && $worker->applications()->where('status', 'accepted')->count() >= 5;

            if ($worker->is_top_rated !== $shouldBeTopRated) {
                $updates['is_top_rated'] = $shouldBeTopRated;
                $stats['top_rated_updated']++;
            }

            // Update avg_response_hours from message data
            if ($limitsService->hasChatBadge($worker)) {
                $avgHours = $this->calculateAvgResponseTime($worker);
                if ($avgHours !== null && $worker->avg_response_hours !== $avgHours) {
                    $updates['avg_response_hours'] = $avgHours;
                    $stats['response_time_updated']++;
                }
            }

            if (! empty($updates) && ! $dryRun) {
                $worker->update($updates);
                $this->info("Updated worker {$worker->id} ({$worker->name}): ".json_encode($updates));
            } elseif (! empty($updates) && $dryRun) {
                $this->line("[DRY RUN] Would update worker {$worker->id}: ".json_encode($updates));
            }
        }

        $this->newLine();
        $this->info('Update complete!');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Verified badges updated', $stats['verified_updated']],
                ['Top Rated badges updated', $stats['top_rated_updated']],
                ['Response times updated', $stats['response_time_updated']],
            ]
        );

        return self::SUCCESS;
    }

    private function calculateAvgResponseTime(User $worker): ?float
    {
        // Calculate average response time from conversations
        $avgMinutes = $worker->conversations()
            ->whereNotNull('worker_replied_at')
            ->whereNotNull('employer_sent_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, employer_sent_at, worker_replied_at)) as avg_minutes')
            ->value('avg_minutes');

        return $avgMinutes ? round($avgMinutes / 60, 1) : null;
    }
}
