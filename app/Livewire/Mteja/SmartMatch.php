<?php

namespace App\Livewire\Mteja;

use App\Models\Conversation;
use App\Models\Job;
use App\Models\User;
use App\Services\SmartMatchingService;
use Livewire\Component;

class SmartMatch extends Component
{
    public ?int $jobId = null;

    public bool $ready = false;

    public array $matches = [];

    public array $jobs = [];

    // Profile View State
    public ?int $viewingWorkerId = null;

    public ?array $selectedWorker = null;

    public function mount(): void
    {
        $this->jobs = Job::where('employer_id', auth()->id())
            ->whereIn('status', ['open', 'in_progress'])
            ->where('is_approved', true)
            ->latest()
            ->get(['id', 'title'])
            ->toArray();

        if (! empty($this->jobs)) {
            $this->jobId = $this->jobs[0]['id'];
            $this->loadMatches();
        }
    }

    public function updatedJobId(): void
    {
        $this->ready = false;
        $this->matches = [];
        $this->closeProfile();
        $this->loadMatches();
    }

    public function loadMatches(): void
    {
        if (! $this->jobId) {
            return;
        }

        $job = Job::with(['skills', 'employer'])->find($this->jobId);
        if (! $job) {
            return;
        }

        $service = app(SmartMatchingService::class);
        $results = $service->matchWorkersForJob($job, 10);

        $this->matches = $results->map(fn ($r) => [
            'id' => $r['user']->id,
            'name' => $r['user']->name,
            'avatar_url' => $r['user']->avatar
                ? asset('storage/'.$r['user']->avatar)
                : 'https://ui-avatars.com/api/?name='.urlencode($r['user']->name).'&background=8b5cf6&color=fff&size=80',
            'score' => $r['score'],
            'rating' => $r['rating'],
            'reasons' => $r['reasons'],
            'matched_skills' => $r['matched_skills']->toArray(),
            'distance_label' => $r['distance_label'],
            'location' => trim(implode(', ', array_filter([$r['user']->mtaa, $r['user']->wilaya, $r['user']->mkoa]))),
            'bei_aina' => $r['user']->bei_aina ?? 'siku',
            'bei_wastani' => (int) ($r['user']->bei_wastani ?? 0),
            'uzoefu_miaka' => (int) ($r['user']->uzoefu_miaka ?? 0),
        ])->toArray();

        $this->ready = true;
    }

    public function viewProfile(int $workerId)
    {
        $worker = User::with(['portfolios', 'reviewsReceived.reviewer', 'skills'])->find($workerId);
        if (! $worker) {
            return;
        }

        $ratingAvg = $worker->reviewsReceived->avg('rating') ?? 0.0;
        $ratingCount = $worker->reviewsReceived->count();

        $this->selectedWorker = [
            'id' => $worker->id,
            'name' => $worker->name,
            'avatar_url' => $worker->avatar ? asset('storage/'.$worker->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($worker->name).'&background=8b5cf6&color=fff',
            'location' => trim(implode(', ', array_filter([$worker->mtaa, $worker->wilaya, $worker->mkoa]))) ?: 'Hajajaza Eneo',
            'bio' => $worker->bio ?? 'Mfanyakazi huyu bado hajaandika maelezo yoyote kuhusu wasifu wake.',
            'category' => $worker->kategoria ?? 'Haijabainishwa',
            'bei' => number_format((int) $worker->bei_wastani).' / '.ucfirst($worker->bei_aina ?? 'siku'),
            'uzoefu' => $worker->uzoefu_miaka.' miaka',
            'rating' => round($ratingAvg, 1),
            'review_count' => $ratingCount,
            'skills' => $worker->skills->pluck('name')->toArray(),
            'portfolio' => $worker->portfolios->map(fn ($item) => [
                'title' => $item->title,
                'description' => $item->description,
                'image_url' => asset('storage/'.$item->image_path),
            ])->toArray(),
            'reviews' => $worker->reviewsReceived->map(fn ($rev) => [
                'reviewer_name' => $rev->reviewer->name,
                'reviewer_avatar' => $rev->reviewer->avatar ? asset('storage/'.$rev->reviewer->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($rev->reviewer->name).'&background=14b8a6',
                'rating' => $rev->rating,
                'comment' => $rev->comment,
                'date' => $rev->created_at->diffForHumans(),
            ])->toArray(),
        ];

        $this->viewingWorkerId = $workerId;
    }

    public function closeProfile()
    {
        $this->viewingWorkerId = null;
        $this->selectedWorker = null;
    }

    public function startChat(int $workerId)
    {
        if (! $this->jobId) {
            return;
        }

        $employerId = auth()->id();
        $jobId = $this->jobId;

        $conversation = Conversation::firstOrCreate([
            'employer_id' => $employerId,
            'worker_id' => $workerId,
            'job_id' => $jobId,
        ]);

        return redirect()->route('messages.conversation', ['conversationId' => $conversation->id]);
    }

    public function render()
    {
        return view('livewire.mteja.smart-match')
            ->layout('layouts.mteja')
            ->title('Smart Match — Pata Mfanyakazi Bora');
    }
}
