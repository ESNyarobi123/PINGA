<?php

use App\Livewire\Admin\MigogoroDetail;
use App\Livewire\Mteja\Wallet;
use App\Livewire\Winga\WekaCode;
use App\Models\Dispute;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Services\SnippePayoutService;
use App\Services\WithdrawalService;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

function payoutWithdrawal(User $user, array $overrides = []): WithdrawalRequest
{
    return WithdrawalRequest::create(array_merge([
        'user_id' => $user->id,
        'amount' => 10000,
        'charge_percent' => 5,
        'charge_amount' => 500,
        'net_amount' => 10000,
        'account_number' => '0788909090',
        'network' => 'vodacom',
        'status' => 'pending',
        'payout_status' => 'processing',
    ], $overrides));
}

test('a failed payout webhook refunds a withdrawal only once', function () {
    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = payoutWithdrawal($user);

    Transaction::create([
        'user_id' => $user->id,
        'type' => 'withdrawal',
        'amount' => 10500,
        'description' => 'Kutoa pesa',
        'balance_after' => 0,
        'reference' => 'WD-'.$withdrawal->id,
        'status' => 'processing',
    ]);

    $payload = [
        'id' => 'po_fail_1',
        'status' => 'failed',
        'metadata' => [
            'type' => 'withdrawal',
            'user_id' => $user->id,
            'withdrawal_id' => $withdrawal->id,
        ],
    ];

    $service = new SnippePayoutService;
    $service->handlePayoutWebhook($payload);
    $service->handlePayoutWebhook($payload);

    expect((float) $user->fresh()->wallet_balance)->toBe(10500.0);
    expect(Transaction::where('reference', 'refund-po_fail_1')->count())->toBe(1);
    expect($withdrawal->fresh()->status)->toBe('rejected');
    expect($withdrawal->fresh()->payout_status)->toBe('failed');
});

test('a failed payout webhook refunds the withdrawal owner when metadata has no user id', function () {
    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = payoutWithdrawal($user, ['charge_amount' => 0]);

    $service = new SnippePayoutService;
    $service->handlePayoutWebhook([
        'id' => 'po_fail_2',
        'status' => 'failed',
        'metadata' => [
            'type' => 'withdrawal',
            'withdrawal_id' => $withdrawal->id,
        ],
    ]);

    expect((float) $user->fresh()->wallet_balance)->toBe(10000.0);
});

test('a completed payout webhook closes the withdrawal debit transaction', function () {
    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = payoutWithdrawal($user, ['charge_amount' => 0]);

    Transaction::create([
        'user_id' => $user->id,
        'type' => 'withdrawal',
        'amount' => 10000,
        'description' => 'Kutoa pesa',
        'balance_after' => 0,
        'reference' => 'WD-'.$withdrawal->id,
        'status' => 'processing',
    ]);

    $service = new SnippePayoutService;
    $service->handlePayoutWebhook([
        'id' => 'po_ok_1',
        'status' => 'completed',
        'metadata' => [
            'type' => 'withdrawal',
            'user_id' => $user->id,
            'withdrawal_id' => $withdrawal->id,
        ],
    ]);

    expect($withdrawal->fresh()->status)->toBe('paid');
    expect($withdrawal->fresh()->payout_status)->toBe('completed');
    expect(Transaction::where('reference', 'WD-'.$withdrawal->id)->first()->status)->toBe('completed');
});

test('retrying a refunded withdrawal takes the money back out of the wallet first', function () {
    Http::fake([
        'api.snippe.sh/v1/payouts/send' => Http::response([
            'status' => 'success',
            'data' => ['id' => 'po_retry_1', 'status' => 'pending'],
        ], 200),
    ]);

    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 20000]);
    $withdrawal = payoutWithdrawal($user, [
        'charge_amount' => 0,
        'status' => 'rejected',
        'payout_status' => 'failed',
    ]);

    $result = app(WithdrawalService::class)->retry($withdrawal);

    expect($result['success'])->toBeTrue();
    expect((float) $user->fresh()->wallet_balance)->toBe(10000.0);
    expect($withdrawal->fresh()->status)->toBe('pending');
    expect($withdrawal->fresh()->retry_count)->toBe(1);
});

test('retrying an already paid withdrawal does nothing', function () {
    Http::fake();

    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 20000]);
    $withdrawal = payoutWithdrawal($user, [
        'charge_amount' => 0,
        'status' => 'paid',
        'payout_status' => 'completed',
    ]);

    $result = app(WithdrawalService::class)->retry($withdrawal);

    expect($result['success'])->toBeFalse();
    expect((float) $user->fresh()->wallet_balance)->toBe(20000.0);
    Http::assertNothingSent();
});

