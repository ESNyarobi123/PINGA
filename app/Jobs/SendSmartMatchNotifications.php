<?php

namespace App\Jobs;

use App\Models\Job;
use App\Models\User;
use App\Services\SmartMatchingService;
use App\Services\SubscriptionLimitsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SmartMatchNotification;

class SendSmartMatchNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public Job $job,
        public array $workerIds = []
    ) {
    }

    public function handle(SmartMatchingService $matchingService, SubscriptionLimitsService $limitsService): void
    {
        $this->job->load(['employer', 'skills', 'category']);

        // Get matched workers if not provided
        if (empty($this->workerIds)) {
            $matches = $matchingService->matchWorkersForJob($this->job, 20);
            $workers = collect($matches)->pluck('user')->all();
        } else {
            $workers = User::whereIn('id', $this->workerIds)
                ->with('activeSubscription.plan')
                ->get();
        }

        // Group workers by priority tier
        $priorityGroups = [
            'immediate' => [], // Bora - immediate
            'delayed_15' => [], // Kawaida - 15 min delay
            'delayed_60' => [], // Msingi - 1 hour delay
        ];

        foreach ($workers as $worker) {
            $planSlug = $worker->activeSubscription?->plan?->slug ?? 'msingi';

            match ($planSlug) {
                'bora' => $priorityGroups['immediate'][] = $worker,
                'kawaida' => $priorityGroups['delayed_15'][] = $worker,
                default => $priorityGroups['delayed_60'][] = $worker,
            };
        }

        // Send immediate notifications to Bora subscribers
        foreach ($priorityGroups['immediate'] as $worker) {
            $this->sendNotification($worker);
        }

        // Schedule delayed notifications for Kawaida (15 minutes)
        if (! empty($priorityGroups['delayed_15'])) {
            dispatch(new self($this->job, collect($priorityGroups['delayed_15'])->pluck('id')->all()))
                ->delay(now()->addMinutes(15));
        }

        // Schedule delayed notifications for Msingi (1 hour)
        if (! empty($priorityGroups['delayed_60'])) {
            dispatch(new self($this->job, collect($priorityGroups['delayed_60'])->pluck('id')->all()))
                ->delay(now()->addHour());
        }
    }

    private function sendNotification(User $worker): void
    {
        try {
            Notification::send($worker, new SmartMatchNotification($this->job));
        } catch (\Exception $e) {
            \Log::warning("Failed to send SmartMatch notification to worker {$worker->id}: {$e->getMessage()}");
        }
    }
}
