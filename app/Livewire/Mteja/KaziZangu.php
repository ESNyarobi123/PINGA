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

    // Edit modal state
    public bool $showEditModal = false;

    public ?int $editingJobId = null;

    public string $editTitle = '';

    public string $editDescription = '';

    public string $editCategoryId = '';

    public string $editLocation = '';

    public string $editBudgetType = 'fixed';

    public int $editBudgetMin = 0;

    public int $editBudgetMax = 0;

    public string $editUrgency = 'normal';

    public string $editDuration = '';

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

    public function editJob(int $jobId): void
    {
        $job = Job::where('employer_id', auth()->id())
            ->where('id', $jobId)
            ->whereIn('status', ['open', 'draft'])
            ->first();

        if (! $job) {
            $this->dispatch('toast', message: 'Huwezi kuhariri kazi hii.', type: 'error');

            return;
        }

        $this->editingJobId = $job->id;
        $this->editTitle = $job->title;
        $this->editDescription = $job->description ?? '';
        $this->editCategoryId = $job->category_id ?? '';
        $this->editLocation = $job->location ?? '';
        $this->editBudgetType = $job->budget_type ?? 'fixed';
        $this->editBudgetMin = (int) $job->budget_min;
        $this->editBudgetMax = (int) $job->budget_max;
        $this->editUrgency = $job->urgency ?? 'normal';
        $this->editDuration = $job->duration ?? '';
        $this->showEditModal = true;
    }

    public function updateJob(): void
    {
        $this->validate([
            'editTitle' => 'required|string|min:5|max:200',
            'editDescription' => 'required|string|min:20',
            'editCategoryId' => 'required',
            'editLocation' => 'required|string',
            'editBudgetMin' => 'required|integer|min:1000',
        ], [
            'editTitle.required' => 'Jina la kazi linahitajika',
            'editDescription.required' => 'Maelezo ya kazi yanahitajika',
            'editCategoryId.required' => 'Chagua kategoria',
            'editLocation.required' => 'Eneo linahitajika',
            'editBudgetMin.required' => 'Bajeti ya chini inahitajika',
        ]);

        if (Job::containsPhoneNumber($this->editDescription) || Job::containsPhoneNumber($this->editTitle)) {
            $this->addError('editDescription', 'Tafadhali usiandike namba ya simu kwenye maelezo.');

            return;
        }

        $job = Job::where('employer_id', auth()->id())
            ->where('id', $this->editingJobId)
            ->whereIn('status', ['open', 'draft'])
            ->first();

        if (! $job) {
            $this->dispatch('toast', message: 'Kazi haipatikani au haiwezi kuhaririwa.', type: 'error');

            return;
        }

        $job->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'category_id' => $this->editCategoryId,
            'location' => $this->editLocation,
            'budget_type' => $this->editBudgetType,
            'budget_min' => $this->editBudgetMin,
            'budget_max' => $this->editBudgetMax ?: $this->editBudgetMin,
            'urgency' => $this->editUrgency,
            'duration' => $this->editDuration,
            'is_approved' => false, // Needs re-approval after edit
        ]);

        \App\Jobs\TranslateJobPosting::dispatch($job);

        $this->showEditModal = false;
        $this->reset(['editingJobId', 'editTitle', 'editDescription', 'editCategoryId', 'editLocation', 'editBudgetType', 'editBudgetMin', 'editBudgetMax', 'editUrgency', 'editDuration']);
        $this->dispatch('toast', message: 'Kazi imehaririwa! Inasubiri ukaguzi wa admin tena.', type: 'success');
    }

    public function deleteJob(int $jobId): void
    {
        $job = Job::where('employer_id', auth()->id())
            ->where('id', $jobId)
            ->whereIn('status', ['open', 'draft'])
            ->first();

        if (! $job) {
            $this->dispatch('toast', message: 'Huwezi kufuta kazi hii.', type: 'error');

            return;
        }

        $job->delete();
        $this->dispatch('toast', message: 'Kazi imefutwa kikamilifu!', type: 'success');
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editingJobId', 'editTitle', 'editDescription', 'editCategoryId', 'editLocation', 'editBudgetType', 'editBudgetMin', 'editBudgetMax', 'editUrgency', 'editDuration']);
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
            'categories' => \App\Models\Category::orderBy('name')->get(),
        ])->layout('layouts.mteja')
            ->title('Kazi Zangu');
    }
}
