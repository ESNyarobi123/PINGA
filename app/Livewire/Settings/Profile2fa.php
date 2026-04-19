<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class Profile2fa extends Component
{
    public $confirmingDisable = false;

    // Hatua ya kuomba uthibitisho wa kuzima
    public function confirmDisable()
    {
        $this->confirmingDisable = true;
    }

    // Mtumiaji akithibitisha kuizima 2FA
    public function disable2FA()
    {
        auth()->user()->update(['two_factor_enabled' => false]);
        $this->confirmingDisable = false;

        session()->flash('success', 'Uthibitisho wa 2FA Umezimwa Kikamilifu.');
    }

    // Mtumiaji akiwezesha moja kwa moja
    public function enable2FA()
    {
        auth()->user()->update(['two_factor_enabled' => true]);

        session()->flash('success', 'Uthibitisho wa 2FA Umewezeshwa Kikamilifu!');
    }

    public function render()
    {
        return view('livewire.settings.profile-2fa');
    }
}
