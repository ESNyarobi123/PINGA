<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\SubscriptionPurchaseNotAllowedException;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\WingaNotification;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SnippeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $event = $payload['type'] ?? $payload['event'] ?? '';
        $data = is_array($payload['data'] ?? null) ? $payload['data'] : $payload;

        $status = $data['status'] ?? null;
        $reference = $data['reference'] ?? null;

        $metadata = $this->resolveMetadata($payload, $data);
        $orderId = $metadata['order_id'] ?? null;

        Log::info('Snippe Webhook Receiver Hit', [
            'payload_id' => $payload['id'] ?? 'unknown',
            'event' => $event,
            'reference' => $reference,
            'order_id' => $orderId,
        ]);

        $isSuccess = in_array($event, ['payment.completed', 'payment.success', 'payment.successful'], true)
            || in_array($status, ['completed', 'success', 'successful'], true);
        $isFailure = in_array($event, ['payment.failed', 'payment.cancelled'], true)
            || in_array($status, ['failed', 'cancelled'], true);

        $pendingSubscription = $this->findPendingSubscription($reference, $orderId);
        $isSubscription = ($metadata['payment_type'] ?? null) === 'subscription'
            || $pendingSubscription !== null
            || $this->looksLikeSubscriptionOrder($orderId)
            || $this->looksLikeSubscriptionOrder($reference);

        $userId = $metadata['user_id'] ?? $pendingSubscription?->user_id;
        $subscriptionId = isset($metadata['subscription_id']) && is_numeric($metadata['subscription_id'])
            ? (int) $metadata['subscription_id']
            : $pendingSubscription?->id;

        Log::info('Snippe Webhook Processing', [
            'event' => $event,
            'status' => $status,
            'reference' => $reference,
            'user_id' => $userId,
            'order_id' => $orderId,
            'is_subscription' => $isSubscription,
            'subscription_id' => $subscriptionId,
        ]);

        if ($isSubscription) {
            if (! $userId && ! $subscriptionId) {
                Log::warning('Snippe Webhook: subscription payment without user or subscription id', ['reference' => $reference]);

                return response()->json(['status' => 'ignored']);
            }

            return $this->handleSubscriptionPayment(
                $isSuccess,
                $isFailure,
                $userId,
                $reference,
                $orderId,
                $subscriptionId,
                $this->resolveAmount($data),
            );
        }

        if (! $userId || ! $reference) {
            Log::warning('Snippe Webhook Ignored: Missing userId or reference', ['userId' => $userId, 'ref' => $reference]);

            return response()->json(['status' => 'ignored']);
        }

        $amount = $this->resolveAmount($data);

        // ─── Regular wallet deposit ──────────────────────────────────────
        if ($isSuccess) {
            try {
                DB::transaction(function () use ($userId, $amount, $reference, $data) {
                    if (Transaction::where('reference', $reference)->exists()) {
                        Log::info('Snippe Webhook: Transaction already exists', ['ref' => $reference]);

                        return;
                    }

                    $paymentType = $data['payment_type'] ?? $data['channel']['type'] ?? 'payment';
                    $user = User::lockForUpdate()->find($userId);
                    if ($user) {
                        $user->increment('wallet_balance', $amount);
                        Transaction::create([
                            'user_id' => $user->id,
                            'type' => 'deposit',
                            'amount' => $amount,
                            'description' => 'Salio Liliongezwa ('.ucfirst($paymentType).')',
                            'balance_after' => $user->wallet_balance,
                            'reference' => $reference,
                        ]);
                        Log::info("User {$userId} credited with {$amount} via Snippe {$paymentType}");
                    } else {
                        Log::error("Snippe Webhook: User {$userId} not found");
                    }
                });
            } catch (\Exception $e) {
                Log::error('Snippe Webhook Success Branch Error: '.$e->getMessage());
            }
        } elseif ($isFailure) {
            try {
                if (! Transaction::where('reference', $reference)->exists()) {
                    $paymentType = $data['payment_type'] ?? 'payment';
                    $user = User::find($userId);
                    if ($user) {
                        $statusText = ($status === 'cancelled') ? 'Imehairishwa' : 'Imefeli';
                        Transaction::create([
                            'user_id' => $user->id,
                            'type' => 'deposit',
                            'amount' => 0,
                            'description' => "Muamala {$statusText} (".ucfirst($paymentType).')',
                            'balance_after' => $user->wallet_balance,
                            'reference' => $reference,
                        ]);
                        Log::info("Recorded failed/cancelled payment for user {$userId}: {$statusText}");
                    }
                }
            } catch (\Exception $e) {
                Log::error('Snippe Webhook Cancelled Branch Error: '.$e->getMessage());
            }
        } else {
            Log::info('Snippe Webhook: Unhandled event/status', ['event' => $event, 'status' => $status]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Snippe nests metadata in different places depending on the payment channel.
     *
     * @return array<string, mixed>
     */
    private function resolveMetadata(array $payload, array $data): array
    {
        $candidates = [
            $data['metadata'] ?? null,
            $payload['metadata'] ?? null,
            $data['customer']['metadata'] ?? null,
            $payload['customer']['metadata'] ?? null,
            $data['payment']['metadata'] ?? null,
        ];

        $metadata = [];
        foreach ($candidates as $candidate) {
            if (is_array($candidate)) {
                $metadata = array_merge($metadata, $candidate);
            }
        }

        return $metadata;
    }

    private function resolveAmount(array $data): float
    {
        $amount = $data['amount'] ?? 0;

        if (is_array($amount)) {
            return (float) ($amount['value'] ?? 0);
        }

        return (float) $amount;
    }

    private function looksLikeSubscriptionOrder(?string $reference): bool
    {
        return $reference !== null && str_starts_with($reference, 'sub-');
    }

    private function findPendingSubscription(?string $reference, ?string $orderId): ?Subscription
    {
        $references = array_values(array_filter([$reference, $orderId]));

        if ($references === []) {
            return null;
        }

        $query = Subscription::query()
            ->where('payment_status', 'pending')
            ->where(function ($q) use ($references) {
                $q->whereIn('payment_reference', $references);

                if (\Illuminate\Support\Facades\Schema::hasColumn('subscriptions', 'provider_reference')) {
                    $q->orWhereIn('provider_reference', $references);
                }
            });

        return $query->latest('id')->first();
    }

    private function handleSubscriptionPayment(
        bool $isSuccess,
        bool $isFailure,
        int|string|null $userId,
        ?string $reference,
        ?string $orderId,
        ?int $subscriptionId,
        float $amount = 0,
    ) {
        $pending = $this->resolvePendingSubscription($userId, $reference, $orderId, $subscriptionId);

        if (! $isSuccess && ! $isFailure) {
            Log::info('Subscription Webhook: Unhandled event/status', [
                'reference' => $reference,
                'order_id' => $orderId,
            ]);

            return response()->json(['status' => 'success']);
        }

        if ($pending === null) {
            Log::warning('Subscription Webhook: No pending subscription matched', [
                'user_id' => $userId,
                'reference' => $reference,
                'order_id' => $orderId,
                'subscription_id' => $subscriptionId,
            ]);

            return response()->json(['status' => 'success']);
        }

        if ($isFailure) {
            $pending->update([
                'payment_status' => 'failed',
                'notes' => 'Snippe payment failed/cancelled',
            ]);
            Log::info("Subscription payment failed: user={$pending->user_id} ref={$reference}");

            return response()->json(['status' => 'success']);
        }

        // Idempotency: this payment reference already activated a subscription.
        if ($reference && Subscription::where('payment_reference', $reference)
            ->where('payment_status', 'completed')
            ->exists()) {
            Log::info('Subscription Webhook: payment already processed', ['reference' => $reference]);

            return response()->json(['status' => 'success']);
        }

        $user = User::find($pending->user_id);
        if (! $user) {
            Log::error("Subscription Webhook: User {$pending->user_id} not found");

            return response()->json(['status' => 'success']);
        }

        $this->flagAmountMismatch($pending, $amount, $reference);

        $plan = $pending->subscriptionPlan()->first()
            ?? SubscriptionPlan::where('slug', $pending->plan_slug)->first();

        if (! $plan) {
            Log::error("Subscription Webhook: Plan not found for subscription {$pending->id}");

            return response()->json(['status' => 'success']);
        }

        $activationReference = $reference ?: (string) $pending->payment_reference;

        try {
            DB::transaction(function () use ($user, $plan, $pending, $activationReference) {
                /** @var SubscriptionService $service */
                $service = app(SubscriptionService::class);
                $service->activate($user, $plan, $activationReference, 'snippe');

                $this->markPlaceholderActivated($pending);
            });

            Log::info("Subscription activated via webhook: user={$user->id} plan={$plan->slug}");
        } catch (SubscriptionPurchaseNotAllowedException $e) {
            $this->alertAdmins(
                'Subscription imelipwa lakini haikuwashwa',
                "{$user->name} alilipa {$plan->name} (ref {$activationReference}) lakini activation imezuiwa: {$e->getMessage()}",
            );

            $pending->update(['notes' => 'Paid but activation blocked: '.$e->getMessage()]);

            Log::error('Subscription Webhook: activation blocked', [
                'user_id' => $user->id,
                'plan' => $plan->slug,
                'reference' => $activationReference,
                'error' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            Log::error('Subscription Webhook Success Error: '.$e->getMessage());

            $this->alertAdmins(
                'Subscription webhook error',
                "Malipo ya subscription (ref {$activationReference}) yameshindwa kuactivate: {$e->getMessage()}",
            );
        }

        return response()->json(['status' => 'success']);
    }

    private function resolvePendingSubscription(
        int|string|null $userId,
        ?string $reference,
        ?string $orderId,
        ?int $subscriptionId,
    ): ?Subscription {
        if ($subscriptionId) {
            $pending = Subscription::query()
                ->whereKey($subscriptionId)
                ->where('payment_status', 'pending')
                ->first();

            if ($pending) {
                return $pending;
            }
        }

        if ($pending = $this->findPendingSubscription($reference, $orderId)) {
            return $pending;
        }

        if (! $userId) {
            return null;
        }

        return Subscription::query()
            ->where('user_id', $userId)
            ->where('payment_status', 'pending')
            ->latest('id')
            ->first();
    }

    /**
     * Mark the pending placeholder as paid without dropping the order_id audit
     * trail stored in its notes. The row may already be the activated one.
     */
    /**
     * Warn admins when the provider charged a different amount than the one quoted
     * for the pending subscription (price edits between request and confirmation).
     */
    private function flagAmountMismatch(Subscription $pending, float $paidAmount, ?string $reference): void
    {
        $expected = (float) $pending->amount_paid;

        if ($paidAmount <= 0 || $expected <= 0 || abs($expected - $paidAmount) <= 1) {
            return;
        }

        Log::warning('Subscription Webhook: amount mismatch', [
            'subscription_id' => $pending->id,
            'expected' => $expected,
            'received' => $paidAmount,
            'reference' => $reference,
        ]);

        $this->alertAdmins(
            'Subscription: kiasi hakilingani',
            "Malipo ya subscription #{$pending->id} yalitarajiwa TZS ".number_format($expected)
                .' lakini Snippe imetuma TZS '.number_format($paidAmount)." (ref {$reference}).",
        );
    }

    private function markPlaceholderActivated(Subscription $pending): void
    {
        $placeholder = Subscription::find($pending->id);

        if (! $placeholder) {
            return;
        }

        $placeholder->update([
            'payment_status' => 'completed',
            'notes' => trim(($placeholder->notes ? $placeholder->notes.' | ' : '').'Activated via Snippe webhook'),
        ]);
    }

    private function alertAdmins(string $title, string $message): void
    {
        User::where('role', 'admin')->get()->each(function (User $admin) use ($title, $message) {
            $admin->notify(new WingaNotification(
                title: $title,
                message: $message,
                icon: 'exclamation-triangle',
                color: 'amber',
                action_url: route('admin.subscriptions'),
                action_label: 'Angalia Subscriptions',
            ));
        });
    }
}
