<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Services\SettingsService;
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
            ->with(['employer', 'worker', 'job'])
            ->when($this->search, fn ($query) => $query
                ->where(function ($q) {
                    $q->where('payment_reference', 'like', '%'.$this->search.'%')
                        ->orWhereHas('employer', fn ($sub) => $sub->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('worker', fn ($sub) => $sub->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('job', fn ($sub) => $sub->where('title', 'like', '%'.$this->search.'%'));
                })
            )
            ->when($this->filterType, fn ($query) => $query->where('status', $this->filterType))
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
    public function getTotalRevenueProperty(): float
    {
        return Payment::where('status', 'released')->sum('platform_fee');
    }

    public function getMonthlyRevenueProperty(): float
    {
        return Payment::where('status', 'released')
            ->whereMonth('created_at', now()->month)
            ->sum('platform_fee');
    }

    public function getTodayRevenueProperty(): float
    {
        return Payment::where('status', 'released')
            ->whereDate('created_at', now()->today())
            ->sum('platform_fee');
    }

    public function getYesterdayRevenueProperty(): float
    {
        return Payment::where('status', 'released')
            ->whereDate('created_at', now()->subDay())
            ->sum('platform_fee');
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
        $payment = Payment::findOrFail($paymentId);

        if ($payment->status !== 'escrowed') {
            $this->dispatch('toast', message: 'Invalid escrow payment', type: 'error');

            return;
        }

        $platformFee = $payment->amount * (SettingsService::commissionRate() / 100);
        $workerAmount = $payment->amount - $platformFee;

        // Mark escrow as released
        $payment->update(['status' => 'released']);

        $this->logAdminAction('release_escrow', $payment, [
            'amount' => $payment->worker_amount,
        ]);

        $this->dispatch('toast', message: 'Escrow released successfully', type: 'success');
    }

    public function refundEscrow(int $paymentId): void
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->status !== 'escrowed') {
            $this->dispatch('toast', message: 'Invalid escrow payment', type: 'error');

            return;
        }

        // Mark escrow as refunded
        $payment->update(['status' => 'refunded']);

        $this->logAdminAction('refund_escrow', $payment, [
            'amount' => $payment->amount,
        ]);

        $this->dispatch('toast', message: 'Escrow refunded successfully', type: 'success');
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

        $withdrawal->update(['status' => 'rejected']);

        // Refund amount to user's wallet
        if ($withdrawal->user) {
            $withdrawal->user->increment('wallet_balance', $withdrawal->amount);
        }

        $this->logAdminAction('cancel_withdrawal', $withdrawal, [
            'amount' => $withdrawal->amount,
        ]);

        $this->dispatch('toast', message: 'Withdrawal cancelled', type: 'success');
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

    public function exportTransactions(): void
    {
        $transactions = $this->getTransactionsQuery()->get();

        $csv = "Ref ID,Status,User,Amount,Platform Fee,Payment Method,Date\n";

        foreach ($transactions as $transaction) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $transaction->payment_reference ?? 'N/A',
                $transaction->status,
                $transaction->employer?->name ?? 'N/A',
                $transaction->amount,
                $transaction->platform_fee,
                $transaction->payment_method ?? 'N/A',
                $transaction->created_at->format('Y-m-d H:i')
            );
        }

        $this->dispatch('download', data: $csv, filename: 'transactions_export.csv');
    }

    /**
     * Sum subscription payments (amount_paid) for the current calendar month by plan slug.
     */
    public function subscriptionMonthlyTotalByPlan(string $planSlug): float
    {
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
