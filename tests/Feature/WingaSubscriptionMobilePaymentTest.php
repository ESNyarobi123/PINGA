<?php

use App\Livewire\Admin\Malipo;
use App\Livewire\Admin\Subscriptions;
use App\Livewire\Winga\Subscription as SubscriptionPage;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

function mobileSubscriptionPlan(int $price = 5000): SubscriptionPlan
{
    return SubscriptionPlan::query()->firstOrCreate(
        ['slug' => 'winga-mobile-test'],
        [
            'name' => 'Winga Mobile Test',
            'name_en' => 'Winga Mobile Test',
            'price' => $price,
            'duration_days' => 30,
            'features' => [],
            'is_active' => true,
            'sort_order' => 1,
        ]
    );
}

function mobileSubscriptionWebhook($test, array $payload)
{
    $secret = (string) config('services.snippe.webhook_secret');
    $body = json_encode($payload);
    $timestamp = (string) time();
    $signature = hash_hmac('sha256', "{$timestamp}.{$body}", $secret);

    return $test->call('POST', '/api/webhooks/snippe', [], [], [], [
        'HTTP_X_WEBHOOK_SIGNATURE' => $signature,
        'HTTP_X_WEBHOOK_TIMESTAMP' => $timestamp,
        'CONTENT_TYPE' => 'application/json',
    ], $body);
}

function startMobileSubscription(User $winga, ?SubscriptionPlan $plan = null): Subscription
{
    $plan ??= mobileSubscriptionPlan();

    Livewire::actingAs($winga)
        ->test(SubscriptionPage::class)
        ->set('paymentMethod', 'mobile')
        ->set('phone', '0712 345 678')
        ->call('selectPlan', $plan->id)
        ->call('pay');

    return Subscription::where('user_id', $winga->id)->latest('id')->first();
}

/**
 * @return array<string, mixed>
 */
function subscriptionCompletedPayload(Subscription $pending, string $reference): array
{
    return [
        'id' => 'evt_'.$reference,
        'type' => 'payment.completed',
        'data' => [
            'reference' => $reference,
            'status' => 'completed',
            'amount' => ['value' => 5000, 'currency' => 'TZS'],
            'metadata' => [
                'order_id' => $pending->payment_reference,
                'user_id' => (string) $pending->user_id,
                'payment_type' => 'subscription',
                'subscription_id' => (string) $pending->id,
            ],
        ],
    ];
}

test('a winga can start a subscription payment directly with a mobile number', function () {
    Http::fake([
        'api.snippe.sh/*' => Http::response([
            'id' => 'pi_mob_1',
            'reference' => 'pi_mob_1',
            'status' => 'pending',
        ], 200),
    ]);

    $winga = User::factory()->create([
        'role' => 'winga',
        'wallet_balance' => 0,
        'phone' => '0712345678',
        'name' => 'Juma Ally',
    ]);
    $plan = mobileSubscriptionPlan();

    $pending = startMobileSubscription($winga, $plan);

    expect($pending)->not->toBeNull()
        ->and($pending->payment_status)->toBe('pending')
        ->and((float) $pending->amount_paid)->toBe(5000.0)
        ->and($pending->payment_reference)->toStartWith('sub-'.$winga->id.'-')
        ->and((float) $winga->fresh()->wallet_balance)->toBe(0.0);

    expect(Subscription::where('user_id', $winga->id)->where('status', 'active')->exists())->toBeFalse();

    Http::assertSent(fn ($request) => str_contains($request->url(), '/v1/payments')
        && $request['payment_type'] === 'mobile'
        && $request['details']['amount'] === 5000
        && $request['phone_number'] === '255712345678'
        && $request['metadata']['payment_type'] === 'subscription'
        && $request['metadata']['subscription_id'] === (string) $pending->id);
});

