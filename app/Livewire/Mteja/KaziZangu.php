<?php

namespace App\Livewire\Mteja;

use App\Models\Job;
use App\Notifications\WingaNotification;
use Livewire\Component;
use Livewire\WithPagination;

class KaziZangu extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public array $generatedCodes = [];

    public function generateCode(int $jobId): void
    {
        $job = Job::where('employer_id', auth()->id())
            ->where('id', $jobId)
            ->first();

        if (! $job) {
            return;
        }

        $code = $job->generateCompletionCode();
        $this->generatedCodes[$jobId] = $code;

        $this->dispatch('toast', message: 'Msimbo umetengenezwa kikamilifu!', type: 'success');
    }

    public function holdCode(int $jobId): void
    {
        $job = Job::where('employer_id', auth()->id())
            ->where('id', $jobId)
            ->where('status', 'in_progress')
            ->with('hiredWorker:id,name')
            ->first();

        if (! $job) {
            return;
        }

        $job->holdCode(3);

        if ($job->hiredWorker) {
            $job->hiredWorker->notify(new WingaNotification(
                title: '⏸️ Malipo Yameshikiliwa Muda',
                message: 'Muajili ameweka kazi "'.$job->title.'" kwenye tathmini kwa masaa 3. Wasiliana nao ili utatue tatizo kabla ya muda haujaisha.',
                icon: 'clock',
                color: 'amber',
                action_url: route('messages'),
                action_label: 'Fungua Chat',
            ));
        }

        $this->dispatch('toast', message: 'Kazi imewekwa kwenye tathmini kwa masaa 3. Winga amearifiwa.', type: 'warning');
    }

    public function render()
    {
        $query = Job::where('employer_id', auth()->id())
            ->withCount('applications')
            ->latest();

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        return view('livewire.mteja.kazi-zangu', [
            'jobs' => $query->paginate(10),
        ])->layout('layouts.mteja')
            ->title('Kazi Zangu');
    }
}
