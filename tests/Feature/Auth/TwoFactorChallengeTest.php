<?php

use App\Livewire\Auth\VerifyOtp;
use App\Models\User;
use Livewire\Livewire;

test('otp verification page redirects to login when there is no pending login', function () {
    $response = $this->get(route('verify-otp'));

    $response->assertRedirect(route('login'));
});

test('otp verification page can be rendered while a login is pending', function () {
    $user = User::factory()->create(['two_factor_enabled' => true]);

    $response = $this->withSession(['pending_user_id' => $user->id])
        ->get(route('verify-otp'));

    $response->assertOk();
});

test('an invalid otp keeps the user on the verification screen', function () {
    $user = User::factory()->create(['two_factor_enabled' => true]);
    $user->generateOtp();

    $this->withSession(['pending_user_id' => $user->id]);

    Livewire::test(VerifyOtp::class)
        ->set('otp', '000000')
        ->call('verify')
        ->assertSet('errorMessage', 'OTP uliyoingiza si sahihi, imeisha muda wake au majaribio yamezidi.');

    $this->assertGuest();
});
