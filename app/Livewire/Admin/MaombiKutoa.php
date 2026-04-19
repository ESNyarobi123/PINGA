<?php

namespace App\Livewire\Admin;

use App\Jobs\RetryFailedPayout;
use App\Models\Payment;
use App\Models\WithdrawalRequest;
use App\Models\User;
use App\Models\AdminAuditLog;
use App\Notifications\WingaNotification;
use App\Services\SnippePayoutService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class MaombiKutoa extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterPayoutStatus = '';
    public string $filterNetwork = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    // Bulk actions
    public array $selectedRequests = [];
    public bool $selectAll = false;
    public string $bulkAction = '';

    // Individual actions
    public string $rejectionReason = '';
    public ?int $selectedRequestId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterPayoutStatus' => ['except' => ''],
        'filterNetwork' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedRequests = $this->getWithdrawalRequestsQuery()->pluck('id')->toArray();
        } else {
            $this->selectedRequests = [];
        }
    }

    public function updatedSelectedRequests(): void
    {
        $this->selectAll = false;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    private function getWithdrawalRequestsQuery()
    {
        return WithdrawalRequest::query()
            ->with(['user:id,name,phone,whatsapp,avatar,wallet_balance'])
            ->when($this->search, fn($query) => $query
                ->where(function ($q) {
                    $q->whereHas('user', fn($sub) => $sub
                        ->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                    )
                    ->orWhere('account_number', 'like', '%' . $this->search . '%')
                    ->orWhere('payout_reference', 'like', '%' . $this->search . '%');
                })
            )
            ->when($this->filterStatus, fn($query) => $query->where('status', $this->filterStatus))
            ->when($this->filterPayoutStatus, fn($query) => $query->where('payout_status', $this->filterPayoutStatus))
            ->when($this->filterNetwork, fn($query) => $query->where('network', $this->filterNetwork))
            ->when($this->dateFrom, fn($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy($this->sortBy, $this->sortDirection);
    }

    public function getWithdrawalRequestsProperty()
    {
        return $this->getWithdrawalRequestsQuery()->paginate(25);
    }

    public function getTotalRequestsProperty(): int
    {
        return WithdrawalRequest::count();
    }

    public function getPendingRequestsProperty(): int
    {
        return WithdrawalRequest::where('status', 'pending')->count();
    }

    public function getProcessingRequestsProperty(): int
    {
        return WithdrawalRequest::where('payout_status', 'processing')->count();
    }

    public function getFailedRequestsProperty(): int
    {
        return WithdrawalRequest::where('payout_status', 'failed')->count();
    }

    public function getTotalAmountProperty(): float
    {
        return WithdrawalRequest::sum('amount');
    }

    public function getPendingAmountProperty(): float
    {
        return WithdrawalRequest::where('status', 'pending')->sum('amount');
    }

    public function getFailedPayoutsProperty(): int
    {
        return Payment::where('payout_status', 'failed')->count();
    }

    public function retryWithdrawal(int $id): void
    {
        $req = WithdrawalRequest::with('user')->findOrFail($id);
        $snippe = app(SnippePayoutService::class);

        $result = $snippe->sendPayout([
            'amount' => (int) $req->amount,
            'phone' => $req->account_number,
            'name' => $req->user?->name ?? 'Mfanyakazi',
            'network' => $req->network ?? $snippe->detectNetwork($req->account_number ?? ''),
            'narration' => 'Retry: Kutoa pesa - Winga Platform',
            'idempotency_key' => 'admin-retry-' . $id . '-' . now()->timestamp,
            'metadata' => [
                'type' => 'withdrawal',
                'user_id' => $req->user_id,
                'withdrawal_id' => $req->id,
            ],
        ]);

        if ($result['success']) {
            $req->update([
                'payout_reference' => $result['reference'],
                'payout_status' => 'processing',
                'status' => 'pending',
                'retry_count' => ($req->retry_count ?? 0) + 1,
                'last_retry_at' => now(),
            ]);

            $this->logAdminAction('retry_withdrawal', $req, [
                'reference' => $result['reference'],
                'retry_count' => $req->retry_count,
            ]);

            $this->dispatch('toast', message: 'Payout retry initiated successfully', type: 'success');
        } else {
            Log::error("Admin retryWithdrawal {$id} failed", $result);
            $this->dispatch('toast', message: 'Retry failed: ' . ($result['error'] ?? 'Unknown error'), type: 'error');
        }
    }

    public function retryJobPayout(int $paymentId): void
    {
        $payment = Payment::with(['worker', 'job'])->findOrFail($paymentId);
        $snippe = app(SnippePayoutService::class);

        $result = $snippe->sendPayout([
            'amount' => (int) $payment->worker_amount,
            'phone' => $payment->worker->phone,
            'name' => $payment->worker->name,
            'narration' => 'Retry Admin: Malipo ya kazi #' . $payment->job_id,
            'idempotency_key' => 'admin-retry-pay-' . $paymentId . '-' . now()->timestamp,
            'metadata' => [
                'type' => 'payout',
                'job_id' => $payment->job_id,
                'payment_id' => $paymentId,
                'worker_id' => $payment->worker_id,
            ],
        ]);

        if ($result['success']) {
            $payment->update([
                'payout_reference' => $result['reference'], 
                'payout_status' => 'processing',
                'retry_count' => ($payment->retry_count ?? 0) + 1,
                'last_retry_at' => now(),
            ]);

            $this->logAdminAction('retry_job_payout', $payment, [
                'reference' => $result['reference'],
                'job_id' => $payment->job_id,
                'retry_count' => $payment->retry_count,
            ]);

            $this->dispatch('toast', message: 'Job payout retry initiated', type: 'success');
        } else {
            $this->dispatch('toast', message: 'Retry failed: ' . ($result['error'] ?? 'Unknown error'), type: 'error');
        }
    }

    public function approveWithdrawal(int $id): void
    {
        $req = WithdrawalRequest::with('user')->findOrFail($id);
        
        $req->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        $this->logAdminAction('approve_withdrawal', $req, [
            'amount' => $req->amount,
        ]);

        // Automatically process the approved withdrawal
        $this->retryWithdrawal($id);

        $this->dispatch('toast', message: 'Withdrawal approved and processing', type: 'success');
    }

    public function rejectWithdrawal(int $id, string $reason = ''): void
    {
        $req = WithdrawalRequest::with('user')->findOrFail($id);

        // Return funds to user wallet
        $req->user->increment('wallet_balance', $req->amount);
        
        // Create refund transaction record for audit trail
        \App\Models\Transaction::create([
            'user_id' => $req->user_id,
            'type' => 'credit',
            'amount' => $req->amount,
            'description' => 'Refund: Withdrawal rejected by admin - ' . ($reason ?: 'No reason provided'),
            'balance_after' => $req->user->fresh()->wallet_balance,
            'reference' => 'admin-reject-' . $req->id . '-' . now()->timestamp,
            'status' => 'completed',
        ]);
        
        $req->update([
            'status' => 'rejected',
            'payout_status' => 'failed',
            'admin_note' => $reason ?: 'Request rejected by admin',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        // Notify user
        $req->user->notify(new WingaNotification(
            title: 'Withdrawal Request Rejected',
            message: 'Your withdrawal request for TZS ' . number_format($req->amount) . ' has been rejected. Amount returned to wallet.',
            icon: 'x-circle',
            color: 'red',
            action_url: route('winga.tomba-ombi'),
            action_label: 'View Wallet',
        ));

        $this->logAdminAction('reject_withdrawal', $req, [
            'amount' => $req->amount,
            'reason' => $reason,
        ]);

        $this->dispatch('toast', message: 'Withdrawal rejected and funds returned', type: 'error');
    }

    public function markAsCompleted(int $id): void
    {
        $req = WithdrawalRequest::findOrFail($id);
        
        $req->update([
            'status' => 'paid',
            'payout_status' => 'completed',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        $this->logAdminAction('complete_withdrawal', $req, [
            'amount' => $req->amount,
            'reference' => $req->payout_reference,
        ]);

        $this->dispatch('toast', message: 'Withdrawal marked as completed', type: 'success');
    }

    public function executeBulkAction(): void
    {
        $this->validate([
            'bulkAction' => 'required|in:approve,reject,retry',
            'selectedRequests' => 'required|array|min:1',
        ]);

        $requests = WithdrawalRequest::whereIn('id', $this->selectedRequests)->get();
        $count = 0;

        match ($this->bulkAction) {
            'approve' => $requests->each(fn($req) => $this->approveWithdrawal($req->id)),
            'reject' => $requests->each(fn($req) => $this->rejectWithdrawal($req->id, 'Bulk rejection')),
            'retry' => $requests->each(fn($req) => $this->retryWithdrawal($req->id)),
        };

        $this->dispatch('toast', message: "Action executed on {$requests->count()} requests", type: 'success');
        $this->reset(['selectedRequests', 'selectAll', 'bulkAction']);
    }

    public function exportWithdrawals(): void
    {
        $requests = WithdrawalRequest::with(['user'])->get();
        
        $csv = "ID,User Name,Phone,Amount,Network,Status,Payout Status,Reference,Created At,Processed At\n";
        
        foreach ($requests as $req) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $req->id,
                str_replace(',', '', $req->user->name),
                $req->account_number,
                $req->amount,
                $req->network ?? '',
                $req->status,
                $req->payout_status,
                $req->payout_reference ?? '',
                $req->created_at->format('Y-m-d H:i'),
                $req->processed_at?->format('Y-m-d H:i') ?? ''
            );
        }

        $this->dispatch('download', data: $csv, filename: 'withdrawal_requests_export.csv');
    }

    public function getWithdrawalStats(WithdrawalRequest $request): array
    {
        return [
            'user_wallet_balance' => $request->user->wallet_balance ?? 0,
            'total_user_withdrawals' => $request->user->withdrawalRequests()->sum('amount'),
            'user_withdrawal_count' => $request->user->withdrawalRequests()->count(),
            'retry_count' => $request->retry_count ?? 0,
            'last_retry_at' => $request->last_retry_at?->diffForHumans(),
            'processing_time' => $request->processed_at ? $request->created_at->diffInMinutes($request->processed_at) : null,
        ];
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        AdminAuditLog::create([
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
        return view('livewire.admin.maombi-kutoa', [
            'requests' => $this->withdrawalRequests,
            'totalRequests' => $this->totalRequests,
            'pendingRequests' => $this->pendingRequests,
            'processingRequests' => $this->processingRequests,
            'failedRequests' => $this->failedRequests,
            'totalAmount' => $this->totalAmount,
            'pendingAmount' => $this->pendingAmount,
            'failedPayouts' => $this->failedPayouts,
        ])
            ->layout('layouts.admin')
            ->title('Withdrawal Requests Management');
    }
}
