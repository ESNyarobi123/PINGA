<?php

use App\Livewire\Auth\ForgotPassword;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

test('forgot password screen can be rendered', function () {
    $response = $this->get(route('password.request'));

    $response->assertOk();
});

test('OTP is sent when valid email is submitted', function () {
    Mail::fake();

    $user = User::factory()->create(['email' => 'test@example.com']);

    $component = Livewire::test(ForgotPassword::class);
    $component->set('email', 'test@example.com');
    $component->call('sendOtp');

    $component->assertSet('step', 2);
    $component->assertSet('successMessage', 'OTP imetumwa kwenye barua pepe yako!');

    Mail::assertSent(PasswordResetOtpMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('error shown when email not found', function () {
    $component = Livewire::test(ForgotPassword::class);
    $component->set('email', 'nonexistent@example.com');
    $component->call('sendOtp');

    $component->assertSet('step', 1);
    $component->assertHasErrors('email');
});

test('password can be reset with valid OTP', function () {
    Mail::fake();

    $user = User::factory()->create(['email' => 'reset@example.com']);

    // Step 1: Send OTP
    $component = Livewire::test(ForgotPassword::class);
    $component->set('email', 'reset@example.com');
    $component->call('sendOtp');
    $component->assertSet('step', 2);

    // Get the raw OTP from the mailed instance
    $sentOtp = null;
    Mail::assertSent(PasswordResetOtpMail::class, function ($mail) use (&$sentOtp) {
        $sentOtp = $mail->otp;

        return true;
    });

    // Step 2: Verify OTP only
    $component->set('otp', $sentOtp);
    $component->call('verifyOtp');
    $component->assertSet('step', 3);

    // Step 3: Set new password
    $component->set('password', 'newpassword123');
    $component->set('password_confirmation', 'newpassword123');
    $component->call('resetPassword');

    $component->assertSet('step', 4);

    $user->refresh();
    expect(Hash::check('newpassword123', $user->password))->toBeTrue();
});

test('password reset fails with invalid OTP', function () {
    Mail::fake();

    $user = User::factory()->create(['email' => 'fail@example.com']);

    $component = Livewire::test(ForgotPassword::class);
    $component->set('email', 'fail@example.com');
    $component->call('sendOtp');
    $component->assertSet('step', 2);

    // Try to verify with wrong OTP
    $component->set('otp', '000000');
    $component->call('verifyOtp');

    // Should stay on step 2 with error
    $component->assertSet('step', 2);
    $component->assertNotSet('errorMessage', '');
});

test('OTP can be resent on step 2', function () {
    Mail::fake();

    $user = User::factory()->create(['email' => 'resend@example.com']);

    $component = Livewire::test(ForgotPassword::class);
    $component->set('email', 'resend@example.com');
    $component->call('sendOtp');

    // Resend
    $component->call('resendOtp');

    $component->assertSet('successMessage', 'OTP mpya imetumwa kwenye barua pepe yako!');

    Mail::assertSent(PasswordResetOtpMail::class, 2);
});
