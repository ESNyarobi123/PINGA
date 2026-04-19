<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Watumiaji extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = 'all'; // all, winga, mteja
    public string $statusFilter = ''; // new, unverified, high_rated, complains

    // Bulk actions
    public array $selectedUsers = [];

    public bool $selectAll = false;

    // Add User Modal
    public bool $showAddUserModal = false;
    public string $newUserName = '';
    public string $newUserEmail = '';
    public string $newUserPhone = '';
    public string $newUserRole = 'winga';
    public string $newUserPassword = '';

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = User::pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function verifyUser($userId): void
    {
        $user = User::findOrFail($userId);

        $user->update(['is_verified' => true]);

        Log::info("Admin verified user ID: {$userId}");
        $this->dispatch('toast', message: 'Mtumiaji amethibitishwa kikamilifu (NIDA/VETA).', type: 'success');
    }

    public function unverifyUser($userId): void
    {
        $user = User::findOrFail($userId);

        $user->update(['is_verified' => false]);

        Log::info("Admin unverified user ID: {$userId}");
        $this->dispatch('toast', message: 'Uthibitisho umefutwa.', type: 'warning');
    }

    public function suspendUser($userId, string $reason = ''): void
    {
        $user = User::findOrFail($userId);

        if ($user->suspended_at) {
            $user->update([
                'suspended_at' => null,
                'suspended_reason' => null,
            ]);

            Log::info("Admin unsuspended user ID: {$userId}");
            $this->dispatch('toast', message: 'Akaunti imerudishwa.', type: 'success');

            return;
        }

        $user->update([
            'suspended_at' => now(),
            'suspended_reason' => $reason ?: 'Imesitishwa na msimamizi',
        ]);

        Log::info("Admin suspended user ID: {$userId}", ['reason' => $reason]);
        $this->dispatch('toast', message: 'Akaunti imesitishwa kwa muda.', type: 'warning');
    }

    public function reset2FA($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
        
        Log::info("Admin reset 2FA for user ID: {$userId}");
        $this->dispatch('toast', message: 'OTP 2FA imewekwa upya kwa mtumiaji.', type: 'success');
    }

    public function enable2FA($userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['two_factor_enabled' => true]);
        
        Log::info("Admin enabled 2FA for user ID: {$userId}");
        $this->dispatch('toast', message: '2FA/OTP imewezeshwa.', type: 'success');
    }

    public function disable2FA($userId): void
    {
        $user = User::findOrFail($userId);
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
        
        Log::info("Admin disabled 2FA for user ID: {$userId}");
        $this->dispatch('toast', message: '2FA/OTP imezimwa.', type: 'warning');
    }

    public function openProfile($userId): void
    {
        Log::info("Admin viewing profile for user ID: {$userId}");
        
        // Redirect to admin user detail page
        $this->redirect(route('admin.watumiaji.detail', $userId), navigate: true);
    }

    public function changeRole($userId, string $newRole): void
    {
        $user = User::findOrFail($userId);
        $oldRole = $user->role;
        $user->update(['role' => $newRole]);
        
        Log::info("Admin changed role for user ID: {$userId}", ['old' => $oldRole, 'new' => $newRole]);
        $this->dispatch('toast', message: "Jukumu limebadilishwa kutoka {$oldRole} kwenda {$newRole}.", type: 'success');
    }

    public function openAddUserModal(): void
    {
        $this->showAddUserModal = true;
        $this->reset(['newUserName', 'newUserEmail', 'newUserPhone', 'newUserRole', 'newUserPassword']);
        $this->newUserRole = 'winga';
    }

    public function closeAddUserModal(): void
    {
        $this->showAddUserModal = false;
        $this->reset(['newUserName', 'newUserEmail', 'newUserPhone', 'newUserRole', 'newUserPassword']);
    }

    public function createUser(): void
    {
        $this->validate([
            'newUserName' => 'required|string|max:255',
            'newUserEmail' => 'required|email|unique:users,email',
            'newUserPhone' => 'nullable|string|max:20',
            'newUserRole' => 'required|in:winga,mteja',
            'newUserPassword' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $this->newUserName,
            'email' => $this->newUserEmail,
            'phone' => $this->newUserPhone,
            'role' => $this->newUserRole,
            'password' => \Hash::make($this->newUserPassword),
            'email_verified_at' => now(),
        ]);

        Log::info("Admin created new user ID: {$user->id}");
        $this->dispatch('toast', message: "Mtumiaji mpya ameongezwa kikamilifu!", type: 'success');
        
        $this->closeAddUserModal();
    }

    public function render()
    {
        $query = User::query()
            ->with(['reviewsReceived'])
            ->withCount('jobs')
            ->where('role', '!=', 'admin')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->when($this->roleFilter !== 'all', fn ($q) => $q->where('role', $this->roleFilter))
            ->when($this->statusFilter !== '', fn ($q) => match ($this->statusFilter) {
                'new' => $q->where('created_at', '>=', now()->subDays(7)),
                'unverified' => $q->where('onboarding_completed', false),
                'high_rated' => $q->whereHas('applications', fn($q) => $q->where('status', 'hired')),
                'complaints' => $q->whereHas('disputes'),
                default => $q,
            })
            ->orderByDesc('created_at');

        $wingaCount   = User::where('role', 'winga')->count();
        $mtejCount    = User::where('role', 'mteja')->count();

        $users = $query->latest()->paginate(15);

        return view('livewire.admin.watumiaji', [
            'users'       => $users,
            'wingaCount'  => $wingaCount,
            'mtejCount'   => $mtejCount,
        ])->layout('layouts.admin')->title('Watumiaji | Usimamizi');
    }
}
