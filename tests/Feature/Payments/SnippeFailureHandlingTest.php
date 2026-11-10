<?php

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Services\SnippePayoutService;
use App\Services\WithdrawalService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

function failureWithdrawal(User $user, array $overrides = []): WithdrawalRequest
{
    return WithdrawalRequest::create(array_merge([
        'user_id' => $user->id,
        'amount' => 5000,
        'account_number' => '0788909090',
        'network' => 'Tigo',
        'status' => 'pending',
        'payout_status' => 'pending',
    ], $overrides));
}

test('an insufficient snippe balance produces an actionable error the admin can fix', function () {
    Http::fake([
        'api.snippe.sh/v1/payouts/send' => Http::response([
            'status' => 'error',
            'code' => 400,
            'error_code' => 'PAY_004',
            'message' => 'Insufficient balance to complete this transaction.',
        ], 400),
    ]);

    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = failureWithdrawal($user);

    $result = app(WithdrawalService::class)->retry($withdrawal);

    expect($result['success'])->toBeFalse()
        ->and($result['error_code'])->toBe('PAY_004')
        ->and($result['transient'])->toBeFalse()
        ->and($result['error'])->toContain('Salio la akaunti ya Snippe');

    $withdrawal->refresh();

    expect($withdrawal->payout_status)->toBe('failed')
        ->and($withdrawal->payout_error)->toContain('Salio la akaunti ya Snippe')
        ->and($withdrawal->status)->toBe('pending');
});

test('a payout timeout keeps the withdrawal in flight so a double click cannot pay twice', function () {
    Http::fake(function () {
        throw new ConnectionException('cURL error 28: Operation timed out after 30001 milliseconds');
    });

    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = failureWithdrawal($user);

    $first = app(WithdrawalService::class)->retry($withdrawal);

    expect($first['success'])->toBeFalse()
        ->and($first['transient'])->toBeTrue()
        ->and($first['error'])->toContain('timeout');

    $withdrawal->refresh();

    // Still "processing": the provider may have accepted it, so a resend is blocked.
    expect($withdrawal->payout_status)->toBe('processing')
        ->and($withdrawal->payout_error)->toContain('timeout')
        ->and($withdrawal->hasPayoutInFlight())->toBeTrue();

    $second = app(WithdrawalService::class)->retry($withdrawal->fresh());

    expect($second['success'])->toBeFalse()
        ->and($second['error'])->toContain('bado inasubiri uthibitisho');
});

test('snippe error codes are translated into admin instructions', function () {
    $service = new SnippePayoutService;

    expect($service->humaniseError('Insufficient balance to complete this transaction.', 'PAY_004'))
        ->toContain('Salio la akaunti ya Snippe');

    expect($service->humaniseError('cURL error 28: Operation timed out', 'TIMEOUT'))
        ->toContain('dashboard ya Snippe');

    expect($service->humaniseError('Invalid API Key', null))
        ->toContain('SNIPPE_API_KEY');

    expect($service->humaniseError('Something odd happened', null))
        ->toBe('Something odd happened');
});

test('a mobile subscription payment timeout leaves the request pending for a late webhook', function () {
    Http::fake(function () {
        throw new ConnectionException('cURL error 28: Operation timed out after 30001 milliseconds');
    });

    Notification::fake();

    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0, 'phone' => '0712345678']);
    $plan = SubscriptionPlan::query()->firstOrCreate(
        ['slug' => 'timeout-plan'],
        [
            'name' => 'Timeout Plan',
            'name_en' => 'Timeout Plan',
            'price' => 5000,
            'duration_days' => 30,
            'features' => [],
            'is_active' => true,
            'sort_order' => 1,
        ]
    );

    Livewire::actingAs($winga)
        ->test(\App\Livewire\Winga\Subscription::class)
        ->set('paymentMethod', 'mobile')
        ->set('phone', '0712345678')
        ->call('selectPlan', $plan->id)
        ->call('pay');

    $pending = Subscription::where('user_id', $winga->id)->latest('id')->first();

    expect($pending)->not->toBeNull()
        ->and($pending->payment_status)->toBe('pending')
        ->and($pending->notes)->toContain('timeout');

    expect(Subscription::where('user_id', $winga->id)->where('status', 'active')->exists())->toBeFalse();
});

