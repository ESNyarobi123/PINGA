<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SnippeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Snippe Webhook Receiver Hit', ['payload_id' => $payload['id'] ?? 'unknown']);

        $event = $payload['type'] ?? $payload['event'] ?? '';
        $data = $payload['data'] ?? $payload;
        $status = $data['status'] ?? null;
        $reference = $data['reference'] ?? null;
        $metadata = $data['metadata'] ?? [];
        $userId = $metadata['user_id'] ?? $data['customer']['metadata']['user_id'] ?? null;
        $amount = $data['amount']['value'] ?? 0;

        // Detect subscription payments via metadata flag
        $isSubscription = ($metadata['payment_type'] ?? '') === 'subscription';
        $subscriptionId = $metadata['subscription_id'] ?? null;

        Log::info('Snippe Webhook Processing', [
            'event' => $event,
            'status' => $status,
            'reference' => $reference,
            'user_id' => $userId,
            'amount' => $amount,
            'is_subscription' => $isSubscription,
        ]);

        if (! $userId || ! $reference) {
            Log::warning('Snippe Webhook Ignored: Missing userId or reference', ['userId' => $userId, 'ref' => $reference]);

            return response()->json(['status' => 'ignored']);
        }

        $isSuccess = $event === 'payment.completed' || $status === 'completed' || $status === 'success';
        $isFailure = in_array($event, ['payment.failed', 'payment.cancelled'])
                   || in_array($status, ['failed', 'cancelled']);

        // ─── Subscription payment ────────────────────────────────────────
        if ($isSubscription) {
            return $this->handleSubscriptionPayment(
                $isSuccess, $isFailure, $userId, $reference, $subscriptionId
            );
        }

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

    private function handleSubscriptionPayment(
        bool $isSuccess,
        bool $isFailure,
        int|string $userId,
        string $reference,
        ?int $subscriptionId
    ) {
        if ($isSuccess) {
            try {
                DB::transaction(function () use ($userId, $reference, $subscriptionId) {
                    $user = User::find($userId);
                    if (! $user) {
                        Log::error("Subscription Webhook: User {$userId} not found");

                        return;
                    }

                    // Find the pending subscription record
                    $pending = $subscriptionId
                        ? Subscription::find($subscriptionId)
                        : Subscription::where('user_id', $userId)
                            ->where('payment_reference', $reference)
                            ->where('payment_status', 'pending')
                            ->latest()
                            ->first();

                    if (! $pending) {
                        Log::warning("Subscription Webhook: No pending record found for ref={$reference}");

                        return;
                    }

                    $plan = $pending->subscriptionPlan()->first()
                        ?? SubscriptionPlan::where('slug', $pending->plan_slug)->first();

                    if (! $plan) {
                        Log::error("Subscription Webhook: Plan not found for subscription {$pending->id}");

                        return;
                    }

                    // Delete the pending placeholder and activate properly
                    $pending->delete();

                    /** @var SubscriptionService $service */
                    $service = app(SubscriptionService::class);
                    $service->activate($user, $plan, $reference, 'snippe');

                    Log::info("Subscription activated via webhook: user={$userId} plan={$plan->slug}");
                });
            } catch (\Exception $e) {
                Log::error('Subscription Webhook Success Error: '.$e->getMessage());
            }
        } elseif ($isFailure) {
            try {
                $pending = $subscriptionId
                    ? Subscription::find($subscriptionId)
                    : Subscription::where('user_id', $userId)
                        ->where('payment_reference', $reference)
                        ->where('payment_status', 'pending')
                        ->latest()
                        ->first();

                if ($pending) {
                    $pending->update(['payment_status' => 'failed', 'notes' => 'Snippe payment failed/cancelled']);
                    Log::info("Subscription payment failed: user={$userId} ref={$reference}");
                }
            } catch (\Exception $e) {
                Log::error('Subscription Webhook Failure Error: '.$e->getMessage());
            }
        }

        return response()->json(['status' => 'success']);
    }
}
