<?php

use App\Models\User;
use Laravel\Fortify\Features;
use Livewire\Livewire;

beforeEach(function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);
});

test('two factor settings page can be rendered', function () {
    $user = User::factory()->create(['two_factor_enabled' => false]);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('two-factor.show'))
        ->assertOk()
        ->assertSee('Two-factor authentication');
});

test('two factor settings page requires password confirmation when enabled', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('two-factor.show'));

    $response->assertRedirect(route('password.confirm'));
});

test('enabling two factor switches the user over to email otp', function () {
    $user = User::factory()->create(['two_factor_enabled' => false]);

    $this->actingAs($user);

    Livewire::test('pages::settings.two-factor')
        ->assertSet('twoFactorEnabled', false)
        ->call('enable')
        ->assertSet('twoFactorEnabled', true);

    expect((bool) $user->fresh()->two_factor_enabled)->toBeTrue();
});

test('disabling two factor turns email otp off again', function () {
    $user = User::factory()->create(['two_factor_enabled' => true]);

    $this->actingAs($user);

    Livewire::test('pages::settings.two-factor')
        ->assertSet('twoFactorEnabled', true)
        ->call('disable')
        ->assertSet('twoFactorEnabled', false);

    expect((bool) $user->fresh()->two_factor_enabled)->toBeFalse();
});
