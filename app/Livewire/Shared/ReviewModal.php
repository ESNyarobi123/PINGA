<?php

namespace App\Livewire\Shared;

use App\Models\Job;
use App\Models\Review;
use App\Notifications\WingaNotification;
use Livewire\Component;

class ReviewModal extends Component
{
    public bool $show = false;

    public ?int $jobId = null;

    public int $rating = 0;

    public string $comment = '';

    public ?Job $job = null;

    protected array $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ];

    public function openReview(int $jobId): void
    {
        $this->jobId = $jobId;
        $this->job = Job::with(['employer', 'hiredWorker'])->find($jobId);
        $this->show = true;
        $this->rating = 0;
        $this->comment = '';
    }

    public function close(): void
    {
        $this->show = false;
    }

    public function setRating(int $stars): void
    {
        $this->rating = $stars;
    }

    public function submit(): void
    {
        $this->validate();

        if (! $this->job) {
            return;
        }

        $user = auth()->user();

        // Determine who we're reviewing
        $revieweeId = $user->id === $this->job->employer_id
            ? $this->job->hired_worker_id
            : $this->job->employer_id;

        if (! $revieweeId) {
            $this->dispatch('toast', message: 'Hitilafu: Mtu wa kukaguliwa hajapatikana.', type: 'error');

            return;
        }

        // Prevent duplicate reviews
        $exists = Review::where('job_id', $this->job->id)
            ->where('reviewer_id', $user->id)
            ->exists();

        if ($exists) {
            $this->dispatch('toast', message: 'Tayari umetoa tathmini kwa kazi hii.', type: 'warning');
            $this->close();

            return;
        }

        Review::create([
            'job_id' => $this->job->id,
            'reviewer_id' => $user->id,
            'reviewee_id' => $revieweeId,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        // Notify the reviewee
        $reviewee = \App\Models\User::find($revieweeId);
        if ($reviewee) {
            $stars = str_repeat('⭐', $this->rating);
            $reviewee->notify(new WingaNotification(
                title: 'Tathmini mpya!',
                message: "{$user->name} amekupa tathmini {$stars}.",
                icon: 'star',
                color: 'yellow',
            ));
        }

        $this->dispatch('toast', message: 'Asante! Tathmini yako imehifadhiwa.', type: 'success');
        $this->close();
    }

    public function render()
    {
        return view('livewire.shared.review-modal');
    }
}
