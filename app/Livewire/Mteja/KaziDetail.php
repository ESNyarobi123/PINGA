<?php

namespace App\Livewire\Mteja;

use App\Models\Job;
use Livewire\Component;

class KaziDetail extends Component
{
    public Job $job;

    public function mount($id)
    {
        $this->job = Job::where('id', $id)
            ->where('employer_id', auth()->id())
            ->with(['category', 'skills', 'applications.worker', 'hiredWorker', 'payment'])
            ->withCount('applications')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.mteja.kazi-detail')
            ->layout('layouts.mteja')
            ->title('Maelezo: ' . $this->job->title);
    }
}
