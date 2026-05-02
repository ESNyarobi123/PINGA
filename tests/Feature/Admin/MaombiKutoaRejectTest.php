<?php

use App\Livewire\Admin\MaombiKutoa;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Livewire\Livewire;

test('rejecting a withdrawal returns funds and closes modal', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);

    $req = WithdrawalRequest::create([
        'user_id' => $winga->id,
        'amount' => 10000,
        'account_number' => '255712345678',
        'network' => 'vodacom',
        'status' => 'pending',
        'payout_status' => 'pending',
    ]);

    Livewire::actingAs($admin)
        ->test(MaombiKutoa::class)
        ->set('selectedRequestId', $req->id)
        ->set('rejectionReason', 'Namba ya simu si sahihi')
        ->call('confirmReject')
        ->assertSet('selectedRequestId', null)
        ->assertSet('rejectionReason', '');

    $req->refresh();
    expect($req->status)->toBe('rejected');
    expect($req->admin_note)->toBe('Namba ya simu si sahihi');

    $winga->refresh();
    expect((float) $winga->wallet_balance)->toBe(10000.0);
});

test('double rejection does not refund twice', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);

    $req = WithdrawalRequest::create([
        'user_id' => $winga->id,
        'amount' => 5000,
        'account_number' => '255712345678',
        'network' => 'vodacom',
        'status' => 'pending',
        'payout_status' => 'pending',
    ]);

    Livewire::actingAs($admin)
        ->test(MaombiKutoa::class)
        ->set('selectedRequestId', $req->id)
        ->set('rejectionReason', 'Test')
        ->call('confirmReject');

    $winga->refresh();
    expect((float) $winga->wallet_balance)->toBe(5000.0);

    // Try rejecting again — should not add more money
    Livewire::actingAs($admin)
        ->test(MaombiKutoa::class)
        ->call('rejectWithdrawal', $req->id, 'Double reject attempt');

    $winga->refresh();
    expect((float) $winga->wallet_balance)->toBe(5000.0);
});
