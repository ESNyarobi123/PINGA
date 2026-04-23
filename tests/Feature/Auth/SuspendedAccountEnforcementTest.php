<?php

use App\Livewire\Auth\Login;
use App\Models\Setting;
use App\Models\User;
use Livewire\Livewire;

test('suspended user cannot complete Livewire login and is sent to appeal page', function () {
    $user = User::factory()->create([
        'email' => 'suspended@example.com',
        'role' => 'winga',
        'two_factor_enabled' => false,
        'suspended_at' => now(),
        'suspended_reason' => 'Imesitishwa na msimamizi',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'suspended@example.com')
        ->set('password', 'password')
        ->call('login')
        ->assertRedirect(route('account-suspended'));

    expect(auth()->check())->toBeFalse();
});

test('authenticated suspended user is logged out when accessing the app', function () {
    $user = User::factory()->create([
        'role' => 'mteja',
        'onboarding_completed' => true,
        'two_factor_enabled' => false,
        'suspended_at' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('mteja.dashboard'));

    $response->assertRedirect(route('account-suspended'));
    $this->assertGuest();
});

test('account suspended page shows appeal email from settings', function () {
    Setting::updateOrCreate(
        ['key' => 'suspension_appeal_email'],
        [
            'value' => 'appeals@example.test',
            'type' => 'string',
            'description' => 'test',
            'category' => 'support',
        ]
    );

    $response = $this->get(route('account-suspended'));

    $response->assertOk()->assertSee('appeals@example.test', false);
});
