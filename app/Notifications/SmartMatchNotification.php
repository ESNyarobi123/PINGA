<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SmartMatchNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Job $job
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'job_id' => $this->job->id,
            'title' => "Kazi Mpya Inayofanana na Ujuzi Wako!",
            'message' => "{$this->job->title} - {$this->job->employer?->name}",
            'location' => $this->job->location,
            'budget' => $this->job->budget_min
                ? 'TZS '.number_format($this->job->budget_min)
                : 'Maelewano',
            'type' => 'smart_match',
            'action_url' => route('winga.kazi-karibu', ['apply' => $this->job->id]),
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return [
            'job_id' => $this->job->id,
            'title' => "Kazi Mpya Inayofanana na Ujuzi Wako!",
            'message' => $this->job->title,
            'type' => 'smart_match',
        ];
    }
}
