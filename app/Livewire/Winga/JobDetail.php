<?php

namespace App\Livewire\Winga;

use App\Models\Application;
use App\Models\Job;
use App\Notifications\WingaNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class JobDetail extends Component
{
    public Job $job;

    public bool $showApplyModal = false;

    public string $coverLetter = '';

    public ?float $proposedBudget = null;

    public string $proposedDuration = '';

    public ?int $existingApplicationId = null;

    public function mount(string $slug, ?string $action = null): void
    {
        $workerId = Auth::id();

        $this->job = Job::query()
            ->where('slug', $slug)
            ->where(function ($q) use ($workerId) {
                $q->where('status', 'open')
                  ->orWhere('hired_worker_id', $workerId)
                  ->orWhereHas('applications', fn ($a) => $a->where('worker_id', $workerId));
            })
            ->with([
                'employer:id,name,avatar,bio',
                'category:id,name,icon',
                'skills:id,name',
            ])
            ->withCount('applications')
            ->firstOrFail();

        // Check if user already applied
        $existingApp = Application::where('job_id', $this->job->id)
            ->where('worker_id', Auth::id())
            ->first();

        if ($existingApp) {
            $this->existingApplicationId = $existingApp->id;
        }

        // Only show apply modal for open jobs
        if ($action === 'apply' && ! $this->existingApplicationId && $this->job->status === 'open') {
            $this->showApplyModal = true;
        }

        // Set default budget to job's budget_min
        $this->proposedBudget = $this->job->budget_min;
    }

    public function openApplyModal(): void
    {
        if ($this->existingApplicationId) {
            $this->dispatch('toast', message: 'Umeshaomba kazi hii tayari.', type: 'warning');

            return;
        }

        $this->showApplyModal = true;
    }

    public function closeApplyModal(): void
    {
        $this->showApplyModal = false;
        $this->reset(['coverLetter', 'proposedBudget', 'proposedDuration']);
        $this->proposedBudget = $this->job->budget_min;
    }

    public function submitApplication(): void
    {
        if ($this->existingApplicationId) {
            $this->dispatch('toast', message: 'Umeshaomba kazi hii tayari.', type: 'warning');

            return;
        }

        $this->validate([
            'coverLetter'     => ['required', 'string', 'min:50', 'max:2000'],
            'proposedBudget'  => ['required', 'numeric', 'min:1000'],
            'proposedDuration' => ['required', 'string', 'min:2', 'max:100'],
        ], [
            'coverLetter.required'    => 'Tafadhali andika barua ya utangulizi.',
            'coverLetter.min'         => 'Barua yako iwe na angalau herufi 50.',
            'proposedBudget.required' => 'Tafadhali weka bei unayopendekeza.',
            'proposedBudget.min'      => 'Bei lazima isiwe chini ya TZS 1,000.',
            'proposedDuration.required' => 'Tafadhali weka muda utakaochukua.',
        ]);

        $application = Application::create([
            'job_id' => $this->job->id,
            'worker_id' => Auth::id(),
            'cover_letter' => $this->coverLetter,
            'proposed_budget' => $this->proposedBudget,
            'proposed_duration' => $this->proposedDuration,
            'status' => 'pending',
        ]);

        $this->existingApplicationId = $application->id;

        // Notify employer
        $this->job->employer->notify(new WingaNotification(
            title: 'Ombi Jipya la Kazi!',
            message: Auth::user()->name.' ameomba kazi yako: '.$this->job->title,
            icon: 'document-text',
            color: 'blue',
            action_url: route('mteja.maombi', ['job_id' => $this->job->id]),
            action_label: 'Angalia Ombi',
        ));

        $this->closeApplyModal();

        $this->dispatch('toast', message: 'Ombi lako limetumwa! Subiri mteja akubali.', type: 'success');
    }

    public function render(): View
    {
        return view('livewire.winga.job-detail')
            ->layout('layouts.winga')
            ->title($this->job->title.' - Maelezo ya Kazi');
    }
}
