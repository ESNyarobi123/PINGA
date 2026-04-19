<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Job;
use App\Services\OpenStreetMapGeocodingService;
use Livewire\Component;
use Livewire\WithPagination;

class Kazi extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterApproval = '';

    public string $filterHold = '';

    public string $filterStatus = '';

    public string $filterCategory = '';

    public string $filterLocation = '';

    public string $filterDispute = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $budgetMin = '';

    public string $budgetMax = '';

    // Sorting
    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    // Bulk actions
    public array $selectedJobs = [];

    public bool $selectAll = false;

    public string $bulkAction = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterApproval' => ['except' => ''],
        'filterHold' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterLocation' => ['except' => ''],
        'filterDispute' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedJobs = $this->getJobsQuery()->pluck('id')->toArray();
        } else {
            $this->selectedJobs = [];
        }
    }

    public function updatedSelectedJobs(): void
    {
        $this->selectAll = false;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    private function getJobsQuery()
    {
        return Job::query()
            ->with([
                'employer',
                'category',
                'hiredWorker',
                'applications',
                'payment',
            ])
            ->when($this->search, fn ($query) => $query
                ->where(function ($q) {
                    $q->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%');
                })
            )
            ->when($this->filterApproval, fn ($query) => match ($this->filterApproval) {
                'pending' => $query->where('is_approved', false),
                'approved' => $query->where('is_approved', true),
                default => $query,
            })
            ->when($this->filterHold, fn ($query) => match ($this->filterHold) {
                'active' => $query->whereNotNull('code_hold_until'),
                'none' => $query->whereNull('code_hold_until'),
                default => $query,
            })
            ->when($this->filterStatus, fn ($query) => $query->where('status', $this->filterStatus))
            ->when($this->filterCategory, fn ($query) => $query->where('category_id', $this->filterCategory))
            ->when($this->filterLocation, fn ($query) => $query->where('location', $this->filterLocation))
            ->when($this->filterDispute, fn ($query) => match ($this->filterDispute) {
                'yes' => $query->where('status', 'disputed'),
                'no' => $query->where('status', '!=', 'disputed'),
                default => $query,
            })
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->budgetMin, fn ($query) => $query->where('budget_min', '>=', $this->budgetMin))
            ->when($this->budgetMax, fn ($query) => $query->where('budget_max', '<=', $this->budgetMax))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function getJobsProperty()
    {
        return $this->getJobsQuery()->paginate(25);
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    public function getRegionsProperty()
    {
        return Job::distinct('location')->whereNotNull('location')->orderBy('location')->pluck('location');
    }

    public function getPendingCountProperty()
    {
        return Job::where('is_approved', false)->count();
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

        $this->logAdminAction('approve_job', $job, [
            'old' => ['is_approved' => false],
            'new' => ['is_approved' => true],
        ]);

        $this->dispatch('toast', message: "Kazi imethibitishwa: {$job->title}", type: 'success');
    }

    public function rejectJob(int $jobId, string $reason = ''): void
    {
        $job = Job::findOrFail($jobId);

        $job->update([
            'status' => 'cancelled',
            'approved_at' => now(),
        ]);

        $this->logAdminAction('reject_job', $job, [
            'old' => ['is_approved' => false],
            'new' => ['status' => 'cancelled'],
        ]);

        $this->dispatch('toast', message: "Kazi imekataliwa: {$job->title}", type: 'error');
    }

    public function deleteJob(int $jobId): void
    {
        $job = Job::findOrFail($jobId);
        $title = $job->title;

        $job->delete();

        $this->logAdminAction('delete_job', $job, [
            'deleted_title' => $title,
        ]);

        $this->dispatch('toast', message: "Kazi imefutwa: {$title}", type: 'warning');
    }

    public function executeBulkAction(): void
    {
        $this->validate([
            'bulkAction' => 'required|in:approve,reject,delete,export',
            'selectedJobs' => 'required|array|min:1',
        ]);

        $jobs = Job::whereIn('id', $this->selectedJobs)->get();
        $count = 0;

        match ($this->bulkAction) {
            'approve' => $jobs->each(fn ($job) => $this->approveJob($job->id)),
            'reject' => $jobs->each(fn ($job) => $this->rejectJob($job->id, 'Bulk rejection')),
            'delete' => $jobs->each(fn ($job) => $this->deleteJob($job->id)),
            'export' => $this->exportJobs($jobs),
        };

        $this->dispatch('toast', message: "Action executed on {$jobs->count()} jobs", type: 'success');
        $this->reset(['selectedJobs', 'selectAll', 'bulkAction']);
    }

    private function exportJobs($jobs): void
    {
        $csv = "ID,Title,Client,Category,Status,Is Approved,Budget Min,Budget Max,Created,Location\n";

        foreach ($jobs as $job) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $job->id,
                str_replace(',', '', $job->title ?? ''),
                $job->employer?->name ?? 'Unknown',
                $job->category?->name ?? 'Uncategorized',
                $job->status,
                $job->is_approved ? 'Yes' : 'No',
                $job->budget_min,
                $job->budget_max,
                $job->created_at->format('Y-m-d H:i'),
                $job->location
            );
        }

        $this->dispatch('download', data: $csv, filename: 'jobs_export.csv');
    }

    public function getEscrowAmount(Job $job): float
    {
        $escrowPayment = $job->payment;

        return $escrowPayment && $escrowPayment->status === 'escrowed' ? $escrowPayment->amount : 0;
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        \App\Models\AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.kazi', [
            'jobs' => $this->jobs,
            'pendingCount' => $this->pendingCount,
            'categories' => $this->categories,
            'regions' => $this->regions,
        ])
            ->layout('layouts.admin')
            ->title('Kazi Zote');
    }
}
