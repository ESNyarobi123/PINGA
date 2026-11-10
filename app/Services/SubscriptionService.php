<?php

namespace App\Services;

use App\Exceptions\SubscriptionPurchaseNotAllowedException;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\WingaNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    /**
     * Days before expiry when the same plan may be purchased again (extends {@see Subscription::$expires_at}).
     */
    private function renewalWindowDays(): int
    {
        return max(1, (int) config('subscription.renewal_days_before_expiry', 7));
    }

    public function mayRenewEarly(Subscription $active): bool
    {
        if ($active->expires_at === null) {
            return false;
        }

        $windowStart = $active->expires_at->copy()->subDays($this->renewalWindowDays());

        return now()->greaterThanOrEqualTo($windowStart);
    }

    /**
     * @throws SubscriptionPurchaseNotAllowedException
     */
    public function assertPurchaseAllowed(User $user, SubscriptionPlan $plan): void
    {
        $active = $this->getActivePlan($user);
        if (! $active) {
            return;
        }

        if ((int) $active->subscription_plan_id !== (int) $plan->id) {
            throw new SubscriptionPurchaseNotAllowedException(
                __('messages.subscription.purchase_plan_change_blocked', [
                    'date' => $active->expires_at?->format('d M Y') ?? '',
                ])
            );
        }

        if (! $this->mayRenewEarly($active)) {
            throw new SubscriptionPurchaseNotAllowedException(
                __('messages.subscription.purchase_renewal_too_early', [
                    'date' => $active->expires_at?->format('d M Y') ?? '',
                    'days' => $this->renewalWindowDays(),
                ])
            );
        }
    }

    /**
     * After external or wallet payment: extend current term if same plan in renewal window, else start/replace cycle.
     * Admin flows should use {@see activate()} with $forceReplace = true instead.
     *
     * @throws SubscriptionPurchaseNotAllowedException
     */
    public function fulfillAfterPayment(
        User $user,
        SubscriptionPlan $plan,
        string $paymentReference,
        string $paymentMethod = 'wallet'
    ): Subscription {
        $active = $this->getActivePlan($user);

        if ($active && (int) $active->subscription_plan_id !== (int) $plan->id) {
            throw new SubscriptionPurchaseNotAllowedException(
                __('messages.subscription.purchase_plan_change_blocked', [
                    'date' => $active->expires_at?->format('d M Y') ?? '',
                ])
            );
        }

        if ($active && (int) $active->subscription_plan_id === (int) $plan->id) {
            if (! $this->mayRenewEarly($active)) {
                throw new SubscriptionPurchaseNotAllowedException(
                    __('messages.subscription.purchase_renewal_too_early', [
                        'date' => $active->expires_at?->format('d M Y') ?? '',
                        'days' => $this->renewalWindowDays(),
                    ])
                );
            }

            $placeholder = $this->pendingPlaceholder($user, $plan);
            $paidAmount = (float) ($placeholder?->amount_paid ?: $plan->price);

            $subscription = $this->renewSubscription($user, $active, $plan, $paymentReference, $paymentMethod);

            // The renewal extends the existing row, so the pending placeholder
            // from the Snippe attempt is merged into it (never shown twice).
            $this->mergePlaceholder($placeholder, $subscription);

            $this->recordLedgerPayment($subscription, $user, $plan, $paymentReference, $paymentMethod, $paidAmount);

            return $subscription;
        }

        $placeholder = $paymentMethod === 'admin' ? null : $this->pendingPlaceholder($user, $plan);
        $paidAmount = (float) ($placeholder?->amount_paid ?: $plan->price);

        $subscription = $this->activateReplaceExisting($user, $plan, $paymentReference, $paymentMethod, $placeholder);
        $this->recordLedgerPayment($subscription, $user, $plan, $paymentReference, $paymentMethod, $paidAmount);

        return $subscription;
    }

    /**
     * The pending row created by {@see createPending()} for this user and plan.
     */
    private function pendingPlaceholder(User $user, SubscriptionPlan $plan): ?Subscription
    {
        return Subscription::query()
            ->where('user_id', $user->id)
            ->where('subscription_plan_id', $plan->id)
            ->where('payment_status', 'pending')
            ->latest('id')
            ->first();
    }

    /**
     * Fold a pending placeholder into the subscription row that was just renewed.
     *
     * The renewal extends the existing row and the ledger row written right after
     * this keeps the Snippe reference, so the placeholder is removed instead of
     * leaving a second "cancelled but paid" row in the admin list.
     */
    private function mergePlaceholder(?Subscription $placeholder, Subscription $subscription): void
    {
        if (! $placeholder || $placeholder->is($subscription)) {
            return;
        }

        if (SubscriptionPayment::query()->where('subscription_id', $placeholder->id)->exists()) {
            return;
        }

        Log::info('Subscription placeholder merged into renewal', [
            'placeholder_id' => $placeholder->id,
            'subscription_id' => $subscription->id,
            'order_reference' => $placeholder->payment_reference,
        ]);

        $placeholder->delete();
    }

    /**
     * Expire active rows and create a new subscription (admin override or first purchase after expiry).
     */
    public function activateReplaceExisting(
        User $user,
        SubscriptionPlan $plan,
        string $paymentReference,
        string $paymentMethod = 'wallet',
        ?Subscription $placeholder = null
    ): Subscription {
        return DB::transaction(function () use ($user, $plan, $paymentReference, $paymentMethod, $placeholder) {
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            $now = now();

            $attributes = [
                'subscription_plan_id' => $plan->id,
                'plan' => 'basic', // keep old enum valid
                'plan_slug' => $plan->slug,
                'amount_paid' => (float) ($placeholder?->amount_paid ?: $plan->price),
                'starts_at' => $now,
                'expires_at' => $now->copy()->addDays($plan->duration_days),
                'status' => 'active',
                'payment_status' => 'completed',
                'payment_reference' => $paymentReference,
                'payment_method' => $paymentMethod,
            ];

            if ($placeholder && (int) $placeholder->user_id === (int) $user->id) {
                // Promote the pending Snippe placeholder instead of leaving a
                // second, cancelled copy of the same purchase behind.
                $placeholder->update($attributes);
                $subscription = $placeholder->fresh();
            } else {
                $subscription = Subscription::create($attributes + [
                    'user_id' => $user->id,
                ]);
            }

            $user->notify(new WingaNotification(
                title: '⭐ Subscription Imewashwa!',
                message: "Umejiunga na mpango wa {$plan->name}. Sasa unaonekana kwenye orodha ya Winga Bora hadi ".$subscription->expires_at->format('d M Y').'.',
                icon: 'star',
                color: 'green',
                action_url: route('winga.subscription'),
                action_label: 'Angalia Subscription',
            ));

            Log::info("Subscription activated (new): user={$user->id} plan={$plan->slug} ref={$paymentReference}");

            return $subscription;
        });
    }

    protected function renewSubscription(
        User $user,
        Subscription $active,
        SubscriptionPlan $plan,
        string $paymentReference,
        string $paymentMethod
    ): Subscription {
        $newExpires = $active->expires_at->copy()->addDays($plan->duration_days);
        $active->update([
            'expires_at' => $newExpires,
            'amount_paid' => (float) $active->amount_paid + (float) $plan->price,
            'payment_reference' => $paymentReference,
            'payment_method' => $paymentMethod,
            'payment_status' => 'completed',
            'status' => 'active',
        ]);

        $active = $active->fresh();

        $user->notify(new WingaNotification(
            title: '✅ Subscription imeongezwa',
            message: "Muda wa mpango wa {$plan->name} umeongezwa. Unaendelea kuonekana kwenye Winga Bora hadi ".$active->expires_at->format('d M Y').'.',
            icon: 'star',
            color: 'green',
            action_url: route('winga.subscription'),
            action_label: 'Angalia Subscription',
        ));

        Log::info("Subscription renewed: user={$user->id} plan={$plan->slug} ref={$paymentReference}");

        return $active;
    }

    /**
     * @param  bool  $forceReplace  When true (admin), always expire actives and create a new row.
     *
     * @throws SubscriptionPurchaseNotAllowedException When $forceReplace is false and purchase is not allowed.
     */
    public function activate(
        User $user,
        SubscriptionPlan $plan,
        string $paymentReference,
        string $paymentMethod = 'wallet',
        bool $forceReplace = false
    ): Subscription {
        if ($forceReplace) {
            $subscription = $this->activateReplaceExisting($user, $plan, $paymentReference, $paymentMethod);
            $this->recordLedgerPayment($subscription, $user, $plan, $paymentReference, $paymentMethod);

            return $subscription;
        }

        return $this->fulfillAfterPayment($user, $plan, $paymentReference, $paymentMethod);
    }

    /**
     * Record one row per actual payment so renewals are not lost from monthly revenue.
     */
    protected function recordLedgerPayment(
        Subscription $subscription,
        User $user,
        SubscriptionPlan $plan,
        string $paymentReference,
        string $paymentMethod,
        ?float $amount = null,
    ): void {
        if (! \Illuminate\Support\Facades\Schema::hasTable('subscription_payments')) {
            return;
        }

        $attributes = [
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'plan_slug' => $plan->slug,
            'amount' => $amount ?? (float) $plan->price,
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
        ];

        SubscriptionPayment::query()->firstOrCreate(
            [
                'subscription_id' => $subscription->id,
                'payment_reference' => $paymentReference,
            ],
            $attributes
        );
    }

    /**
     * Create a pending subscription record before Snippe payment is confirmed.
     * The webhook will call activate() after success.
     */
    public function createPending(
        User $user,
        SubscriptionPlan $plan,
        string $paymentReference
    ): Subscription {
        $this->assertPurchaseAllowed($user, $plan);

        // A newer attempt replaces the older one; only one row may stay pending.
        Subscription::query()
            ->where('user_id', $user->id)
            ->where('subscription_plan_id', $plan->id)
            ->where('payment_status', 'pending')
            ->update([
                'payment_status' => 'failed',
                'notes' => 'Superseded by a newer payment attempt',
            ]);

        return Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'plan' => 'basic',
            'plan_slug' => $plan->slug,
            'amount_paid' => $plan->price,
            'starts_at' => null,
            'expires_at' => null,
            'status' => 'cancelled', // will be set to active by webhook
            'payment_status' => 'pending',
            'payment_reference' => $paymentReference,
            'payment_method' => 'snippe',
        ]);
    }

    /**
     * Pay from wallet and immediately activate.
     */
    public function payFromWallet(User $user, SubscriptionPlan $plan): Subscription
    {
        $this->assertPurchaseAllowed($user, $plan);

        return DB::transaction(function () use ($user, $plan) {
            $reference = 'wallet-sub-'.$user->id.'-'.now()->timestamp;

            // Deduct from wallet
            $user->decrement('wallet_balance', $plan->price);

            // Create debit transaction
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $plan->price,
                'description' => "Subscription ya Winga - {$plan->name}",
                'balance_after' => $user->fresh()->wallet_balance,
                'reference' => $reference,
                'status' => 'completed',
            ]);

            return $this->fulfillAfterPayment($user, $plan, $reference, 'wallet');
        });
    }

    /**
     * Get the current active subscription for a user (with plan).
     */
    public function getActivePlan(User $user): ?Subscription
    {
        return $user->subscriptions()
            ->with('subscriptionPlan')
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->orderByDesc('starts_at')
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Mark expired subscriptions. Called by scheduler.
     */
    public function expireOldSubscriptions(): int
    {
        $count = Subscription::where('status', 'active')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        Log::info("SubscriptionService: expired {$count} subscriptions");

        return $count;
    }
}