test('the snippe webhook activates a mobile subscription and records it as platform revenue', function () {
    Http::fake([
        'api.snippe.sh/*' => Http::response(['id' => 'pi_mob_2', 'reference' => 'pi_mob_2'], 200),
    ]);

    $admin = User::factory()->create(['role' => 'admin']);
    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0, 'phone' => '0712345678']);
    $plan = mobileSubscriptionPlan();

    $pending = startMobileSubscription($winga, $plan);

    $before = Livewire::actingAs($admin)->test(Malipo::class)->instance();
    $beforeRevenue = [
        'subscription' => $before->subscriptionRevenue,
        'total' => $before->totalRevenue,
        'monthly' => $before->monthlyRevenue,
        'today' => $before->todayRevenue,
    ];

    mobileSubscriptionWebhook($this, [
        'id' => 'evt_mob_2',
        'type' => 'payment.completed',
        'data' => [
            'reference' => 'pi_mob_2',
            'status' => 'completed',
            'amount' => ['value' => 5000, 'currency' => 'TZS'],
            'metadata' => [
                'order_id' => $pending->payment_reference,
                'user_id' => (string) $winga->id,
                'payment_type' => 'subscription',
                'subscription_id' => (string) $pending->id,
            ],
        ],
    ])->assertStatus(200);

    $active = Subscription::where('user_id', $winga->id)
        ->where('status', 'active')
        ->where('payment_status', 'completed')
        ->first();

    expect($active)->not->toBeNull()
        ->and($active->expires_at)->not->toBeNull()
        ->and((float) $active->amount_paid)->toBe(5000.0)
        ->and((float) $winga->fresh()->wallet_balance)->toBe(0.0);

    expect(Transaction::where('user_id', $winga->id)->where('type', 'deposit')->exists())->toBeFalse();

    expect(SubscriptionPayment::where('user_id', $winga->id)->count())->toBe(1)
        ->and((float) SubscriptionPayment::where('user_id', $winga->id)->sum('amount'))->toBe(5000.0);

    $after = Livewire::actingAs($admin)->test(Malipo::class)->instance();

    expect($after->subscriptionRevenue - $beforeRevenue['subscription'])->toBe(5000.0)
        ->and($after->totalRevenue - $beforeRevenue['total'])->toBe(5000.0)
        ->and($after->monthlyRevenue - $beforeRevenue['monthly'])->toBe(5000.0)
        ->and($after->todayRevenue - $beforeRevenue['today'])->toBe(5000.0);

    $stats = Livewire::actingAs($admin)->test(Subscriptions::class)->viewData('stats');

    expect($stats['revenue_total'])->toBeGreaterThanOrEqual(5000.0)
        ->and($stats['revenue_month'])->toBeGreaterThanOrEqual(5000.0);
});

test('one mobile subscription payment leaves exactly one paid subscription row', function () {
    Http::fake([
        'api.snippe.sh/*' => Http::response(['id' => 'pi_mob_3', 'reference' => 'pi_mob_3'], 200),
    ]);

    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0, 'phone' => '0712345678']);
    $plan = mobileSubscriptionPlan();

    $pending = startMobileSubscription($winga, $plan);

    mobileSubscriptionWebhook($this, [
        'id' => 'evt_mob_3',
        'type' => 'payment.completed',
        'data' => [
            'reference' => 'pi_mob_3',
            'status' => 'completed',
            'amount' => ['value' => 5000],
            'metadata' => [
                'order_id' => $pending->payment_reference,
                'user_id' => (string) $winga->id,
                'payment_type' => 'subscription',
                'subscription_id' => (string) $pending->id,
            ],
        ],
    ])->assertStatus(200);

    $rows = Subscription::where('user_id', $winga->id)->get();

    expect($rows)->toHaveCount(1)
        ->and($rows->first()->status)->toBe('active')
        ->and($rows->first()->payment_status)->toBe('completed')
        ->and($rows->first()->payment_reference)->toBe('pi_mob_3');
});

test('a duplicate mobile webhook does not extend the plan or revenue twice', function () {
    Http::fake([
        'api.snippe.sh/*' => Http::response(['id' => 'pi_mob_4', 'reference' => 'pi_mob_4'], 200),
    ]);

    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0, 'phone' => '0712345678']);
    $plan = mobileSubscriptionPlan();

    $pending = startMobileSubscription($winga, $plan);

    $payload = [
        'id' => 'evt_mob_4',
        'type' => 'payment.completed',
        'data' => [
            'reference' => 'pi_mob_4',
            'status' => 'completed',
            'amount' => ['value' => 5000],
            'metadata' => [
                'order_id' => $pending->payment_reference,
                'user_id' => (string) $winga->id,
                'payment_type' => 'subscription',
                'subscription_id' => (string) $pending->id,
            ],
        ],
    ];

    mobileSubscriptionWebhook($this, $payload)->assertStatus(200);
    $firstExpiry = (string) Subscription::where('user_id', $winga->id)->where('status', 'active')->value('expires_at');

    mobileSubscriptionWebhook($this, $payload)->assertStatus(200);

    expect(Subscription::where('user_id', $winga->id)->where('status', 'active')->count())->toBe(1)
        ->and((string) Subscription::where('user_id', $winga->id)->where('status', 'active')->value('expires_at'))->toBe($firstExpiry)
        ->and(SubscriptionPayment::where('user_id', $winga->id)->count())->toBe(1)
        ->and((float) SubscriptionPayment::where('user_id', $winga->id)->sum('amount'))->toBe(5000.0);
});

