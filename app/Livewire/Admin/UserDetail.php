<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class UserDetail extends Component
{
    public User $user;

    public string $activeTab = 'overview';

    public function mount($id)
    {
        $this->user = User::with([
            'activeSubscription.subscriptionPlan',
            'subscriptions.subscriptionPlan',
            'jobs',
            'applications.job',
            'services.category',
            'services.packages',
            'portfolios',
            'skills',
            'reviewsGiven',
            'reviewsReceived',
        ])->findOrFail($id);
    }

    public function verifyUser(): void
    {
        $this->user->update(['is_verified' => true]);
        $this->dispatch('toast', message: 'Mtumiaji amethibitishwa kikamilifu.', type: 'success');
        $this->user->refresh();
    }

    public function suspendUser(): void
    {
        if ($this->user->suspended_at) {
            $this->user->update([
                'suspended_at' => null,
                'suspended_reason' => null,
            ]);
            $this->dispatch('toast', message: 'Akaunti imerudishwa.', type: 'success');
        } else {
            $this->user->update([
                'suspended_at' => now(),
                'suspended_reason' => 'Imesitishwa na msimamizi',
            ]);
            $this->dispatch('toast', message: 'Akaunti imesitishwa.', type: 'warning');
        }
        $this->user->refresh();
    }

    public function reset2FA(): void
    {
        $this->user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
        $this->dispatch('toast', message: 'OTP 2FA imewekwa upya.', type: 'success');
    }

    public function changeRole(string $newRole): void
    {
        $oldRole = $this->user->role;
        $this->user->update(['role' => $newRole]);
        $this->dispatch('toast', message: "Jukumu limebadilishwa kutoka {$oldRole} kwenda {$newRole}.", type: 'success');
        $this->user->refresh();
    }

    public function render()
    {
        return view('livewire.admin.user-detail')
            ->layout('layouts.admin')
            ->title('User Details - '.$this->user->name);
    }
}
