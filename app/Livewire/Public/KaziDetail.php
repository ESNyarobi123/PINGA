<?php

namespace App\Livewire\Public;

use App\Models\Job;
use Livewire\Component;

class KaziDetail extends Component
{
    public string $slug = '';

    public bool $showLoginModal = false;

    public ?Job $job = null;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->job = Job::query()
            ->where('slug', $slug)
            ->where(function ($query) {
                $query->where('status', 'open');
                if (auth()->check()) {
                    if (auth()->user()->isMuajili()) {
                        $query->orWhere('employer_id', auth()->id());
                    }
                    if (auth()->user()->isMfanyakazi()) {
                        $query->orWhere('hired_worker_id', auth()->id())
                              ->orWhereHas('applications', fn ($q) => $q->where('worker_id', auth()->id()));
                    }
                }
            })
            ->with(['employer', 'category', 'skills'])
            ->firstOrFail();
    }

    public function openApplyModal(): void
    {
        if (auth()->guest()) {
            $this->showLoginModal = true;

            return;
        }
        if (auth()->user()->isMfanyakazi()) {
            $this->redirect(route('winga.kazi-detail', ['slug' => $this->job->slug, 'action' => 'apply']), navigate: true);

            return;
        }
        $this->dispatch('notify', message: 'Ni wafanyakazi tu wanaoweza kuomba kazi.');
    }

    public function closeLoginModal(): void
    {
        $this->showLoginModal = false;
    }

    public function render()
    {
        return view('livewire.public.kazi-detail')
            ->layout('layouts.public')
            ->title($this->job?->title ?? 'Kazi');
    }
}
