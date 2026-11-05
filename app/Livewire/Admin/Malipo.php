<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Notifications\WingaNotification;
use App\Services\SettingsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithPagination;

class Malipo extends Component
{
    use WithPagination;

    public string $activeTab = 'transactions';

    public string $search = '';

    public string $filterType = '';

    public string $filterStatus = '';

    public string $filterMethod = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $amountMin = '';

    public string $amountMax = '';

    // Settings
    public string $commissionRate = '';

    public string $minWithdrawal = '';

    public string $maxWithdrawalDaily = '';

    public string $minDeposit = '';

    public string $autoReleaseDays = '';

    public string $payoutDelayHours = '';

    public array $subscriptionPrices = [
        'msingi' => '',
        'kawaida' => '',
        'bora' => '',
    ];

    protected $queryString = [
        'activeTab' => ['except' => 'transactions'],
        'search' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterMethod' => ['except' => ''],
    ];

    private const TABS = ['transactions', 'escrow', 'withdrawals', 'subscriptions', 'settings'];

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadSettings();

        if (! in_array($this->activeTab, self::TABS, true)) {
            $this->activeTab = 'transactions';
        }
    }

    public function setActiveTab(string $tab): void
    {
        if (! in_array($tab, self::TABS, true)) {
            return;
        }

        $this->activeTab = $tab;
        $this->resetPage();
    }

    private function loadSettings(): void
    {
        $this->commissionRate = SettingsService::get('payment.commission_rate', '10');
        $this->minWithdrawal = SettingsService::get('payment.min_withdrawal', '5000');
        $this->maxWithdrawalDaily = SettingsService::get('payment.max_withdrawal_daily', '1000000');
        $this->minDeposit = SettingsService::get('payment.min_deposit', '1000');
        $this->autoReleaseDays = SettingsService::get('payment.auto_release_days', '7');
        $this->payoutDelayHours = SettingsService::get('payment.payout_delay_hours', '24');

        $this->subscriptionPrices = [
            'msingi' => SettingsService::get('subscription.msingi_price', '15000'),
            'kawaida' => SettingsService::get('subscription.kawaida_price', '45000'),
            'bora' => SettingsService::get('subscription.bora_price', '120000'),
        ];
    }

    public function saveSettings(): void
    {
        try {
            SettingsService::set('payment.commission_rate', $this->commissionRate);
            SettingsService::set('payment.min_withdrawal', $this->minWithdrawal);
            SettingsService::set('payment.max_withdrawal_daily', $this->maxWithdrawalDaily);
            SettingsService::set('payment.min_deposit', $this->minDeposit);
            SettingsService::set('payment.auto_release_days', $this->autoReleaseDays);
            SettingsService::set('payment.payout_delay_hours', $this->payoutDelayHours);

            SettingsService::set('subscription.msingi_price', $this->subscriptionPrices['msingi'] ?? '');
            SettingsService::set('subscription.kawaida_price', $this->subscriptionPrices['kawaida'] ?? '');
            SettingsService::set('subscription.bora_price', $this->subscriptionPrices['bora'] ?? '');

            $this->logAdminAction('update_payment_settings', null, [
                'new' => [
                    'commission_rate' => $this->commissionRate,
                    'min_withdrawal' => $this->minWithdrawal,
                    'subscription_prices' => $this->subscriptionPrices,
                ],
            ]);

            $this->loadSettings();

            $this->dispatch('toast', message: __('messages.admin_malipo.settings_saved'), type: 'success');
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: __('messages.admin_malipo.settings_save_failed'), type: 'error');
        }
    }

    public function testSnippeConnection(): void
    {
        $key = (string) config('services.snippe.key', '');
        $baseUrl = rtrim((string) config('services.snippe.url', 'https://api.snippe.sh'), '/');

        if ($key === '') {
            $this->dispatch('toast', message: __('messages.admin_malipo.snippe_no_key'), type: 'error');

            return;
        }

        try {
            $response = Http::timeout(12)
                ->withHeaders([
                    'Authorization' => 'Bearer '.$key,
                    'Accept' => 'application/json',
                ])
                ->get($baseUrl.'/v1/payments', ['limit' => 1]);

            if ($response->successful()) {
                $this->dispatch('toast', message: __('messages.admin_malipo.snippe_ok'), type: 'success');

                return;
            }

            if ($response->status() === 401 || $response->status() === 403) {
                $this->dispatch('toast', message: __('messages.admin_malipo.snippe_auth_failed'), type: 'error');

                return;
            }

            $this->dispatch('toast', message: __('messages.admin_malipo.snippe_http', ['status' => $response->status()]), type: 'warning');
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: __('messages.admin_malipo.snippe_unreachable'), type: 'error');
        }
    }

    private function getTransactionsQuery()
    {
        return Payment::query()
            ->with(['employer', 'worker', 'job', 'serviceRequest.service'])
            ->when($this->search, fn ($query) => $query
                ->where(function ($q) {
                    $q->where('payment_reference', 'like', '%'.$this->search.'%')
                        ->orWhereHas('employer', fn ($sub) => $sub->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('worker', fn ($sub) => $sub->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('job', fn ($sub) => $sub->where('title', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('serviceRequest.service', fn ($sub) => $sub->where('title', 'like', '%'.$this->search.'%'));
                })
            )
            ->when($this->filterType === 'job', fn ($query) => $query->whereNotNull('job_id'))
            ->when($this->filterType === 'service', fn ($query) => $query->whereNotNull('service_request_id'))
            ->when($this->filterType && ! in_array($this->filterType, ['job', 'service']), fn ($query) => $query->where('status', $this->filterType))
            ->when($this->filterStatus, fn ($query) => $query->where('status', $this->filterStatus))
            ->when($this->filterMethod, fn ($query) => $query->where('payment_method', $this->filterMethod))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->amountMin, fn ($query) => $query->where('amount', '>=', $this->amountMin))
            ->when($this->amountMax, fn ($query) => $query->where('amount', '<=', $this->amountMax))
            ->latest('created_at');
    }

    private function getEscrowQuery()
    {
        return Payment::query()
            ->where('status', 'escrowed')
            ->with(['employer', 'worker', 'job.employer', 'job.hiredWorker'])
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->latest('created_at');
    }

    private function getWithdrawalsQuery()
    {
        return WithdrawalRequest::query()
            ->with(['user'])
            ->when($this->search, fn ($query) => $query
                ->where(function ($q) {
                    $q->where('reference', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', fn ($sub) => $sub->where('name', 'like', '%'.$this->search.'%'));
                })
            )
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->latest('created_at');
    }

    private function getSubscriptionsQuery()
    {
        return Subscription::query()
            ->with(['user', 'subscriptionPlan'])
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->latest('created_at');
    }

    public function getTransactionsProperty()
    {
        return $this->getTransactionsQuery()->paginate(25);
    }

    public function getEscrowsProperty()
    {
        return $this->getEscrowQuery()->paginate(25);
    }

    public function getWithdrawalsProperty()
    {
        return $this->getWithdrawalsQuery()->paginate(25);
    }

    public function getSubscriptionsProperty()
    {
        return $this->getSubscriptionsQuery()->paginate(25);
    }

    // Stats
    /**
     * Platform commission from released jobs/services plus 100% of subscription payments.
     */
    public function getTotalRevenueProperty(): float
    {
        return $this->commissionRevenue() + $this->subscriptionRevenue();
    }

    public function getCommissionRevenueProperty(): float
    {
        return $this->commissionRevenue();
    }

    public function getSubscriptionRevenueProperty(): float
    {
        return $this->subscriptionRevenue();
    }

    public function getMonthlyRevenueProperty(): float
    {
        $startOfMonth = now()->startOfMonth();

        return Payment::where('status', 'released')
            ->where('escrow_released_at', '>=', $startOfMonth)
            ->sum('platform_fee')
            + $this->subscriptionRevenueBetween($startOfMonth, now());
    }

    public function getTodayRevenueProperty(): float
    {
        return Payment::where('status', 'released')
            ->whereDate('escrow_released_at', today())
            ->sum('platform_fee')
            + $this->subscriptionRevenueBetween(today()->startOfDay(), today()->endOfDay());
    }

    public function getYesterdayRevenueProperty(): float
    {
        $yesterday = now()->subDay()->startOfDay();

        return Payment::where('status', 'released')
            ->whereDate('escrow_released_at', $yesterday)
            ->sum('platform_fee')
            + $this->subscriptionRevenueBetween($yesterday, $yesterday->copy()->endOfDay());
    }

    private function commissionRevenue(): float
    {
        return (float) Payment::where('status', 'released')->sum('platform_fee');
    }

    private function subscriptionRevenue(): float
    {
        $ledger = (float) SubscriptionPayment::query()->sum('amount');

        if ($ledger > 0) {
            return $ledger;
        }

        return (float) Subscription::query()
            ->whereIn('status', ['active', 'expired'])
            ->where('payment_status', 'completed')
            ->sum('amount_paid');
    }

    private function subscriptionRevenueBetween(\Carbon\CarbonInterface $from, \Carbon\CarbonInterface $to): float
    {
        $ledger = (float) SubscriptionPayment::query()
            ->whereBetween('paid_at', [$from, $to])
            ->sum('amount');

        if ($ledger > 0) {
            return $ledger;
        }

        // Legacy fallback: subscriptions without ledger rows.
        return (float) Subscription::query()
            ->whereIn('status', ['active', 'expired'])
            ->where('payment_status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->sum('amount_paid');
    }

    public function getCurrentEscrowBalanceProperty(): float
    {
        return Payment::where('status', 'escrowed')
            ->sum('amount');
    }

    public function getTotalPaidToWorkersProperty(): float
    {
        return Payment::where('status', 'released')
            ->sum('worker_amount');
    }

    public function getTotalRefundsProperty(): float
    {
        return Payment::where('status', 'refunded')
            ->sum('amount');
    }

    public function getFailedWithdrawalsCountProperty(): int
    {
        return WithdrawalRequest::where('payout_status', 'failed')->count();
    }

    public function getFailedWithdrawalsAmountProperty(): float
    {
        return WithdrawalRequest::where('payout_status', 'failed')->sum('amount');
    }

    // Actions
    public function releaseEscrow(int $paymentId): void
    {
        $payment = Payment::with(['job', 'serviceRequest.service', 'worker'])->findOrFail($paymentId);

        if ($payment->status !== 'escrowed') {
            $this->dispatch('toast', message: 'Invalid escrow payment', type: 'error');

            return;
        }

        $fees = Payment::calculateFromWorkerBid((float) $payment->amount);
        $released = DB::transaction(function () use ($payment, $fees) {
            $locked = Payment::query()->lockForUpdate()->find($payment->id);

            if (! $locked || $locked->status !== 'escrowed') {
                return false;
            }

            $locked->update([
                'platform_fee' => $fees['platform_fee'],
                'worker_amount' => $fees['worker_amount'],
                'status' => 'released',
                'payout_status' => 'completed',
                'escrow_released_at' => now(),
            ]);

            $worker = $locked->worker;
            $label = ($locked->job_id ? 'Malipo ya kazi: ' : 'Malipo ya huduma: ').$locked->escrowItemLabel();

            $alreadyCredited = $worker && Transaction::query()
                ->where('payment_id', $locked->id)
                ->where('type', 'credit')
                ->exists();

            if ($worker && ! $alreadyCredited) {
                $worker->increment('wallet_balance', (float) $fees['worker_amount']);

                Transaction::create([
                    'user_id' => $worker->id,
                    'payment_id' => $locked->id,
                    'type' => 'credit',
                    'amount' => (float) $fees['worker_amount'],
                    'description' => $label,
                    'balance_after' => $worker->fresh()->wallet_balance,
                    'status' => 'completed',
                ]);
            }

            return true;
        });

        if (! $released) {
            $this->dispatch('toast', message: 'Invalid escrow payment', type: 'error');

            return;
        }

        $this->logAdminAction('release_escrow', $payment, [
            'amount' => $fees['worker_amount'],
        ]);

        $this->dispatch('toast', message: 'Escrow released successfully', type: 'success');
    }

    public function refundEscrow(int $paymentId): void
    {
        $payment = Payment::with(['job', 'serviceRequest.service', 'employer'])->findOrFail($paymentId);

        if ($payment->status !== 'escrowed') {
            $this->dispatch('toast', message: 'Invalid escrow payment', type: 'error');

            return;
        }

        $refunded = DB::transaction(function () use ($payment) {
            $locked = Payment::query()->lockForUpdate()->find($payment->id);

            if (! $locked || $locked->status !== 'escrowed') {
                return null;
            }

            $amount = $this->creditEscrowRefund($locked);

            $locked->update([
                'status' => 'refunded',
                'payout_status' => 'refunded',
            ]);

            if ($locked->job && $locked->job->status === 'in_progress') {
                $locked->job->update(['status' => 'open']);
            }

            if ($locked->serviceRequest && $locked->serviceRequest->status === 'in_progress') {
                $locked->serviceRequest->update(['status' => 'accepted']);
            }

            return $amount;
        });

        if ($refunded === null) {
            $this->dispatch('toast', message: 'Invalid escrow payment', type: 'error');

            return;
        }

        $employer = $payment->employer;
        if ($employer) {
            $employer->notify(new WingaNotification(
                title: 'Pesa imerudishwa kwenye wallet',
                message: 'TZS '.number_format($refunded).' ya escrow imerudishwa kwenye salio lako.',
                icon: 'banknotes',
                color: 'green',
                action_url: route('mteja.wallet'),
                action_label: 'Angalia Wallet',
            ));
        }

        $this->logAdminAction('refund_escrow', $payment, [
            'amount' => $refunded,
        ]);

        $this->dispatch('toast', message: 'Pesa imerudishwa kwa mteja', type: 'success');
    }

    /**
     * Return the escrowed amount to the client who paid. Skips if a credit for this payment already exists.
     */
    public function creditEscrowRefund(Payment $payment): float
    {
        $employer = $payment->employer ?? User::query()->find($payment->employer_id);
        $amount = (float) $payment->amount;

        $debit = Transaction::query()
            ->where('payment_id', $payment->id)
            ->where('type', 'debit')
            ->first();

        if ($debit) {
            $amount = (float) $debit->amount;
        }

        $alreadyCredited = Transaction::query()
            ->where('payment_id', $payment->id)
            ->where('type', 'credit')
            ->where('user_id', $payment->employer_id)
            ->exists();

        if ($employer && ! $alreadyCredited && $amount > 0) {
            $employer->increment('wallet_balance', $amount);

            Transaction::create([
                'user_id' => $employer->id,
                'payment_id' => $payment->id,
                'type' => 'credit',
                'amount' => $amount,
                'description' => 'Rudisho la escrow: '.$payment->escrowItemLabel(),
                'balance_after' => $employer->fresh()->wallet_balance,
                'status' => 'completed',
            ]);
        }

        return $amount;
    }

    public function retryWithdrawal(int $withdrawalId): void
    {
        $withdrawal = WithdrawalRequest::findOrFail($withdrawalId);

        $withdrawal->update(['status' => 'pending']);

        $this->logAdminAction('retry_withdrawal', $withdrawal, [
            'amount' => $withdrawal->amount,
        ]);

        $this->dispatch('toast', message: 'Withdrawal retry initiated', type: 'success');
    }

    public function cancelWithdrawal(int $withdrawalId): void
    {
        $withdrawal = WithdrawalRequest::findOrFail($withdrawalId);

        if ($withdrawal->status === 'rejected') {
            $this->dispatch('toast', message: 'Withdrawal already rejected', type: 'warning');

            return;
        }

        $refundAmount = (float) $withdrawal->amount + (float) ($withdrawal->charge_amount ?? 0);

        // Refund amount to user's wallet
        if ($withdrawal->user) {
            $withdrawal->user->increment('wallet_balance', $refundAmount);

            \App\Models\Transaction::where('user_id', $withdrawal->user_id)
                ->where(function ($q) use ($withdrawal) {
                    $q->where('reference', 'WD-'.$withdrawal->id)
                        ->orWhere(function ($sub) use ($withdrawal) {
                            $sub->where('type', 'withdrawal')
                                ->where('status', 'processing')
                                ->where('amount', $withdrawal->amount);
                        });
                })
                ->update(['status' => 'failed']);

            \App\Models\Transaction::create([
                'user_id' => $withdrawal->user_id,
                'type' => 'credit',
                'amount' => $refundAmount,
                'description' => 'Refund: Ombi la kutoa pesa limeghairiwa (#'.$withdrawal->id.')',
                'balance_after' => $withdrawal->user->fresh()->wallet_balance,
                'reference' => 'admin-reject-'.$withdrawal->id.'-'.now()->timestamp,
                'status' => 'completed',
            ]);
        }

        $withdrawal->update([
            'status' => 'rejected',
            'payout_status' => 'failed',
            'processed_at' => now(),
        ]);

        $this->logAdminAction('cancel_withdrawal', $withdrawal, [
            'amount' => $withdrawal->amount,
        ]);

        $this->dispatch('toast', message: 'Withdrawal cancelled and refunded', type: 'success');
    }

    public function markWithdrawalPaid(int $withdrawalId): void
    {
        $withdrawal = WithdrawalRequest::findOrFail($withdrawalId);

        $withdrawal->update(['status' => 'paid']);

        $this->logAdminAction('mark_withdrawal_paid', $withdrawal, [
            'amount' => $withdrawal->amount,
        ]);

        $this->dispatch('toast', message: 'Withdrawal marked as paid', type: 'success');
    }

    public function exportTransactions()
    {
        $transactions = $this->getTransactionsQuery()->get();

        $this->logAdminAction('export_transactions', null, [
            'count' => $transactions->count(),
        ]);

        $filename = 'transactions_export_'.now()->format('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel displays non-ASCII characters properly
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Ref ID',
                'Type',
                'Client / Employer',
                'Worker / Winga',
                'Job / Service Title',
                'Amount (TZS)',
                'Platform Fee (TZS)',
                'Worker Amount (TZS)',
                'Status',
                'Payment Method',
                'Date',
            ]);

            foreach ($transactions as $transaction) {
                $title = $transaction->job?->title ?? ($transaction->serviceRequest?->service?->title ?? 'N/A');
                fputcsv($handle, [
                    $transaction->payment_reference ?: ('PAY-'.$transaction->id),
                    $transaction->service_request_id ? 'Service' : 'Job',
                    $transaction->employer?->name ?? 'N/A',
                    $transaction->worker?->name ?? 'N/A',
                    $title,
                    $transaction->amount,
                    $transaction->platform_fee,
                    $transaction->worker_amount,
                    $transaction->status,
                    $transaction->payment_method ?? 'N/A',
                    $transaction->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * Sum subscription payments (amount_paid) for the current calendar month by plan slug.
     */
    public function subscriptionMonthlyTotalByPlan(string $planSlug): float
    {
        $ledgerTotal = (float) SubscriptionPayment::query()
            ->where('plan_slug', $planSlug)
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('amount');

        if ($ledgerTotal > 0) {
            return $ledgerTotal;
        }

        // Legacy rows that predate the payment ledger.
        return (float) Subscription::query()
            ->where(function ($q) use ($planSlug) {
                $q->whereHas('subscriptionPlan', fn ($q2) => $q2->where('slug', $planSlug))
                    ->orWhere('plan_slug', $planSlug);
            })
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount_paid');
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        \App\Models\AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $changes['old'] ?? $changes['old_values'] ?? null,
            'new_values' => $changes['new'] ?? $changes['new_values'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.malipo', [
            'transactions' => $this->transactions,
            'escrows' => $this->escrows,
            'withdrawals' => $this->withdrawals,
            'subscriptions' => $this->subscriptions,
            'stats' => [
                'totalRevenue' => $this->totalRevenue,
                'monthlyRevenue' => $this->monthlyRevenue,
                'todayRevenue' => $this->todayRevenue,
                'yesterdayRevenue' => $this->yesterdayRevenue,
                'currentEscrowBalance' => $this->currentEscrowBalance,
                'totalPaidToWorkers' => $this->totalPaidToWorkers,
                'totalRefunds' => $this->totalRefunds,
                'failedWithdrawalsCount' => $this->failedWithdrawalsCount,
                'failedWithdrawalsAmount' => $this->failedWithdrawalsAmount,
            ],
        ])
            ->layout('layouts.admin')
            ->title('Financial Control Center');
    }
}
