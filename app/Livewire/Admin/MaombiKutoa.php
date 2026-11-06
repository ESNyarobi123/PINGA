<?php

namespace App\Livewire\Admin;

use App\Models\AdminAuditLog;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Notifications\WingaNotification;
use App\Services\SnippePayoutService;
use App\Services\WithdrawalService;
use Illuminate\Support\Facades\DB;
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
            ->with(['user:id,name,phone,email,role,whatsapp,avatar,wallet_balance'])
            ->when($this->search, fn ($query) => $query
                ->where(function ($q) {
                    $q->whereHas('user', fn ($sub) => $sub
                        ->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                    )
                        ->orWhere('account_number', 'like', '%'.$this->search.'%')
                        ->orWhere('payout_reference', 'like', '%'.$this->search.'%');
                })
            )
            ->when($this->filterStatus, fn ($query) => $query->where('status', $this->filterStatus))
            ->when($this->filterPayoutStatus, fn ($query) => $query->where('payout_status', $this->filterPayoutStatus))
            ->when($this->filterNetwork, fn ($query) => $query->where('network', $this->filterNetwork))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
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

        $result = app(WithdrawalService::class)->retry($req);

        if ($result['success']) {
            $req->refresh();

            $this->logAdminAction('retry_withdrawal', $req, [
                'reference' => $result['reference'] ?? null,
                'retry_count' => $req->retry_count,
            ]);

            $this->dispatch('toast', message: 'Payout retry initiated successfully', type: 'success');
        } else {
            Log::error("Admin retryWithdrawal {$id} failed", $result);
            $this->dispatch('toast', message: 'Retry failed: '.($result['error'] ?? 'Unknown error'), type: 'error');
        }
    }

    public function retryJobPayout(int $paymentId): void
    {
        $payment = Payment::with(['worker', 'job'])->findOrFail($paymentId);

        if ($payment->payout_status === 'completed' || $payment->status === 'refunded') {
            $this->dispatch('toast', message: 'Malipo haya hayahitaji retry.', type: 'warning');

            return;
        }

        $snippe = app(SnippePayoutService::class);

        $result = $snippe->sendPayout([
            'amount' => (int) $payment->worker_amount,
            'phone' => $payment->worker->phone,
            'name' => $payment->worker->name,
            'narration' => 'Retry Admin: Malipo ya kazi #'.$payment->job_id,
            'idempotency_key' => 'admin-retry-pay-'.$paymentId.'-'.now()->timestamp,
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
            $this->dispatch('toast', message: 'Retry failed: '.($result['error'] ?? 'Unknown error'), type: 'error');
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

    public function confirmReject(): void
    {
        if (! $this->selectedRequestId) {
            return;
        }

        $this->rejectWithdrawal($this->selectedRequestId, $this->rejectionReason);
    }

    public function rejectWithdrawal(int $id, string $reason = ''): void
    {
        $req = WithdrawalRequest::with('user')->findOrFail($id);

        if (in_array($req->status, ['rejected', 'refunded'], true)) {
            $this->closeRejectModal();
            $this->dispatch('toast', message: 'This request has already been rejected', type: 'warning');

            return;
        }

        if ($req->status === 'paid' || $req->payout_status === 'completed') {
            $this->closeRejectModal();
            $this->dispatch('toast', message: 'Haiwezi kukataliwa — malipo yameshatumwa kwa mtumiaji.', type: 'error');

            return;
        }

        // Return funds to user wallet (including fee if employer/mteja was charged fee)
        $refundAmount = (float) $req->amount + (float) ($req->charge_amount ?? 0);

        $refunded = DB::transaction(function () use ($req, $reason, $refundAmount): bool {
            $locked = WithdrawalRequest::query()->lockForUpdate()->find($req->id);

            if (! $locked || in_array($locked->status, ['rejected', 'refunded'], true)
                || $locked->status === 'paid' || $locked->payout_status === 'completed') {
                return false;
            }

            $user = $locked->user;

            if (! $user) {
                return false;
            }

            $alreadyRefunded = Transaction::query()
                ->where('user_id', $user->id)
                ->where('type', 'credit')
                ->where('reference', 'like', 'admin-reject-'.$locked->id.'-%')
                ->exists();

            if (! $alreadyRefunded) {
                $user->increment('wallet_balance', $refundAmount);

                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'amount' => $refundAmount,
                    'description' => 'Refund: Ombi la kutoa pesa limekataliwa'.($reason ? ' - Sababu: '.$reason : ''),
                    'balance_after' => $user->fresh()->wallet_balance,
                    'reference' => 'admin-reject-'.$locked->id.'-'.now()->timestamp,
                    'status' => 'completed',
                ]);
            }

            // Mark corresponding withdrawal debit transaction as failed so it does not count in totalWithdrawn
            Transaction::query()
                ->where('user_id', $user->id)
                ->where(function ($q) use ($locked): void {
                    $q->where('reference', 'WD-'.$locked->id)
                        ->orWhere(function ($sub) use ($locked): void {
                            $sub->where('type', 'withdrawal')
                                ->where('status', 'processing')
                                ->where('amount', $locked->amount);
                        });
                })
                ->update(['status' => 'failed']);

            $locked->update([
                'status' => 'rejected',
                'payout_status' => 'failed',
                'admin_note' => $reason ?: 'Request rejected by admin',
                'processed_at' => now(),
                'processed_by' => auth()->id(),
            ]);

            return true;
        });

        if (! $refunded) {
            $this->closeRejectModal();
            $this->dispatch('toast', message: 'Ombi hili limeshashughulikiwa.', type: 'warning');

            return;
        }

        // Route appropriately based on user role
        $isClient = in_array(strtolower($req->user?->role ?? ''), ['client', 'mteja']);
        $actionUrl = $isClient ? route('mteja.wallet') : route('winga.tomba-ombi');

        // Notify user with reason included
        $message = 'Ombi lako la kutoa TZS '.number_format($req->amount).' limekataliwa.';
        if ($reason) {
            $message .= ' Sababu: '.$reason.'.';
        }
        $message .= ' Salio la TZS '.number_format($refundAmount).' limerudishwa kwenye wallet yako.';

        $req->user->notify(new WingaNotification(
            title: 'Ombi la Kutoa Pesa Limekataliwa',
            message: $message,
            icon: 'x-circle',
            color: 'red',
            action_url: $actionUrl,
            action_label: 'Angalia Wallet',
        ));

        $this->logAdminAction('reject_withdrawal', $req, [
            'amount' => $req->amount,
            'reason' => $reason,
        ]);

        $this->closeRejectModal();
        $this->dispatch('toast', message: 'Withdrawal rejected and funds returned', type: 'error');
    }

    public function closeRejectModal(): void
    {
        $this->selectedRequestId = null;
        $this->rejectionReason = '';
    }

    public function markAsCompleted(int $id): void
    {
        $req = WithdrawalRequest::findOrFail($id);

        if (in_array($req->status, ['rejected', 'refunded'], true)) {
            $this->dispatch('toast', message: 'Ombi lilikataliwa — haliwezi kuwekwa kama limekamilika.', type: 'error');

            return;
        }

        DB::transaction(function () use ($req): void {
            $req->update([
                'status' => 'paid',
                'payout_status' => 'completed',
                'processed_at' => now(),
                'processed_by' => auth()->id(),
            ]);

            Transaction::query()
                ->where('reference', 'WD-'.$req->id)
                ->where('type', 'withdrawal')
                ->where('status', 'processing')
                ->update(['status' => 'completed']);
        });

        $this->logAdminAction('complete_withdrawal', $req, [
            'amount' => $req->amount,
            'reference' => $req->payout_reference,
        ]);

        $this->dispatch('toast', message: 'Withdrawal marked as completed', type: 'success');
    }

    public function executeBulkAction()
    {
        $this->validate([
            'bulkAction' => 'required|in:approve,reject,retry,export',
            'selectedRequests' => 'required|array|min:1',
        ]);

        $requests = WithdrawalRequest::whereIn('id', $this->selectedRequests)->with('user')->get();

        if ($this->bulkAction === 'export') {
            $this->reset(['selectedRequests', 'selectAll', 'bulkAction']);

            return $this->exportRequestsList($requests, 'selected_withdrawals_');
        }

        match ($this->bulkAction) {
            'approve' => $requests->each(fn ($req) => $this->approveWithdrawal($req->id)),
            'reject' => $requests->each(fn ($req) => $this->rejectWithdrawal($req->id, 'Bulk rejection')),
            'retry' => $requests->each(fn ($req) => $this->retryWithdrawal($req->id)),
        };

        $this->dispatch('toast', message: "Action executed on {$requests->count()} requests", type: 'success');
        $this->reset(['selectedRequests', 'selectAll', 'bulkAction']);
    }

    public function exportWithdrawals()
    {
        $requests = $this->getWithdrawalRequestsQuery()->get();

        $this->logAdminAction('export_withdrawals', null, [
            'count' => $requests->count(),
        ]);

        return $this->exportRequestsList($requests, 'withdrawal_requests_');
    }

    private function exportRequestsList($requests, string $prefix = 'withdrawal_requests_')
    {
        $filename = $prefix.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($requests) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Recipient Name',
                'User Role',
                'Phone / Account Number',
                'Network / Provider',
                'Method',
                'Amount to Pay (TZS)',
                'Charge / Fee (TZS)',
                'Net Amount (TZS)',
                'Request Status',
                'Payout Status',
                'Payout Reference',
                'Bank Name',
                'Account Name',
                'Admin Note / Rejection Reason',
                'Request Date',
                'Processed Date',
            ]);

            foreach ($requests as $req) {
                $phone = $req->account_number ?: ($req->phone ?: ($req->user?->phone ?? ''));
                $recipientName = $req->account_name ?: ($req->user?->name ?? 'N/A');
                $role = ucfirst($req->user?->role ?? 'N/A');
                $network = $req->methodLabel();

                fputcsv($handle, [
                    $req->id,
                    $recipientName,
                    $role,
                    $phone,
                    $network,
                    $req->method ?? 'N/A',
                    $req->amount,
                    $req->charge_amount ?? 0,
                    $req->net_amount ?: $req->amount,
                    $req->status,
                    $req->payout_status,
                    $req->payout_reference ?? '',
                    $req->bank_name ?? '',
                    $req->account_name ?? '',
                    $req->admin_note ?? '',
                    $req->created_at?->format('Y-m-d H:i:s') ?? '',
                    $req->processed_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
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

    private function logAdminAction(string $action, $model = null, array $changes = []): void
    {
        AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? (isset($changes['old']) ? null : $changes),
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
