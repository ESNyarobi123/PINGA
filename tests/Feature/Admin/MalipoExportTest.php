<?php

use App\Livewire\Admin\Malipo;
use App\Livewire\Admin\MaombiKutoa;
use App\Models\Job;
use App\Models\Payment;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Livewire\Livewire;

test('admin can export payment transactions to csv', function () {
    $admin = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
    $employer = User::factory()->create(['role' => 'client', 'name' => 'Ainas Abdul']);
    $worker = User::factory()->create(['role' => 'winga', 'name' => 'John Fundi']);

    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'hired_worker_id' => $worker->id,
        'title' => 'Logo Designer',
        'status' => 'completed',
    ]);

    Payment::create([
        'job_id' => $job->id,
        'employer_id' => $employer->id,
        'worker_id' => $worker->id,
        'amount' => 50000,
        'platform_fee' => 5000,
        'worker_amount' => 45000,
        'status' => 'released',
        'payment_method' => 'wallet',
        'payment_reference' => 'PAY-LOGO-001',
    ]);

    $test = Livewire::actingAs($admin)
        ->test(Malipo::class)
        ->call('exportTransactions');

    $test->assertFileDownloaded();
});

test('admin can export withdrawal requests to csv', function () {
    $admin = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
    $winga = User::factory()->create(['role' => 'winga', 'name' => 'Salum Winga']);

    WithdrawalRequest::create([
        'user_id' => $winga->id,
        'amount' => 30000,
        'charge_amount' => 1500,
        'account_number' => '0754123456',
        'network' => 'vodacom',
        'status' => 'pending',
        'payout_status' => 'pending',
    ]);

    $test = Livewire::actingAs($admin)
        ->test(MaombiKutoa::class)
        ->call('exportWithdrawals');

    $test->assertFileDownloaded();
});

test('admin can export selected withdrawal requests to csv via bulk action', function () {
    $admin = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
    $winga = User::factory()->create(['role' => 'winga', 'name' => 'Mercy Edward']);

    $req = WithdrawalRequest::create([
        'user_id' => $winga->id,
        'amount' => 20000,
        'charge_amount' => 0,
        'account_number' => '0794047441',
        'network' => 'airtel',
        'status' => 'paid',
        'payout_status' => 'completed',
    ]);

    $test = Livewire::actingAs($admin)
        ->test(MaombiKutoa::class)
        ->set('selectedRequests', [$req->id])
        ->set('bulkAction', 'export')
        ->call('executeBulkAction');

    $test->assertFileDownloaded();
});
