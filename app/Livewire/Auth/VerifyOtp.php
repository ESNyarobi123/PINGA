<?php

namespace App\Livewire\Auth;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class VerifyOtp extends Component
{
    public $otp;

    public $errorMessage = '';

    public $successMessage = '';

    protected $rules = [
        'otp' => 'required|string|size:6',
    ];

    public function mount()
    {
        // Kama hakuna pending session, mrudishe login
        if (! session()->has('pending_user_id')) {
            return redirect()->route('login');
        }
    }

    public function verify()
    {
        $this->validate();
        $this->errorMessage = '';

        $userId = session('pending_user_id');
        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->verifyOtp($this->otp)) {
            Auth::login($user, session('remember_login', false));
            session()->forget(['pending_user_id', 'remember_login']);

            // Kamilisha wasifu kwanza: muajili/mfanyakazi wanaenda onboarding
            if (! $user->onboarding_completed) {
                if ($user->isMteja()) {
                    return redirect()->route('onboarding.mteja');
                }
                if ($user->isWinga()) {
                    return redirect()->route('onboarding.winga');
                }
            }

            return redirect()->intended(route('dashboard'));
        }

        // OTP sio sahihi au imepitwa na wakati
        $this->errorMessage = 'OTP uliyoingiza si sahihi, imeisha muda wake au majaribio yamezidi.';
    }

    public function resend()
    {
        $userId = session('pending_user_id');
        $user = User::find($userId);

        if ($user) {
            $otp = $user->generateOtp();
            Mail::to($user->email)->send(new SendOtpMail($otp));

            $this->successMessage = 'OTP mpya imetumwa kwenye barua pepe yako!';
            $this->errorMessage = '';
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-otp')->layout('layouts.auth');
    }
}
