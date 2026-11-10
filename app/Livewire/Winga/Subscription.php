<?php

namespace App\Livewire\Winga;

use App\Exceptions\SubscriptionPurchaseNotAllowedException;
use App\Models\SubscriptionPlan;
use App\Services\SnippePaymentService;
use App\Services\SubscriptionLimitsService;
use App\Services\SubscriptionService;
use Livewire\Component;

class Subscription extends Component
{
    public string $paymentMethod = 'wallet'; // wallet | mobile | card

    public string $phone = '';

    public bool $showConfirm = false;

    public ?int $selectedPlanId = null;

    public bool $loading = false;

    public function mount(): void
    {
        $this->phone = auth()->user()->phone ?? '';
    }

    public function selectPlan(int $planId): void
    {
        $this->selectedPlanId = $planId;
        $this->showConfirm = true;
    }

    public function cancelConfirm(): void
    {
        $this->showConfirm = false;
        $this->selectedPlanId = null;
    }

    public function pay(): void
    {
        $user = auth()->user();
        $plan = SubscriptionPlan::active()->find($this->selectedPlanId);

        if (! $plan) {
            $this->dispatch('toast', message: 'Mpango haukupatikana.', type: 'error');

            return;
        }

        $this->loading = true;

        try {
            match ($this->paymentMethod) {
                'wallet' => $this->payViaWallet($user, $plan),
                'mobile' => $this->payViaMobile($user, $plan),
                'card' => $this->payViaCard($user, $plan),
                default => $this->dispatch('toast', message: 'Njia ya malipo si sahihi.', type: 'error'),
            };
        } catch (SubscriptionPurchaseNotAllowedException $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        } finally {
            $this->loading = false;
        }
    }

    private function payViaWallet($user, SubscriptionPlan $plan): void
    {
        if ($user->wallet_balance < $plan->price) {
            $needed = number_format($plan->price - $user->wallet_balance);
            $this->dispatch('toast',
                message: "Salio halitoshi. Unahitaji TZS {$needed} zaidi. Tumia Mobile Money au Kadi kulipa subscription.",
                type: 'error',
            );

            return;
        }

        /** @var SubscriptionService $service */
        $service = app(SubscriptionService::class);
        $service->payFromWallet($user, $plan);

        $this->showConfirm = false;
        $this->selectedPlanId = null;

        $this->dispatch('toast',
            message: "🎉 Umefanikiwa! Umejiunga na mpango wa {$plan->name}. Sasa unaonekana kwenye Winga Bora!",
            type: 'success',
        );
    }

    private function payViaMobile($user, SubscriptionPlan $plan): void
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (empty($phone)) {
            $this->dispatch('toast', message: 'Ingiza namba ya simu.', type: 'error');

            return;
        }

        $orderId = 'sub-'.$user->id.'-'.$plan->slug.'-'.now()->timestamp;

        /** @var SubscriptionService $service */
        $service = app(SubscriptionService::class);
        $pending = $service->createPending($user, $plan, $orderId);

        /** @var SnippePaymentService $snippe */
        $snippe = app(SnippePaymentService::class);

        $nameParts = explode(' ', $user->name, 2);
        $result = $snippe->createMobilePayment(
            amount: $plan->price,
            phoneNumber: $phone,
            customerData: [
                'firstname' => $nameParts[0],
                'lastname' => $nameParts[1] ?? $nameParts[0],
                'email' => $user->email ?? 'noemail@winga.com',
                'user_id' => $user->id,
                'payment_type' => 'subscription',
                'subscription_id' => $pending->id,
            ],
            orderId: $orderId,
        );

