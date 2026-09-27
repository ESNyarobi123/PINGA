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

test('rejecting client withdrawal refunds charge amount and notifies with reason and client wallet url', function () {
    \Illuminate\Support\Facades\Notification::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $client = User::factory()->create(['role' => 'client', 'wallet_balance' => 1000]);

    $req = WithdrawalRequest::create([
        'user_id' => $client->id,
        'amount' => 50000,
        'charge_percent' => 5,
        'charge_amount' => 2500,
        'net_amount' => 50000,
        'account_number' => '0788909090',
        'network' => 'vodacom',
        'status' => 'pending',
        'payout_status' => 'pending',
    ]);

    Livewire::actingAs($admin)
        ->test(MaombiKutoa::class)
        ->set('selectedRequestId', $req->id)
        ->set('rejectionReason', 'Akaunti ya simu haipatikani')
        ->call('confirmReject');

    $client->refresh();
    // 1000 initial + 50000 amount + 2500 charge = 53500
    expect((float) $client->wallet_balance)->toBe(53500.0);

    $req->refresh();
    expect($req->status)->toBe('rejected');
    expect($req->admin_note)->toBe('Akaunti ya simu haipatikani');

    \Illuminate\Support\Facades\Notification::assertSentTo(
        $client,
        \App\Notifications\WingaNotification::class,
        function ($notification) {
            return str_contains($notification->message, 'Akaunti ya simu haipatikani')
                && $notification->action_url === route('mteja.wallet');
        }
    );
});

test('winga tomba-ombi page displays rejection reason when request is rejected', function () {
    $winga = User::factory()->create(['role' => 'winga', 'wallet_balance' => 5000]);

    WithdrawalRequest::create([
        'user_id' => $winga->id,
        'amount' => 10000,
        'account_number' => '0712345678',
        'network' => 'vodacom',
        'status' => 'rejected',
        'admin_note' => 'Namba imefungwa kwa sasa',
        'payout_status' => 'failed',
    ]);

    Livewire::actingAs($winga)
        ->test(\App\Livewire\Winga\TombaOmbi::class)
        ->assertSee('Sababu ya kukataliwa:')
        ->assertSee('Namba imefungwa kwa sasa');
});
