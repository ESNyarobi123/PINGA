<?php

namespace App\Livewire\Auth;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Login extends Component
{
    public $email;

    public $password;

    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string',
    ];

    public function login()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if ($user && Hash::check($this->password, $user->password)) {
            // Admin anapitiliza moja kwa moja (Bypass 2FA/OTP)
            if ($user->isAdmin()) {
                Auth::login($user, $this->remember);
                session()->regenerate();

                return redirect()->intended(route('dashboard'));
            }

            // Check if 2FA is enabled kwa watumiaji wengine
            if ($user->two_factor_enabled) {
                // Generate and send OTP
                $otp = $user->generateOtp();
                Mail::to($user->email)->send(new SendOtpMail($otp));

                // Store in session for OTP Verification step
                session([
                    'pending_user_id' => $user->id,
                    'remember_login' => $this->remember,
                ]);

                return redirect()->route('verify-otp');
            } else {
                // 2FA Disabled, Login straight away
                Auth::login($user, $this->remember);
                session()->regenerate();

                return redirect()->intended(route('dashboard'));
            }
        }

        $this->addError('email', 'Barua pepe au nenosiri sio sahihi.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth');
    }
}