        if ($result && (isset($result['id']) || isset($result['data']['reference']) || ($result['status'] ?? null) === 'success')) {
            $providerReference = $result['data']['reference'] ?? $result['reference'] ?? null;
            $this->rememberProviderReference($pending, $orderId, $providerReference);

            $this->showConfirm = false;
            $this->selectedPlanId = null;
            $this->dispatch('toast',
                message: 'Ombi la malipo limetumwa. Subiri USSD push kwenye simu yako. Subscription itawashwa mara malipo yanapothibitishwa.',
                type: 'info',
            );
        } elseif (SnippePaymentService::isUnknownOutcome($result ?? [])) {
            // Timeout: keep the row pending so a late webhook can still activate it.
            $pending->update([
                'notes' => 'order_id='.$orderId.' | timeout: awaiting provider confirmation',
            ]);

            $this->showConfirm = false;
            $this->selectedPlanId = null;
            $this->dispatch('toast',
                message: 'Mtoa huduma hakujibu kwa muda. Ombi linaweza kuwa limetumwa — subiri USSD kwenye simu yako. Usijaribu tena mara moja.',
                type: 'info',
            );
        } else {
            $pending->update([
                'payment_status' => 'failed',
                'notes' => 'Snippe mobile payment request failed: '.($result['message'] ?? 'unknown error'),
            ]);
            $this->dispatch('toast', message: 'Malipo hayakufanikiwa. Jaribu tena au tumia njia nyingine.', type: 'error');
        }
    }

    private function payViaCard($user, SubscriptionPlan $plan): void
    {
        $orderId = 'sub-card-'.$user->id.'-'.$plan->slug.'-'.now()->timestamp;

        /** @var SubscriptionService $service */
        $service = app(SubscriptionService::class);
        $pending = $service->createPending($user, $plan, $orderId);

        /** @var SnippePaymentService $snippe */
        $snippe = app(SnippePaymentService::class);

        $nameParts = explode(' ', $user->name, 2);
        $result = $snippe->createCardPayment(
            amount: $plan->price,
            customerData: [
                'firstname' => $nameParts[0],
                'lastname' => $nameParts[1] ?? $nameParts[0],
                'email' => $user->email ?? 'noemail@winga.com',
                'user_id' => $user->id,
                'payment_type' => 'subscription',
                'subscription_id' => $pending->id,
            ],
            orderId: $orderId,
        );

        if ($result && (isset($result['data']['authorization_url']) || isset($result['data']['payment_url']))) {
            $providerReference = $result['data']['reference'] ?? $result['reference'] ?? null;
            $this->rememberProviderReference($pending, $orderId, $providerReference);

            $this->redirect($result['data']['authorization_url'] ?? $result['data']['payment_url'], navigate: false);
        } else {
            $pending->update(['payment_status' => 'failed', 'notes' => 'Snippe card payment request failed']);
            $this->dispatch('toast', message: 'Malipo ya kadi hayakufanikiwa. Jaribu tena.', type: 'error');
        }
    }

    /**
     * Persist Snippe's payment reference. Falls back to notes when the
     * provider_reference column has not been migrated yet (avoids hard crash).
     */
    private function rememberProviderReference($pending, string $orderId, ?string $providerReference): void
    {
        $notes = 'order_id='.$orderId;

        if ($providerReference) {
            $notes .= '|provider_reference='.$providerReference;
        }

        $payload = ['notes' => $notes];

        if ($providerReference && \Illuminate\Support\Facades\Schema::hasColumn('subscriptions', 'provider_reference')) {
            $payload['provider_reference'] = $providerReference;
        }

        $pending->update($payload);
    }

    public function render()
    {
        $user = auth()->user();
        $plans = SubscriptionPlan::active()->get();
        $activeSub = app(SubscriptionService::class)->getActivePlan($user);
        $planUi = app(SubscriptionLimitsService::class)->getPlanDisplayData($user);

        return view('livewire.winga.subscription', [
            'plans' => $plans,
            'activeSub' => $activeSub,
            'planUi' => $planUi,
            'user' => $user,
        ])
            ->layout('layouts.winga')
            ->title('Subscription — Winga Bora');
    }
}
