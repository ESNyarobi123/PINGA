<?php

use App\Livewire\Mteja\Wallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

test('checkDepositStatus updates to completed when API returns completed', function () {
    Http::fake([
        'api.snippe.sh/v1/payments/*' => Http::response([
            'status' => 'success',
            'data' => ['status' => 'completed', 'reference' => 'pi_test123'],
        ], 200),
    ]);

    $user = User::factory()->create(['role' => 'mteja', 'wallet_balance' => 1000]);

    $component = Livewire::actingAs($user)->test(Wallet::class);
    $component->set('paymentReference', 'pi_test123');
    $component->set('paymentStatus', 'pending');
    $component->set('pollCount', 0);

    $component->call('checkDepositStatus');

    $component->assertSet('paymentStatus', 'completed');
});

test('checkDepositStatus updates to failed when API returns failed', function () {
    Http::fake([
        'api.snippe.sh/v1/payments/*' => Http::response([
            'status' => 'success',
            'data' => ['status' => 'failed', 'reference' => 'pi_test123'],
        ], 200),
    ]);

    $user = User::factory()->create(['role' => 'mteja', 'wallet_balance' => 1000]);

    $component = Livewire::actingAs($user)->test(Wallet::class);
    $component->set('paymentReference', 'pi_test123');
    $component->set('paymentStatus', 'pending');
    $component->set('pollCount', 0);

    $component->call('checkDepositStatus');

    $component->assertSet('paymentStatus', 'failed');
});

test('checkDepositStatus stops polling after 40 attempts', function () {
    Http::fake([
        'api.snippe.sh/v1/payments/*' => Http::response([
            'status' => 'success',
            'data' => ['status' => 'pending', 'reference' => 'pi_test123'],
        ], 200),
    ]);

    $user = User::factory()->create(['role' => 'mteja', 'wallet_balance' => 1000]);

    $component = Livewire::actingAs($user)->test(Wallet::class);
    $component->set('paymentReference', 'pi_test123');
    $component->set('paymentStatus', 'pending');
    $component->set('pollCount', 41);

    $component->call('checkDepositStatus');

    $component->assertSet('paymentStatus', 'timeout');
});

test('checkDepositStatus does nothing when no reference set', function () {
    $user = User::factory()->create(['role' => 'mteja', 'wallet_balance' => 1000]);

    $component = Livewire::actingAs($user)->test(Wallet::class);
    $component->set('paymentReference', null);
    $component->set('paymentStatus', 'pending');

    $component->call('checkDepositStatus');

    // Status should remain pending (no error thrown)
    $component->assertSet('paymentStatus', 'pending');
});
