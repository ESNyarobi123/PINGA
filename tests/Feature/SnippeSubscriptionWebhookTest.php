<?php

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;

function snippeSignedPost($test, array $payload)
{
    $secret = config('services.snippe.webhook_secret');
    $body = json_encode($payload);
    $timestamp = (string) time();
    $signature = hash_hmac('sha256', "{$timestamp}.{$body}", $secret);

    return $test->call('POST', '/api/webhooks/snippe', [], [], [], [
        'HTTP_X_WEBHOOK_SIGNATURE' => $signature,
        'HTTP_X_WEBHOOK_TIMESTAMP' => $timestamp,
        'CONTENT_TYPE' => 'application/json',
    ], $body);
}

function subscriptionPlanForTests(int $price = 5000): SubscriptionPlan
{
    return SubscriptionPlan::query()->firstOrCreate(
        ['slug' => 'winga-complex'],
        [
            'name' => 'Winga Complex',
            'name_en' => 'Winga Complex',
            'price' => $price,
            'duration_days' => 30,
            'features' => [],
            'is_active' => true,
            'sort_order' => 1,
        ]
    );
}

function pendingSubscriptionFor(User $user, SubscriptionPlan $plan, string $orderId): Subscription
{
    return Subscription::create([
        'user_id' => $user->id,
        'subscription_plan_id' => $plan->id,
        'plan' => 'basic',
        'plan_slug' => $plan->slug,
        'amount_paid' => $plan->price,
        'status' => 'cancelled',
        'payment_status' => 'pending',
        'payment_reference' => $orderId,
        'payment_method' => 'snippe',
    ]);
}

test('subscription webhook activates the plan and never credits the wallet', function () {
    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $plan = subscriptionPlanForTests();
    $orderId = 'sub-'.$user->id.'-winga-complex-1700000000';
    $pending = pendingSubscriptionFor($user, $plan, $orderId);

    snippeSignedPost($this, [
        'id' => 'evt_sub_1',
        'type' => 'payment.completed',
        'data' => [
            'reference' => 'pi_sub_1',
            'status' => 'completed',
            'amount' => ['value' => 5000, 'currency' => 'TZS'],
            'metadata' => [
                'user_id' => (string) $user->id,
                'subscription_id' => (string) $pending->id,
                'payment_type' => 'subscription',
                'order_id' => $orderId,
            ],
        ],
    ])->assertStatus(200);

    expect(Subscription::where('user_id', $user->id)->where('status', 'active')->where('payment_status', 'completed')->exists())->toBeTrue();
    expect((float) $user->fresh()->wallet_balance)->toBe(0.0);
    expect(Transaction::where('user_id', $user->id)->where('type', 'deposit')->exists())->toBeFalse();
    expect(SubscriptionPayment::where('user_id', $user->id)->count())->toBe(1);
    expect((float) SubscriptionPayment::where('user_id', $user->id)->sum('amount'))->toBe(5000.0);
});

test('subscription webhook resolves metadata nested under customer', function () {
    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $plan = subscriptionPlanForTests();
    $orderId = 'sub-'.$user->id.'-winga-complex-1700000001';
    pendingSubscriptionFor($user, $plan, $orderId);

    snippeSignedPost($this, [
        'id' => 'evt_sub_2',
        'type' => 'payment.completed',
        'data' => [
            'reference' => 'pi_sub_2',
            'status' => 'completed',
            'amount' => ['value' => 5000],
            'customer' => [
                'metadata' => [
                    'user_id' => (string) $user->id,
                    'order_id' => $orderId,
                ],
            ],
        ],
    ])->assertStatus(200);

    expect(Subscription::where('user_id', $user->id)->where('status', 'active')->exists())->toBeTrue();
    expect((float) $user->fresh()->wallet_balance)->toBe(0.0);
    expect(Transaction::where('user_id', $user->id)->where('type', 'deposit')->exists())->toBeFalse();
});

test('duplicate subscription webhooks do not create a second subscription or payment row', function () {
    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $plan = subscriptionPlanForTests();
    $orderId = 'sub-'.$user->id.'-winga-complex-1700000002';
    $pending = pendingSubscriptionFor($user, $plan, $orderId);

    $payload = [
        'id' => 'evt_sub_3',
        'type' => 'payment.completed',
        'data' => [
            'reference' => 'pi_sub_3',
            'status' => 'completed',
            'amount' => ['value' => 5000],
            'metadata' => [
                'user_id' => (string) $user->id,
                'subscription_id' => (string) $pending->id,
                'payment_type' => 'subscription',
                'order_id' => $orderId,
            ],
        ],
    ];

    snippeSignedPost($this, $payload)->assertStatus(200);
    snippeSignedPost($this, $payload)->assertStatus(200);

    expect(Subscription::where('user_id', $user->id)->where('status', 'active')->count())->toBe(1);
    expect(SubscriptionPayment::where('user_id', $user->id)->count())->toBe(1);
    expect((float) $user->fresh()->wallet_balance)->toBe(0.0);
});

test('failed subscription webhook marks the pending row as failed', function () {
    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $plan = subscriptionPlanForTests();
    $orderId = 'sub-'.$user->id.'-winga-complex-1700000003';
    $pending = pendingSubscriptionFor($user, $plan, $orderId);

    snippeSignedPost($this, [
        'id' => 'evt_sub_4',
        'type' => 'payment.failed',
        'data' => [
            'reference' => 'pi_sub_4',
            'status' => 'failed',
            'amount' => ['value' => 5000],
            'metadata' => [
                'user_id' => (string) $user->id,
                'subscription_id' => (string) $pending->id,
                'payment_type' => 'subscription',
                'order_id' => $orderId,
            ],
        ],
    ])->assertStatus(200);

    expect($pending->fresh()->payment_status)->toBe('failed');
    expect(Subscription::where('user_id', $user->id)->where('status', 'active')->exists())->toBeFalse();
});
