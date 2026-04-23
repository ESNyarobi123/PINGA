<?php

namespace App\Livewire\Mteja;

use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\Transaction;
use App\Notifications\WingaNotification;
use App\Support\ServicePackageSchema;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HudumaMalipo extends Component
{
    public bool $showPaymentModal = false;

    public ?int $pendingServiceRequestId = null;

    public string $paymentMethod = 'wallet';

    public ?float $paymentAmount = null;

    public ?float $platformFeeAmount = null;

    /** Agreed service / package price (same role as worker bid on jobs). */
    public ?float $servicePriceAmount = null;

    public function openPaymentModal(int $serviceRequestId): void
    {
        $user = auth()->user();
        if (! $user || ! $user->isMteja()) {
            return;
        }

        $req = ServiceRequest::query()
            ->where('id', $serviceRequestId)
            ->where('client_id', $user->id)
            ->where('status', 'accepted')
            ->with(['service', 'package', 'payment'])
            ->first();

        if (! $req || $req->payment) {
            $this->dispatch('toast', message: __('messages.huduma_malipo.pay_invalid'), type: 'error');

            return;
        }

        $workerBid = $req->agreedAmount();
        if ($workerBid <= 0) {
            $this->dispatch('toast', message: __('messages.huduma_malipo.invalid_amount'), type: 'error');

            return;
        }

        $fees = Payment::calculateFromWorkerBid($workerBid);
        $this->pendingServiceRequestId = $serviceRequestId;
        $this->servicePriceAmount = $workerBid;
        $this->platformFeeAmount = $fees['platform_fee'];
        $this->paymentAmount = $fees['amount'];
        $this->paymentMethod = 'wallet';
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->pendingServiceRequestId = null;
        $this->paymentAmount = null;
        $this->platformFeeAmount = null;
        $this->servicePriceAmount = null;
        $this->paymentMethod = 'wallet';
    }

    public function confirmPayment(): void
    {
        if (! $this->pendingServiceRequestId) {
            return;
        }

        $user = auth()->user();
        if (! $user || ! $user->isMteja()) {
            $this->closePaymentModal();

            return;
        }

        $req = ServiceRequest::query()
            ->where('id', $this->pendingServiceRequestId)
            ->where('client_id', $user->id)
            ->where('status', 'accepted')
            ->with(['service', 'package', 'payment'])
            ->first();

        if (! $req || $req->payment) {
            $this->closePaymentModal();

            return;
        }

        $workerBid = $req->agreedAmount();
        $fees = Payment::calculateFromWorkerBid($workerBid);
        $totalAmount = $fees['amount'];

        if ($this->paymentMethod === 'mobile' || $this->paymentMethod === 'card') {
            $this->closePaymentModal();
            session()->flash('deposit_amount', $totalAmount);
            session()->flash('deposit_reason', __('messages.huduma_malipo.deposit_reason', ['title' => $req->service->title]));
            $this->redirect(route('mteja.wallet'));

            return;
        }

        if ($user->wallet_balance < $totalAmount) {
            $needed = $totalAmount - $user->wallet_balance;
            $this->closePaymentModal();
            $this->dispatch('toast',
                message: __('messages.huduma_malipo.insufficient', [
                    'have' => number_format($user->wallet_balance),
                    'need' => number_format($needed),
                ]),
                type: 'error'
            );
            $this->dispatch('show-error',
                title: __('messages.huduma_malipo.insufficient_title'),
                message: __('messages.huduma_malipo.insufficient_detail', ['need' => number_format($needed)]),
                action_url: route('mteja.wallet'),
                action_label: __('messages.huduma_malipo.wallet_cta')
            );

            return;
        }

        $this->processWalletEscrow($req, $user, $workerBid, $fees, $totalAmount);
        $this->closePaymentModal();
    }

    /**
     * @param  array{amount: float, platform_fee: float, worker_amount: float}  $fees
     */
    private function processWalletEscrow(ServiceRequest $req, \App\Models\User $user, float $workerBid, array $fees, float $totalAmount): void
    {
        $wingaId = $req->wingaUserId();

        DB::transaction(function () use ($user, $req, $fees, $totalAmount, $wingaId) {
            $user->decrement('wallet_balance', $totalAmount);

            $payment = Payment::create(array_merge($fees, [
                'job_id' => null,
                'service_request_id' => $req->id,
                'employer_id' => $user->id,
                'worker_id' => $wingaId,
                'status' => 'escrowed',
                'payment_method' => 'wallet',
            ]));

            Transaction::create([
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'type' => 'debit',
                'amount' => $totalAmount,
                'description' => __('messages.huduma_malipo.tx_escrow', ['title' => $req->service->title]),
                'balance_after' => $user->fresh()->wallet_balance,
                'status' => 'completed',
            ]);

            $req->update(['status' => 'in_progress']);
        });

        $req->refresh();
        $winga = $req->service->user;
        if ($winga) {
            $winga->notify(new WingaNotification(
                title: __('messages.huduma_malipo.notify_winga_title'),
                message: __('messages.huduma_malipo.notify_winga_body', [
                    'client' => $user->name,
                    'title' => $req->service->title,
                    'amount' => number_format($totalAmount),
                ]),
                icon: 'currency-dollar',
                color: 'green',
                action_url: route('winga.weka-code'),
                action_label: __('messages.huduma_malipo.notify_winga_action'),
            ));
        }

        $this->dispatch('toast', message: __('messages.huduma_malipo.paid', ['amount' => number_format($totalAmount)]), type: 'success');
    }

    public function render()
    {
        $user = auth()->user();

        $with = ['service.category', 'service.user:id,name,phone', 'payment'];
        if (ServicePackageSchema::hasPackagesTable()) {
            $with[] = 'package';
        }

        $awaitingPayment = ServiceRequest::query()
            ->where('client_id', $user->id)
            ->where('status', 'accepted')
            ->whereDoesntHave('payment')
            ->with($with)
            ->latest()
            ->get();

        $inProgress = ServiceRequest::query()
            ->where('client_id', $user->id)
            ->where('status', 'in_progress')
            ->with($with)
            ->latest()
            ->get();

        return view('livewire.mteja.huduma-malipo', [
            'awaitingPayment' => $awaitingPayment,
            'inProgress' => $inProgress,
            'usesServicePackages' => ServicePackageSchema::hasPackagesTable(),
        ])
            ->layout('layouts.mteja')
            ->title(__('messages.huduma_malipo.title'));
    }
}
