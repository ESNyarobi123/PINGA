<?php

use App\Livewire\Admin\MaombiKutoa;
use App\Livewire\Winga\Mapato;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Services\MfanyakaziDashboardService;
use Livewire\Livewire;

test('rejecting a withdrawal does not double count totalEarned or thisMonth', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employer = User::factory()->create(['role' => 'client']);
    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 50000]);

    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'hired_worker_id' => $winga->id,
        'status' => 'completed',
    ]);

    // Worker earned 50k from a job released this month
    $payment = Payment::create([
        'job_id' => $job->id,
        'employer_id' => $employer->id,
        'worker_id' => $winga->id,
        'amount' => 50000,
        'worker_amount' => 50000,
        'platform_fee' => 0,
        'payment_method' => 'wallet',
        'status' => 'released',
        'payout_status' => 'completed',
        'escrow_released_at' => now(),
    ]);

    Transaction::create([
        'user_id' => $winga->id,
        'payment_id' => $payment->id,
        'type' => 'credit',
        'amount' => 50000,
        'description' => 'Malipo ya kazi: '.$job->title,
        'balance_after' => 50000,
        'status' => 'completed',
    ]);

    // Initial check on Mapato
    Livewire::actingAs($winga)
        ->test(Mapato::class)
        ->assertViewHas('totalEarned', 50000.0)
        ->assertViewHas('thisMonth', 50000.0)
        ->assertViewHas('totalWithdrawn', 0.0)
        ->assertViewHas('walletBalance', 50000.0);

    // Worker requests withdrawal of 50,000 -> wallet balance drops to 0
    $winga->decrement('wallet_balance', 50000);
    $req = WithdrawalRequest::create([
        'user_id' => $winga->id,
        'amount' => 50000,
        'charge_percent' => 0,
        'charge_amount' => 0,
        'net_amount' => 50000,
        'account_number' => '0712345678',
        'network' => 'vodacom',
        'status' => 'pending',
        'payout_status' => 'processing',
    ]);

    Transaction::create([
        'user_id' => $winga->id,
        'type' => 'withdrawal',
        'amount' => 50000,
        'description' => 'Kutoa pesa — vodacom',
        'balance_after' => 0,
        'reference' => 'WD-'.$req->id,
        'status' => 'processing',
    ]);

    // Admin rejects withdrawal request
    Livewire::actingAs($admin)
        ->test(MaombiKutoa::class)
        ->set('selectedRequestId', $req->id)
        ->set('rejectionReason', 'Namba ya Vodacom haipatikani hewani')
        ->call('confirmReject');

    $winga->refresh();
    expect((float) $winga->wallet_balance)->toBe(50000.0);

    // After rejection, totalEarned and thisMonth MUST REMAIN 50,000 (NOT 100,000!)
    Livewire::actingAs($winga)
        ->test(Mapato::class)
        ->assertViewHas('totalEarned', 50000.0)
        ->assertViewHas('thisMonth', 50000.0)
        ->assertViewHas('totalWithdrawn', 0.0)
        ->assertViewHas('walletBalance', 50000.0);

    // Dashboard stats also must not double count
    $dashboardService = app(MfanyakaziDashboardService::class);
    $data = $dashboardService->data($winga);
    expect($data['stats']['mapato_jumla'])->toBe(50000.0);
    expect($data['stats']['mapato_wiki'])->toBe(50000.0);
});
