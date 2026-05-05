<?php

namespace App\Livewire\Auth;

use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class ForgotPassword extends Component
{
    public int $step = 1;

    public string $email = '';

    public string $otp = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $errorMessage = '';

    public string $successMessage = '';

    /**
     * Step 1: Send OTP to email.
     */
    public function sendOtp(): void
    {
        $this->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Barua pepe inahitajika.',
            'email.email' => 'Barua pepe si sahihi.',
        ]);

        $this->errorMessage = '';
        $this->successMessage = '';

        $email = Str::lower(trim($this->email));
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->addError('email', 'Hatukupata akaunti yenye barua pepe hii.');

            return;
        }

        $otp = $user->generateOtp();
        Mail::to($user->email)->send(new PasswordResetOtpMail($otp));

        session(['password_reset_user_id' => $user->id]);

        $this->step = 2;
        $this->successMessage = 'OTP imetumwa kwenye barua pepe yako!';
    }

    /**
     * Step 2: Verify OTP only.
     */
    public function verifyOtp(): void
    {
        $this->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Nambari ya OTP inahitajika.',
            'otp.size' => 'OTP lazima iwe tarakimu 6.',
        ]);

        $this->errorMessage = '';

        $userId = session('password_reset_user_id');
        $user = User::find($userId);

        if (! $user) {
            $this->errorMessage = 'Muda umekwisha. Tafadhali anza upya.';
            $this->step = 1;
            session()->forget('password_reset_user_id');

            return;
        }

        if (! $user->verifyOtp($this->otp)) {
            $this->errorMessage = 'OTP si sahihi, imeisha muda wake, au majaribio yamezidi.';

            return;
        }

        // OTP verified — mark in session so step 3 can proceed
        session(['password_reset_otp_verified' => true]);

        $this->step = 3;
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    /**
     * Step 3: Set new password (only after OTP verified).
     */
    public function resetPassword(): void
    {
        $this->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Nenosiri jipya linahitajika.',
            'password.min' => 'Nenosiri lazima liwe angalau herufi 8.',
            'password.confirmed' => 'Nenosiri halifanani.',
        ]);

        $this->errorMessage = '';

        if (! session('password_reset_otp_verified')) {
            $this->step = 1;
            session()->forget(['password_reset_user_id', 'password_reset_otp_verified']);

            return;
        }

        $userId = session('password_reset_user_id');
        $user = User::find($userId);

        if (! $user) {
            $this->errorMessage = 'Muda umekwisha. Tafadhali anza upya.';
            $this->step = 1;
            session()->forget(['password_reset_user_id', 'password_reset_otp_verified']);

            return;
        }

        $user->forceFill([
            'password' => Hash::make($this->password),
        ])->save();

        session()->forget(['password_reset_user_id', 'password_reset_otp_verified']);

        $this->step = 4;
        $this->successMessage = 'Nenosiri limebadilishwa kikamilifu!';
    }

    /**
     * Resend OTP.
     */
    public function resendOtp(): void
    {
        $this->errorMessage = '';
        $this->successMessage = '';

        $userId = session('password_reset_user_id');
        $user = User::find($userId);

        if (! $user) {
            $this->errorMessage = 'Muda umekwisha. Tafadhali anza upya.';
            $this->step = 1;
            session()->forget(['password_reset_user_id', 'password_reset_otp_verified']);

            return;
        }

        $otp = $user->generateOtp();
        Mail::to($user->email)->send(new PasswordResetOtpMail($otp));

        $this->successMessage = 'OTP mpya imetumwa kwenye barua pepe yako!';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('layouts.auth');
    }
}
