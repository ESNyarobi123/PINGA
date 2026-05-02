<?php

use App\Http\Middleware\VerifySnippeWebhookSignature;
use App\Models\Transaction;
use App\Models\User;
use App\Services\SnippePaymentService;
use App\Services\SnippePayoutService;
use Illuminate\Support\Facades\Http;

// ============================================================
// Webhook Signature Verification
// ============================================================

test('webhook rejects request without signature headers', function () {
    $response = $this->postJson('/api/webhooks/snippe', ['type' => 'payment.completed']);

    $response->assertStatus(400)
        ->assertJson(['error' => 'Missing signature headers']);
});

test('webhook rejects expired timestamp', function () {
    $secret = config('services.snippe.webhook_secret');
    $body = json_encode(['type' => 'payment.completed']);
    $expiredTimestamp = (string) (time() - 600);
    $signature = hash_hmac('sha256', "{$expiredTimestamp}.{$body}", $secret);

    $response = $this->call('POST', '/api/webhooks/snippe', [], [], [], [
        'HTTP_X-Webhook-Signature' => $signature,
        'HTTP_X-Webhook-Timestamp' => $expiredTimestamp,
        'CONTENT_TYPE' => 'application/json',
    ], $body);

    $response->assertStatus(400)
        ->assertJson(['error' => 'Webhook timestamp expired']);
});

test('webhook rejects invalid signature', function () {
    $body = json_encode(['type' => 'payment.completed']);
    $timestamp = (string) time();

    $response = $this->call('POST', '/api/webhooks/snippe', [], [], [], [
        'HTTP_X-Webhook-Signature' => 'invalid_signature',
        'HTTP_X-Webhook-Timestamp' => $timestamp,
        'CONTENT_TYPE' => 'application/json',
    ], $body);

    $response->assertStatus(400)
        ->assertJson(['error' => 'Invalid signature']);
});

test('webhook accepts valid signature', function () {
    $secret = config('services.snippe.webhook_secret');
    $payload = [
        'id' => 'evt_test123',
        'type' => 'payment.completed',
        'data' => [
            'reference' => 'pi_test123',
            'status' => 'completed',
            'amount' => ['value' => 5000, 'currency' => 'TZS'],
            'metadata' => ['user_id' => '999', 'order_id' => 'TEST1'],
        ],
    ];
    $body = json_encode($payload);
    $timestamp = (string) time();
    $signature = hash_hmac('sha256', "{$timestamp}.{$body}", $secret);

    $response = $this->call('POST', '/api/webhooks/snippe', [], [], [], [
        'HTTP_X-Webhook-Signature' => $signature,
        'HTTP_X-Webhook-Timestamp' => $timestamp,
        'CONTENT_TYPE' => 'application/json',
    ], $body);

    $response->assertStatus(200);
});

// ============================================================
// Payment Webhook — Wallet Deposit
// ============================================================

test('payment.completed webhook credits user wallet', function () {
    $user = User::factory()->create([
        'role' => 'mteja',
        'wallet_balance' => 0,
    ]);

    $secret = config('services.snippe.webhook_secret');
    $payload = [
        'id' => 'evt_deposit1',
        'type' => 'payment.completed',
        'data' => [
            'reference' => 'pi_deposit1',
            'status' => 'completed',
            'amount' => ['value' => 10000, 'currency' => 'TZS'],
            'channel' => ['type' => 'mobile_money', 'provider' => 'mpesa'],
            'metadata' => ['user_id' => (string) $user->id, 'order_id' => 'D1M12345678'],
        ],
    ];
    $body = json_encode($payload);
    $timestamp = (string) time();
    $signature = hash_hmac('sha256', "{$timestamp}.{$body}", $secret);

    $response = $this->call('POST', '/api/webhooks/snippe', [], [], [], [
        'HTTP_X-Webhook-Signature' => $signature,
        'HTTP_X-Webhook-Timestamp' => $timestamp,
        'CONTENT_TYPE' => 'application/json',
    ], $body);

    $response->assertStatus(200);

    $user->refresh();
    expect((float) $user->wallet_balance)->toBe(10000.0);
    expect(Transaction::where('reference', 'pi_deposit1')->exists())->toBeTrue();
});

test('duplicate webhook does not double credit wallet', function () {
    $user = User::factory()->create([
        'role' => 'mteja',
        'wallet_balance' => 0,
    ]);

    $secret = config('services.snippe.webhook_secret');
    $payload = [
        'id' => 'evt_dup1',
        'type' => 'payment.completed',
        'data' => [
            'reference' => 'pi_dup_test',
            'status' => 'completed',
            'amount' => ['value' => 5000, 'currency' => 'TZS'],
            'channel' => ['type' => 'mobile_money', 'provider' => 'mpesa'],
            'metadata' => ['user_id' => (string) $user->id, 'order_id' => 'D1Mdup12345'],
        ],
    ];
    $body = json_encode($payload);
    $timestamp = (string) time();
    $signature = hash_hmac('sha256', "{$timestamp}.{$body}", $secret);

    $headers = [
        'HTTP_X-Webhook-Signature' => $signature,
        'HTTP_X-Webhook-Timestamp' => $timestamp,
        'CONTENT_TYPE' => 'application/json',
    ];

    $this->call('POST', '/api/webhooks/snippe', [], [], [], $headers, $body)->assertStatus(200);
    $this->call('POST', '/api/webhooks/snippe', [], [], [], $headers, $body)->assertStatus(200);

    $user->refresh();
    expect((float) $user->wallet_balance)->toBe(5000.0);
    expect(Transaction::where('reference', 'pi_dup_test')->count())->toBe(1);
});

