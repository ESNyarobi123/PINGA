<?php

namespace App\Livewire\Admin;

use App\Models\Dispute;
use App\Models\Job;
use App\Models\User;
use App\Models\Payment;
use App\Services\SettingsService;
use Livewire\Component;
use Livewire\WithPagination;

class Migogoro extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterPriority = '';
    public string $filterStatus = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $amountMin = '';
    public string $amountMax = '';

    // Sorting
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterPriority' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
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

    private function getDisputesQuery()
    {
        return Dispute::query()
            ->with(['job.employer', 'job.hiredWorker', 'job.payment', 'evidence'])
            ->when($this->search, fn($query) => $query
                ->where(fn($q) => $q
                    ->where('reason', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('job', fn($jq) => $jq->where('title', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('job.employer', fn($uq) => $uq->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('job.hiredWorker', fn($uq) => $uq->where('name', 'like', '%' . $this->search . '%'))
                )
            )
            ->when($this->filterPriority, fn($query) => $query->where('priority', $this->filterPriority))
            ->when($this->filterStatus, fn($query) => $query->where('status', $this->filterStatus))
            ->when($this->dateFrom, fn($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->amountMin, fn($query) => $query->whereHas('job.payment', fn($pq) => $pq->where('amount', '>=', $this->amountMin)))
            ->when($this->amountMax, fn($query) => $query->whereHas('job.payment', fn($pq) => $pq->where('amount', '<=', $this->amountMax)))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function getDisputesProperty()
    {
        return $this->getDisputesQuery()->paginate(25);
    }

    public function getOpenCountProperty(): int
    {
        return Dispute::where('status', 'open')->count();
    }

    public function getEscrowAmount(Dispute $dispute): float
    {
        $payment = $dispute->job?->payment;

        return ($payment && $payment->status === 'escrowed') ? (float) $payment->amount : 0;
    }

    public function getDaysOpen(Dispute $dispute): int
    {
        return $dispute->created_at->diffInDays(now());
    }

    public function getAutoResolveAt(Dispute $dispute): ?string
    {
        if (!$dispute->auto_resolve_at) {
            return null;
        }
        
        return $dispute->auto_resolve_at->diffForHumans();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterPriority = '';
        $this->filterStatus = '';
        $this->amountMin = '';
        $this->amountMax = '';
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.migogoro', [
            'disputes' => $this->disputes,
            'openCount' => $this->openCount,
        ])
            ->layout('layouts.admin')
            ->title('Dispute Resolution Center');
    }
}
