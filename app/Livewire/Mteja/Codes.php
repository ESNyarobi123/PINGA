<?php

namespace App\Livewire\Mteja;

use App\Models\Job;
use App\Notifications\WingaNotification;
use Livewire\Component;
use Livewire\WithPagination;

class Codes extends Component
{
    use WithPagination;

    public array $generatedCodes = [];

    public string $holdComment = '';

    public ?int $extendingJobId = null;

    public function generateCode(int $jobId): void
    {
        $job = Job::where('employer_id', auth()->id())
            ->where('id', $jobId)
            ->where('status', 'in_progress')
            ->first();

        if (! $job) {
            return;
        }

        $code = $job->generateCompletionCode();
        $this->generatedCodes[$jobId] = $code;

        // Notify worker that code has been generated
        if ($job->hiredWorker) {
            $job->hiredWorker->notify(new WingaNotification(
                title: '🔑 Code Imekusanywa!',
                message: 'Muajili ametengeneza code ya kukamilisha kazi "' . $job->title . '". Ingiza code ukiwa kwenye page ya Weka Code.',
                icon: 'key',
                color: 'green',
                action_url: route('winga.weka-code'),
                action_label: 'Weka Code Sasa'
            ));
        }

        $this->dispatch('toast', message: 'Msimbo umetengenezwa kikamilifu! Mfanyakazi amearifiwa.', type: 'success');
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
                action_label: 'Fungua Chat'
            ));
        }

        $this->dispatch('toast', message: 'Kazi imewekwa kwenye tathmini kwa masaa 3. Winga amearifiwa.', type: 'warning');
    }

    public function openExtendForm(int $jobId): void
    {
        $this->extendingJobId = $jobId;
        $this->holdComment = '';
    }

    public function closeExtendForm(): void
    {
        $this->extendingJobId = null;
        $this->holdComment = '';
    }

    public function extendHold(): void
    {
        if (! $this->extendingJobId) {
            return;
        }

        $this->validate([
            'holdComment' => 'required|string|min:10|max:500',
        ], [
            'holdComment.required' => 'Tafadhali andika sababu ya kuongeza muda.',
            'holdComment.min' => 'Sababu lazima iwe na angalau herufi 10.',
        ]);

        $job = Job::where('employer_id', auth()->id())
            ->where('id', $this->extendingJobId)
            ->where('status', 'in_progress')
            ->with('hiredWorker:id,name')
            ->first();

        if (! $job) {
            return;
        }

        if (! $job->extendHold($this->holdComment)) {
            $this->dispatch('toast', message: 'Huwezi kuongeza muda zaidi ya mara moja.', type: 'error');
            return;
        }

        if ($job->hiredWorker) {
            $job->hiredWorker->notify(new WingaNotification(
                title: '⏸️ Muda wa Tathmini Umeongezwa',
                message: 'Muajili ameongeza muda wa tathmini kwa masaa 3 zaidi kwa kazi "'.$job->title.'". Sababu: '.$this->holdComment,
                icon: 'clock',
                color: 'amber',
                action_url: route('messages'),
                action_label: 'Fungua Chat'
            ));
        }

        $this->extendingJobId = null;
        $this->holdComment = '';
        $this->dispatch('toast', message: 'Muda umeongezwa kwa masaa 3 zaidi. Winga amearifiwa.', type: 'warning');
    }

    public function render()
    {
        $jobs = Job::where('employer_id', auth()->id())
            ->where('status', 'in_progress')
            ->whereNotNull('hired_worker_id')
            ->with(['hiredWorker:id,name,phone', 'payment'])
            ->latest()
            ->paginate(10);

        return view('livewire.mteja.codes', [
            'jobs' => $jobs,
        ])->layout('layouts.mteja')
            ->title('Codes za Kazi');
    }
}