// ============================================================
// SnippePaymentService Unit Tests
// ============================================================

test('mobile payment sends correct payload to Snippe API', function () {
    Http::fake([
        'api.snippe.sh/v1/payments' => Http::response([
            'status' => 'success',
            'data' => ['reference' => 'pi_mob123', 'status' => 'pending'],
        ], 200),
    ]);

    $service = new SnippePaymentService;
    $result = $service->createMobilePayment(5000, '0781000000', [
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'john@test.com',
        'user_id' => 1,
    ], 'D1M12345678');

    expect($result)->not->toBeNull();
    expect($result['status'])->toBe('success');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.snippe.sh/v1/payments'
            && $request['payment_type'] === 'mobile'
            && $request['phone_number'] === '255781000000'
            && $request['details']['amount'] === 5000
            && $request['details']['currency'] === 'TZS'
            && strlen($request->header('Idempotency-Key')[0]) <= 30;
    });
});

test('card payment sends correct payload with redirect URLs', function () {
    $user = User::factory()->create(['role' => 'mteja', 'phone' => '0754123456']);
    $this->actingAs($user);

    Http::fake([
        'api.snippe.sh/v1/payments' => Http::response([
            'status' => 'success',
            'data' => [
                'payment_url' => 'https://pay.snippe.sh/test',
                'payment_token' => 'tok_123',
                'reference' => 'pi_card123',
            ],
        ], 200),
    ]);

    $service = new SnippePaymentService;
    $result = $service->createCardPayment(10000, [
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'jane@test.com',
        'user_id' => $user->id,
    ], 'C1K12345678');

    expect($result)->not->toBeNull();
    expect($result['data']['payment_url'])->toBe('https://pay.snippe.sh/test');

    Http::assertSent(function ($request) {
        return $request['payment_type'] === 'card'
            && $request['details']['amount'] === 10000
            && str_contains($request['details']['redirect_url'], '/mteja/wallet')
            && str_contains($request['details']['cancel_url'], 'status=cancelled');
    });
});

// ============================================================
// SnippePayoutService Unit Tests
// ============================================================

test('phone number formatting works correctly', function () {
    $service = new SnippePayoutService;

    expect($service->formatPhone('0781000000'))->toBe('255781000000');
    expect($service->formatPhone('+255781000000'))->toBe('255781000000');
    expect($service->formatPhone('255781000000'))->toBe('255781000000');
    expect($service->formatPhone('781000000'))->toBe('255781000000');
});

test('network detection returns correct provider', function () {
    $service = new SnippePayoutService;

    expect($service->detectNetwork('0411000000'))->toBe('Halopesa');
    expect($service->detectNetwork('0781000000'))->toBe('Airtel');
    expect($service->detectNetwork('0751000000'))->toBe('Vodacom');
    expect($service->detectNetwork('0651000000'))->toBe('Tigo');
});

test('payout sends correct payload to Snippe API', function () {
    Http::fake([
        'api.snippe.sh/v1/payouts/send' => Http::response([
            'status' => 'success',
            'data' => ['id' => 'po_123', 'status' => 'pending', 'amount' => 5000],
        ], 200),
    ]);

    $service = new SnippePayoutService;
    $result = $service->sendPayout([
        'amount' => 5000,
        'phone' => '0781000000',
        'name' => 'Test Worker',
        'narration' => 'Kutoa pesa - Winga Platform',
        'idempotency_key' => 'W1T1234567890',
        'metadata' => ['type' => 'withdrawal', 'user_id' => 1, 'withdrawal_id' => 1],
    ]);

    expect($result['success'])->toBeTrue();
    expect($result['reference'])->toBe('po_123');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.snippe.sh/v1/payouts/send'
            && $request['channel'] === 'mobile'
            && $request['recipient_phone'] === '255781000000'
            && $request['amount'] === 5000
            && strlen($request->header('Idempotency-Key')[0]) <= 30;
    });
});

test('payout fee calculation calls correct endpoint', function () {
    Http::fake([
        'api.snippe.sh/v1/payouts/fee*' => Http::response([
            'status' => 'success',
            'code' => 200,
            'data' => [
                'amount' => 50000,
                'fee_amount' => 1000,
                'total_amount' => 51000,
                'currency' => 'TZS',
            ],
        ], 200),
    ]);

    $service = new SnippePayoutService;
    $result = $service->calculateFee(50000);

    expect($result)->not->toBeNull();
    expect($result['fee_amount'])->toBe(1000);
    expect($result['total_amount'])->toBe(51000);
});