test('a definite mobile payment rejection marks the pending subscription as failed', function () {
    Http::fake([
        'api.snippe.sh/v1/payments' => Http::response([
            'status' => 'error',
            'message' => 'Operator rejected the request',
        ], 422),
    ]);

    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0, 'phone' => '0712345678']);
    $plan = SubscriptionPlan::query()->firstOrCreate(
        ['slug' => 'reject-plan'],
        [
            'name' => 'Reject Plan',
            'name_en' => 'Reject Plan',
            'price' => 5000,
            'duration_days' => 30,
            'features' => [],
            'is_active' => true,
            'sort_order' => 1,
        ]
    );

    Livewire::actingAs($winga)
        ->test(\App\Livewire\Winga\Subscription::class)
        ->set('paymentMethod', 'mobile')
        ->set('phone', '0712345678')
        ->call('selectPlan', $plan->id)
        ->call('pay');

    $pending = Subscription::where('user_id', $winga->id)->latest('id')->first();

    expect($pending->payment_status)->toBe('failed')
        ->and($pending->notes)->toContain('Operator rejected the request');
});

test('the diagnose command flags a webhook host that does not match the app url', function () {
    config([
        'app.url' => 'https://winga.example.co.tz',
        'services.snippe.key' => 'sk_test_key',
        'services.snippe.webhook_secret' => 'whsec_test',
        'services.snippe.webhook_base_url' => 'https://old-domain.example.net',
    ]);

    $this->artisan('snippe:diagnose', ['--no-api' => true])
        ->expectsOutputToContain('Webhook host hailingani na APP_URL')
        ->assertFailed();
});

test('the diagnose command passes when everything is aligned', function () {
    config([
        'app.url' => 'https://winga.example.co.tz',
        'services.snippe.key' => 'sk_test_key',
        'services.snippe.webhook_secret' => 'whsec_test',
        'services.snippe.webhook_base_url' => 'https://winga.example.co.tz',
    ]);

    $this->artisan('snippe:diagnose', ['--no-api' => true])
        ->expectsOutputToContain('Snippe iko tayari')
        ->assertSuccessful();
});

test('payouts reconcile marks a processed payout as paid when the webhook never arrived', function () {
    Http::fake([
        'api.snippe.sh/v1/payouts/po_done_1' => Http::response([
            'status' => 'success',
            'data' => ['id' => 'po_done_1', 'status' => 'completed', 'amount' => 5000],
        ], 200),
    ]);

    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = failureWithdrawal($user, [
        'payout_status' => 'processing',
        'payout_reference' => 'po_done_1',
    ]);

    $this->artisan('payouts:reconcile')->assertSuccessful();

    $withdrawal->refresh();

    expect($withdrawal->status)->toBe('paid')
        ->and($withdrawal->payout_status)->toBe('completed')
        ->and($withdrawal->payout_error)->toBeNull();
});

test('payouts reconcile refunds a withdrawal the provider rejected', function () {
    Http::fake([
        'api.snippe.sh/v1/payouts/po_fail_9' => Http::response([
            'status' => 'success',
            'data' => ['id' => 'po_fail_9', 'status' => 'failed', 'amount' => 5000],
        ], 200),
    ]);

    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = failureWithdrawal($user, [
        'payout_status' => 'processing',
        'payout_reference' => 'po_fail_9',
    ]);

    $this->artisan('payouts:reconcile')->assertSuccessful();

    $withdrawal->refresh();

    expect($withdrawal->status)->toBe('rejected')
        ->and($withdrawal->payout_status)->toBe('failed')
        ->and((float) $user->fresh()->wallet_balance)->toBe(5000.0);
});

test('payouts reconcile leaves a still-pending payout untouched', function () {
    Http::fake([
        'api.snippe.sh/v1/payouts/po_wait_1' => Http::response([
            'status' => 'success',
            'data' => ['id' => 'po_wait_1', 'status' => 'pending', 'amount' => 5000],
        ], 200),
    ]);

    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = failureWithdrawal($user, [
        'payout_status' => 'processing',
        'payout_reference' => 'po_wait_1',
    ]);

    $this->artisan('payouts:reconcile')->assertSuccessful();

    expect($withdrawal->fresh()->payout_status)->toBe('processing')
        ->and($withdrawal->fresh()->status)->toBe('pending')
        ->and((float) $user->fresh()->wallet_balance)->toBe(0.0);
});

test('approving a withdrawal during a provider timeout never marks it ready for a second payout', function () {
    Http::fake(function () {
        throw new ConnectionException('cURL error 28: Operation timed out after 45001 milliseconds');
    });

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = failureWithdrawal($user);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\MaombiKutoa::class)
        ->call('approveWithdrawal', $withdrawal->id);

    $withdrawal->refresh();

    expect($withdrawal->payout_status)->toBe('processing')
        ->and($withdrawal->hasPayoutInFlight())->toBeTrue()
        ->and($withdrawal->payout_error)->toContain('timeout');
});
