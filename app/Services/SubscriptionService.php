<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\WingaNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    /**
     * Activate a subscription for a user after successful payment.
     * Called by webhook handler or wallet payment path.
     */
    public function activate(
        User $user,
        SubscriptionPlan $plan,
        string $paymentReference,
        string $paymentMethod = 'wallet'
    ): Subscription {
        return DB::transaction(function () use ($user, $plan, $paymentReference, $paymentMethod) {
            // Expire any currently active subscriptions first
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            $now = now();

            $subscription = Subscription::create([
                'user_id'              => $user->id,
                'subscription_plan_id' => $plan->id,
                'plan'                 => 'basic', // keep old enum valid
                'plan_slug'            => $plan->slug,
                'amount_paid'          => $plan->price,
                'starts_at'            => $now,
                'expires_at'           => $now->copy()->addDays($plan->duration_days),
                'status'               => 'active',
                'payment_status'       => 'completed',
                'payment_reference'    => $paymentReference,
                'payment_method'       => $paymentMethod,
            ]);

            $user->notify(new WingaNotification(
                title: '⭐ Subscription Imewashwa!',
                message: "Umejiunga na mpango wa {$plan->name}. Sasa unaonekana kwenye orodha ya Winga Bora hadi " . $subscription->expires_at->format('d M Y') . '.',
                icon: 'star',
                color: 'green',
                action_url: route('winga.subscription'),
                action_label: 'Angalia Subscription',
            ));

            Log::info("Subscription activated: user={$user->id} plan={$plan->slug} ref={$paymentReference}");

            return $subscription;
        });
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
        return Subscription::create([
            'user_id'              => $user->id,
            'subscription_plan_id' => $plan->id,
            'plan'                 => 'basic',
            'plan_slug'            => $plan->slug,
            'amount_paid'          => $plan->price,
            'starts_at'            => null,
            'expires_at'           => null,
            'status'               => 'cancelled', // will be set to active by webhook
            'payment_status'       => 'pending',
            'payment_reference'    => $paymentReference,
            'payment_method'       => 'snippe',
        ]);
    }

    /**
     * Pay from wallet and immediately activate.
     */
    public function payFromWallet(User $user, SubscriptionPlan $plan): Subscription
    {
        return DB::transaction(function () use ($user, $plan) {
            $reference = 'wallet-sub-' . $user->id . '-' . now()->timestamp;

            // Deduct from wallet
            $user->decrement('wallet_balance', $plan->price);

            // Create debit transaction
            Transaction::create([
                'user_id'      => $user->id,
                'type'         => 'debit',
                'amount'       => $plan->price,
                'description'  => "Subscription ya Winga - {$plan->name}",
                'balance_after' => $user->fresh()->wallet_balance,
                'reference'    => $reference,
                'status'       => 'completed',
            ]);

            return $this->activate($user, $plan, $reference, 'wallet');
        });
    }

    /**
     * Get the current active subscription for a user (with plan).
     */
    public function getActivePlan(User $user): ?Subscription
    {
        return $user->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest('starts_at')
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