test('a mobile renewal extends the existing row and keeps counting revenue', function () {
    Http::fake([
        'api.snippe.sh/*' => Http::sequence()
            ->push(['id' => 'pi_ren_1', 'reference' => 'pi_ren_1'], 200)
            ->push(['id' => 'pi_ren_2', 'reference' => 'pi_ren_2'], 200),
    ]);

    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0, 'phone' => '0712345678']);
    $plan = mobileSubscriptionPlan();

    $first = startMobileSubscription($winga, $plan);
    mobileSubscriptionWebhook($this, subscriptionCompletedPayload($first, 'pi_ren_1'))->assertStatus(200);

    $active = Subscription::where('user_id', $winga->id)->where('status', 'active')->firstOrFail();
    $active->update(['expires_at' => now()->addDays(3)]);

    $second = startMobileSubscription($winga, $plan);
    mobileSubscriptionWebhook($this, subscriptionCompletedPayload($second, 'pi_ren_2'))->assertStatus(200);

    $renewed = $active->fresh();

    expect(Subscription::where('user_id', $winga->id)->count())->toBe(1)
        ->and($renewed->status)->toBe('active')
        ->and((float) $renewed->amount_paid)->toBe(10000.0)
        ->and((float) SubscriptionPayment::where('user_id', $winga->id)->sum('amount'))->toBe(10000.0)
        ->and($renewed->expires_at->toDateString())->toBe(now()->addDays(33)->toDateString())
        ->and((float) $winga->fresh()->wallet_balance)->toBe(0.0);
});

test('a webhook amount mismatch still activates but alerts the admins', function () {
    \Illuminate\Support\Facades\Notification::fake();

    Http::fake([
        'api.snippe.sh/*' => Http::response(['id' => 'pi_mob_6', 'reference' => 'pi_mob_6'], 200),
    ]);

    $admin = User::factory()->create(['role' => 'admin']);
    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0, 'phone' => '0712345678']);
    $plan = mobileSubscriptionPlan();

    $pending = startMobileSubscription($winga, $plan);

    $payload = subscriptionCompletedPayload($pending, 'pi_mob_6');
    $payload['data']['amount'] = ['value' => 4000];

    mobileSubscriptionWebhook($this, $payload)->assertStatus(200);

    expect(Subscription::where('user_id', $winga->id)->where('status', 'active')->exists())->toBeTrue();

    \Illuminate\Support\Facades\Notification::assertSentTo(
        $admin,
        \App\Notifications\WingaNotification::class,
        fn ($notification) => str_contains($notification->title, 'kiasi')
    );
});

test('a failed mobile subscription payment never activates or records revenue', function () {
    Http::fake([
        'api.snippe.sh/*' => Http::response(['id' => 'pi_mob_5', 'reference' => 'pi_mob_5'], 200),
    ]);

    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0, 'phone' => '0712345678']);
    $plan = mobileSubscriptionPlan();

    $pending = startMobileSubscription($winga, $plan);

    mobileSubscriptionWebhook($this, [
        'id' => 'evt_mob_5',
        'type' => 'payment.failed',
        'data' => [
            'reference' => 'pi_mob_5',
            'status' => 'failed',
            'amount' => ['value' => 5000],
            'metadata' => [
                'order_id' => $pending->payment_reference,
                'user_id' => (string) $winga->id,
                'payment_type' => 'subscription',
                'subscription_id' => (string) $pending->id,
            ],
        ],
    ])->assertStatus(200);

    expect($pending->fresh()->payment_status)->toBe('failed')
        ->and(Subscription::where('user_id', $winga->id)->where('status', 'active')->exists())->toBeFalse()
        ->and(SubscriptionPayment::where('user_id', $winga->id)->count())->toBe(0);
});
