<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use App\Services\OpenStreetMapGeocodingService;
use Livewire\Component;
use Livewire\WithPagination;

class KaziPending extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterCategory = '';

    public string $filterUrgency = '';

    public string $filterLocation = '';

    public string $filterFlag = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterUrgency' => ['except' => ''],
        'filterLocation' => ['except' => ''],
        'filterFlag' => ['except' => ''],
    ];

    public function mount(): void
    {
        // Auto-refresh every 30 seconds
        $this->dispatch('refresh-pending-jobs');
    }

    private function getJobsQuery()
    {
        return Job::query()
            ->where('is_approved', false)
            ->with(['employer', 'category', 'applications'])
            ->when($this->search, fn ($query) => $query
                ->where(fn ($q) => $q
                    ->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                )
            )
            ->when($this->filterCategory, fn ($query) => $query->where('category_id', $this->filterCategory))
            ->when($this->filterUrgency, fn ($query) => $query->where('urgency', $this->filterUrgency))
            ->when($this->filterLocation, fn ($query) => $query->where('location', 'like', '%'.$this->filterLocation.'%'))
            ->when($this->filterFlag, fn ($query) => match ($this->filterFlag) {
                'phone' => $query->where('description', 'regexp', '[0-9]{3}[-\s]?[0-9]{3}[-\s]?[0-9]{4}'),
                'url' => $query->where('description', 'regexp', 'https?://[^\s]+'),
                'new_user' => $query->whereHas('employer', fn ($q) => $q->where('created_at', '>', now()->subDays(7))),
                default => $query,
            })
            ->latest('created_at');
    }

    public function getJobsProperty()
    {
        return $this->getJobsQuery()->paginate(12);
    }

    public function getPendingCountProperty()
    {
        return Job::where('is_approved', false)->count();
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    public function getRegionsProperty()
    {
        return Job::distinct('location')->whereNotNull('location')->orderBy('location')->pluck('location');
    }

    public function approveJob(int $jobId): void
    {
        $job = Job::findOrFail($jobId);

        $job->update([
            'is_approved' => true,
            'status' => 'open',
            'approved_at' => now(),
        ]);

        app(OpenStreetMapGeocodingService::class)->fillJobCoordinatesIfMissing($job->fresh());

        // Log audit
        $this->logAdminAction('approve_job', $job, [
            'old' => ['is_approved' => false],
            'new' => ['is_approved' => true],
        ]);

        $this->dispatch('toast', message: "Kazi imethibitishwa: {$job->title}", type: 'success');
    }

    public function rejectJob(int $jobId, string $reason): void
    {
        $job = Job::findOrFail($jobId);

        $job->update([
            'is_approved' => false,
            'status' => 'cancelled',
            'approved_at' => now(),
        ]);

        // Log audit
        $this->logAdminAction('reject_job', $job, [
            'old' => ['is_approved' => false, 'status' => 'draft'],
            'new' => ['is_approved' => false, 'status' => 'cancelled', 'reason' => $reason],
        ]);

        $this->dispatch('toast', message: "Kazi imekataliwa: {$job->title}", type: 'error');
    }

    public function approveAll(): void
    {
        $jobs = $this->getJobsQuery()->get();
        $count = $jobs->count();

        $geocoder = app(OpenStreetMapGeocodingService::class);
        foreach ($jobs as $job) {
            $job->update([
                'is_approved' => true,
                'status' => 'open',
                'approved_at' => now(),
            ]);
            $geocoder->fillJobCoordinatesIfMissing($job->fresh());
        }

        // Log audit
        $this->logAdminAction('approve_jobs_bulk', null, [
            'count' => $count,
            'job_ids' => $jobs->pluck('id')->toArray(),
        ]);

        $this->dispatch('toast', message: "{$count} kazi zimeidhinishwa", type: 'success');
    }

    public function checkFlags(Job $job): array
    {
        $flags = [];

        // Check for phone numbers
        if (preg_match('/[0-9]{3}[-\s]?[0-9]{3}[-\s]?[0-9]{4}/', $job->description)) {
            $flags[] = ['type' => 'phone', 'message' => 'Namba ya simu imegunduliwa'];
        }

        // Check for URLs
        if (preg_match('/https?:\/\/[^\s]+/', $job->description)) {
            $flags[] = ['type' => 'url', 'message' => 'URL imegunduliwa'];
        }

        // Check for new user
        if ($job->employer && $job->employer->created_at->gt(now()->subDays(7))) {
            $flags[] = ['type' => 'new_user', 'message' => 'Mtumiaji mpya'];
        }

        return $flags;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterCategory = '';
        $this->filterUrgency = '';
        $this->filterLocation = '';
        $this->filterFlag = '';
        $this->resetPage();
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        \App\Models\AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.kazi-pending', [
            'jobs' => $this->jobs,
            'pendingCount' => $this->pendingCount,
            'categories' => $this->categories,
            'regions' => $this->regions,
        ])
            ->layout('layouts.admin')
            ->title('Kazi Zinasubiri Idhini');
    }
}