test('a client can withdraw the full wallet balance while a job is in escrow', function () {
    $user = User::factory()->create([
        'role' => 'client',
        'wallet_balance' => 60000,
        'phone' => '0788909090',
    ]);

    Payment::create([
        'employer_id' => $user->id,
        'amount' => 50000,
        'platform_fee' => 5000,
        'worker_amount' => 45000,
        'status' => 'escrowed',
        'payment_method' => 'wallet',
    ]);

    Livewire::actingAs($user)
        ->test(Wallet::class)
        ->set('withdrawAmount', 50000)
        ->set('withdrawPhone', '0788909090')
        ->set('withdrawNetwork', 'vodacom')
        ->call('submitWithdrawal')
        ->assertHasNoErrors();

    // 60,000 - (50,000 + 5% = 52,500) = 7,500. Escrow was already debited earlier.
    expect((float) $user->fresh()->wallet_balance)->toBe(7500.0);
});

test('the completion code releases the escrowed payment when the job has an older released payment', function () {
    $employer = User::factory()->create(['role' => 'mteja']);
    $worker = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);

    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'hired_worker_id' => $worker->id,
        'status' => 'in_progress',
        'completion_code' => '123456',
        'code_hold_until' => null,
    ]);

    Payment::create([
        'job_id' => $job->id,
        'employer_id' => $employer->id,
        'worker_id' => $worker->id,
        'amount' => 88000,
        'platform_fee' => 8000,
        'worker_amount' => 80000,
        'status' => 'released',
        'payment_method' => 'wallet',
        'payout_status' => 'completed',
        'escrow_released_at' => now()->subDays(2),
    ]);

    $escrowed = Payment::create([
        'job_id' => $job->id,
        'employer_id' => $employer->id,
        'worker_id' => $worker->id,
        'amount' => 80000,
        'platform_fee' => 8000,
        'worker_amount' => 72000,
        'status' => 'escrowed',
        'payment_method' => 'wallet',
    ]);

    Livewire::actingAs($worker)
        ->test(WekaCode::class)
        ->call('selectJob', $job->id)
        ->set('code', '123456')
        ->call('verify');

    expect((float) $worker->fresh()->wallet_balance)->toBe(72000.0);
    expect($escrowed->fresh()->status)->toBe('released');
});

test('refunding a dispute escrow credits the client once and records a transaction', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employer = User::factory()->create(['role' => 'mteja', 'wallet_balance' => 0]);
    $worker = User::factory()->create(['role' => 'winga']);

    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'hired_worker_id' => $worker->id,
        'status' => 'in_progress',
    ]);

    $payment = Payment::create([
        'job_id' => $job->id,
        'employer_id' => $employer->id,
        'worker_id' => $worker->id,
        'amount' => 10000,
        'platform_fee' => 1000,
        'worker_amount' => 9000,
        'status' => 'escrowed',
        'payment_method' => 'wallet',
    ]);

    $dispute = Dispute::create([
        'job_id' => $job->id,
        'initiator_id' => $employer->id,
        'respondent_id' => $worker->id,
        'status' => 'open',
        'reason' => 'Kazi haijakamilika',
        'description' => 'Mteja anadai kazi haijafanyika',
        'priority' => 'medium',
    ]);

    Livewire::actingAs($admin)
        ->test(MigogoroDetail::class, ['id' => $dispute->id])
        ->call('refundToClient');

    expect((float) $employer->fresh()->wallet_balance)->toBe(10000.0);
    expect(Transaction::where('payment_id', $payment->id)->where('type', 'credit')->count())->toBe(1);
    expect($payment->fresh()->status)->toBe('refunded');
    expect($job->fresh()->status)->toBe('open');
});

test('the payments audit command flags duplicate active payments for one job', function () {
    $employer = User::factory()->create(['role' => 'mteja']);
    $worker = User::factory()->create(['role' => 'winga']);

    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'hired_worker_id' => $worker->id,
        'status' => 'in_progress',
    ]);

    foreach ([80000, 80000] as $amount) {
        Payment::create([
            'job_id' => $job->id,
            'employer_id' => $employer->id,
            'worker_id' => $worker->id,
            'amount' => $amount,
            'platform_fee' => 8000,
            'worker_amount' => 72000,
            'status' => 'escrowed',
            'payment_method' => 'wallet',
        ]);
    }

    $this->artisan('payments:audit')->assertExitCode(1);
});

test('the payments audit command can repair stuck withdrawal ledger rows', function () {
    $user = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $withdrawal = payoutWithdrawal($user, ['charge_amount' => 0, 'status' => 'paid', 'payout_status' => 'processing']);

    $transaction = Transaction::create([
        'user_id' => $user->id,
        'type' => 'withdrawal',
        'amount' => 10000,
        'description' => 'Kutoa pesa',
        'balance_after' => 0,
        'reference' => 'WD-'.$withdrawal->id,
        'status' => 'processing',
    ]);

    $this->artisan('payments:audit', ['--fix' => true])->assertExitCode(0);

    expect($transaction->fresh()->status)->toBe('completed');
    expect($withdrawal->fresh()->payout_status)->toBe('completed');
});
