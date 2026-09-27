<?php

use App\Livewire\Mteja\Wallet;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Livewire\Livewire;

test('client can successfully submit withdrawal request and balance is deducted with charge', function () {
    $user = User::factory()->create([
        'role' => 'client',
        'wallet_balance' => 60000,
        'phone' => '0788909090',
    ]);

    Livewire::actingAs($user)
        ->test(Wallet::class)
        ->set('withdrawAmount', 50000)
        ->set('withdrawPhone', '0788909090')
        ->set('withdrawNetwork', 'vodacom')
        ->call('submitWithdrawal')
        ->assertHasNoErrors()
        ->assertSet('showWithdrawModal', false)
        ->assertSet('activeTab', 'withdrawals');

    // 50,000 + 5% (2,500) = 52,500 total deduction
    // 60,000 - 52,500 = 7,500 remaining balance
    $user->refresh();
    expect((float) $user->wallet_balance)->toBe(7500.0);

    // Verify withdrawal request created in database
    $withdrawal = WithdrawalRequest::where('user_id', $user->id)->first();
    expect($withdrawal)->not->toBeNull();
    expect((float) $withdrawal->amount)->toBe(50000.0);
    expect((float) $withdrawal->charge_amount)->toBe(2500.0);
    expect($withdrawal->account_number)->toBe('0788909090');
    expect($withdrawal->phone)->toBe('0788909090');
    expect($withdrawal->network)->toBe('vodacom');
    expect($withdrawal->status)->toBe('pending');

    // Verify transaction record created
    $transaction = Transaction::where('user_id', $user->id)->where('type', 'debit')->first();
    expect($transaction)->not->toBeNull();
    expect((float) $transaction->amount)->toBe(52500.0);
    expect($transaction->reference)->toBe('WD-'.$withdrawal->id);
});

test('client cannot withdraw more than available balance', function () {
    $user = User::factory()->create([
        'role' => 'client',
        'wallet_balance' => 10000,
        'phone' => '0788909090',
    ]);

    Livewire::actingAs($user)
        ->test(Wallet::class)
        ->set('withdrawAmount', 50000)
        ->set('withdrawPhone', '0788909090')
        ->set('withdrawNetwork', 'vodacom')
        ->call('submitWithdrawal')
        ->assertHasErrors(['withdrawAmount']);

    $user->refresh();
    expect((float) $user->wallet_balance)->toBe(10000.0);
    expect(WithdrawalRequest::where('user_id', $user->id)->count())->toBe(0);
});

test('client wallet view displays rejection reason for rejected withdrawal requests and refunds', function () {
    $user = User::factory()->create([
        'role' => 'client',
        'wallet_balance' => 20000,
        'phone' => '0788909090',
    ]);

    WithdrawalRequest::create([
        'user_id' => $user->id,
        'amount' => 50000,
        'charge_percent' => 5,
        'charge_amount' => 2500,
        'net_amount' => 50000,
        'account_number' => '0788909090',
        'network' => 'vodacom',
        'status' => 'rejected',
        'admin_note' => 'Huduma ya Vodacom inasumbua kwa sasa, jaribu namba nyingine',
        'payout_status' => 'failed',
    ]);

    Transaction::create([
        'user_id' => $user->id,
        'type' => 'credit',
        'amount' => 52500,
        'description' => 'Refund: Ombi la kutoa pesa limekataliwa - Sababu: Huduma ya Vodacom inasumbua kwa sasa, jaribu namba nyingine',
        'balance_after' => 20000,
        'reference' => 'admin-reject-1-12345',
        'status' => 'completed',
    ]);

    // Test default transactions tab
    Livewire::actingAs($user)
        ->test(Wallet::class)
        ->assertSee('Sababu ya kukataliwa:')
        ->assertSee('Huduma ya Vodacom inasumbua kwa sasa, jaribu namba nyingine');

    // Test withdrawals tab
    Livewire::actingAs($user)
        ->test(Wallet::class)
        ->set('activeTab', 'withdrawals')
        ->assertSee('Imekataliwa')
        ->assertSee('Sababu ya kukataliwa:')
        ->assertSee('Huduma ya Vodacom inasumbua kwa sasa, jaribu namba nyingine');
});
