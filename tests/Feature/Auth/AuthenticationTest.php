<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\VerifyOtp;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrorsIn('email');

    $this->assertGuest();
});

test('users with two factor enabled are redirected to email otp verification', function () {
    Mail::fake();

    $user = User::factory()->create([
        'role' => 'winga',
        'two_factor_enabled' => true,
    ]);

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login')
        ->assertRedirect(route('verify-otp'));

    expect(session('pending_user_id'))->toBe($user->id);
    Mail::assertSent(SendOtpMail::class, fn (SendOtpMail $mail) => $mail->hasTo($user->email));
    $this->assertGuest();
});

test('users without two factor enabled are logged in directly', function () {
    $user = User::factory()->create(['role' => 'winga', 'two_factor_enabled' => false]);

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login')
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('a pending login can be completed with a valid otp', function () {
    $user = User::factory()->create([
        'role' => 'winga',
        'two_factor_enabled' => true,
        'onboarding_completed' => true,
    ]);

    $code = $user->generateOtp();

    $this->withSession(['pending_user_id' => $user->id]);

    Livewire::test(VerifyOtp::class)
        ->set('otp', $code)
        ->call('verify')
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));

    $this->assertGuest();
});
