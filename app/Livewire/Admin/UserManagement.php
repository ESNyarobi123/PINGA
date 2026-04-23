<?php

namespace App\Livewire\Admin;

use App\Models\AdminAuditLog;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterRole = '';

    public string $filterStatus = '';

    public string $filterSubscription = '';

    public string $filterLocation = '';

    public string $filterVerification = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public array $selectedUsers = [];

    public bool $selectAll = false;

    // Sorting
    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    // Bulk actions
    public string $bulkAction = '';

    public string $bulkMessage = '';

    // User profile modal
    public bool $showProfileModal = false;

    public ?User $selectedUser = null;

    public string $profileTab = 'overview';

    // Wallet actions
    public string $walletAction = '';

    public int $walletAmount = 0;

    public string $walletReason = '';

    // Subscription actions
    public string $subscriptionPlan = '';

    public int $subscriptionDays = 30;

    // Verification actions
    public string $verificationType = '';

    public string $verificationStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRole' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterSubscription' => ['except' => ''],
        'filterLocation' => ['except' => ''],
        'filterVerification' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedUsers = $this->getUsersQuery()->pluck('id')->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function updatedSelectedUsers(): void
    {
        $this->selectAll = false;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    private function getUsersQuery()
    {
        return User::query()
            ->with(['activeSubscription.subscriptionPlan'])
            ->when($this->search, fn ($query) => $query
                ->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                })
            )
            ->when($this->filterRole, fn ($query) => $query->where('role', $this->filterRole))
            ->when($this->filterStatus, fn ($query) => match ($this->filterStatus) {
                'active' => $query->whereNull('suspended_at'),
                'suspended' => $query->whereNotNull('suspended_at'),
                default => $query,
            })
            ->when($this->filterSubscription, fn ($query) => match ($this->filterSubscription) {
                'active' => $query->whereHas('activeSubscription'),
                'expired' => $query->whereHas('subscriptions', fn ($q) => $q->where('status', 'expired')),
                'none' => $query->whereDoesntHave('subscriptions'),
                default => $query,
            })
            ->when($this->filterVerification, fn ($query) => match ($this->filterVerification) {
                'verified' => $query->where('is_verified', true),
                'unverified' => $query->where('is_verified', false),
                default => $query,
            })
            ->when($this->filterLocation, fn ($query) => $query->where('mkoa', $this->filterLocation))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function getUsersProperty()
    {
        return $this->getUsersQuery()->paginate(25);
    }

    public function getTotalUsersProperty(): int
    {
        return User::count();
    }

    public function getActiveUsersProperty(): int
    {
        return User::whereNull('suspended_at')->count();
    }

    public function getVerifiedUsersProperty(): int
    {
        return User::where('is_verified', true)->count();
    }

    public function getPremiumUsersProperty(): int
    {
        return User::whereHas('activeSubscription', fn ($q) => $q->whereHas('subscriptionPlan', fn ($p) => $p->where('slug', '!=', 'msingi')))->count();
    }

    public function openProfile($userId): void
    {
        \Log::info('openProfile called with userId: '.$userId);

        $user = User::findOrFail($userId);
        $this->selectedUser = $user->load([
            'activeSubscription.subscriptionPlan',
            'subscriptions.subscriptionPlan',
            'jobs',
            'applications.job',
            'reviewsGiven',
            'reviewsReceived',
            'sentPayments',
            'disputes',
        ]);
        $this->showProfileModal = true;
        $this->profileTab = 'overview';

        $this->dispatch('toast', message: 'Profile loaded', type: 'success');
    }

    public function closeProfile(): void
    {
        $this->showProfileModal = false;
        $this->selectedUser = null;
        $this->profileTab = 'overview';
        $this->reset(['walletAction', 'walletAmount', 'walletReason', 'subscriptionPlan', 'subscriptionDays']);
    }

    public function executeBulkAction(): void
    {
        $this->validate([
            'bulkAction' => 'required|in:activate,suspend,ban,delete,export,send_message,grant_subscription',
            'selectedUsers' => 'required|array|min:1',
        ]);

        $users = User::whereIn('id', $this->selectedUsers)->get();
        $count = 0;

        match ($this->bulkAction) {
            'activate' => $users->each(fn ($user) => $this->activateUser($user)),
            'suspend' => $users->each(fn ($user) => $this->suspendUser($user)),
            'ban' => $users->each(fn ($user) => $this->banUser($user)),
            'delete' => $users->each(fn ($user) => $this->deleteUser($user)),
            'export' => $this->exportUsers($users),
            'send_message' => $this->sendBulkMessage($users),
            'grant_subscription' => $this->grantBulkSubscription($users),
        };

        $this->dispatch('toast', message: "Action executed on {$users->count()} users", type: 'success');
        $this->reset(['selectedUsers', 'selectAll', 'bulkAction']);
    }

    private function activateUser(User $user): void
    {
        $user->update([
            'suspended_at' => null,
            'suspended_reason' => null,
        ]);
        $this->logAdminAction('activate_user', $user);
    }

    private function suspendUser(User $user): void
    {
        $user->update(['suspended_at' => now()]);
        $this->logAdminAction('suspend_user', $user);
    }

    private function banUser(User $user): void
    {
        $user->update([
            'suspended_at' => now(),
            'suspended_reason' => 'Banned by admin',
        ]);
        $this->logAdminAction('ban_user', $user);
    }

    private function deleteUser(User $user): void
    {
        $this->logAdminAction('delete_user', $user);
        $user->delete();
    }

    private function exportUsers($users): void
    {
        $csv = "ID,Name,Email,Phone,Role,Status,Wallet Balance,Subscription,Verification,Registered,Last Login\n";

        foreach ($users as $user) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $user->id,
                str_replace(',', '', $user->name),
                $user->email,
                $user->phone,
                $user->role,
                $user->suspended_at ? 'Suspended' : 'Active',
                $user->wallet_balance ?? 0,
                $user->activeSubscription?->subscriptionPlan?->name ?? 'None',
                $user->is_verified ? 'Verified' : 'Unverified',
                $user->created_at->format('Y-m-d H:i'),
                ''
            );
        }

        $this->dispatch('download', data: $csv, filename: 'users_export.csv');
    }

    private function sendBulkMessage($users): void
    {
        // TODO: Implement bulk message sending
        $this->logAdminAction('send_bulk_message', null, ['user_count' => $users->count()]);
    }

    private function grantBulkSubscription($users): void
    {
        foreach ($users as $user) {
            $this->grantSubscription($user, 'kawaida', 30);
        }
    }

    public function impersonateUser($userId): void
    {
        $user = User::findOrFail($userId);
        session(['impersonate_admin_id' => auth()->id()]);
        auth()->login($user);

        $this->logAdminAction('impersonate_user', $user);
        $this->dispatch('toast', message: "Now impersonating {$user->name}", type: 'info');

        $this->redirect(route($user->role === 'winga' ? 'winga.dashboard' : 'mteja.dashboard'));
    }

    public function toggleUserStatus($userId, string $action): void
    {
        $user = User::findOrFail($userId);
        match ($action) {
            'activate' => $this->activateUser($user),
            'suspend' => $this->suspendUser($user),
            'ban' => $this->banUser($user),
        };

        $this->dispatch('toast', message: 'User status updated', type: 'success');
    }

    public function executeWalletAction(): void
    {
        $this->validate([
            'walletAction' => 'required|in:credit,debit',
            'walletAmount' => 'required|integer|min:1',
            'walletReason' => 'required|string|max:255',
        ]);

        if ($this->walletAction === 'credit') {
            $this->selectedUser->increment('wallet_balance', $this->walletAmount);
            $this->logAdminAction('credit_wallet', $this->selectedUser, [
                'amount' => $this->walletAmount,
                'reason' => $this->walletReason,
            ]);
            $this->dispatch('toast', message: "Wallet credited with TZS {$this->walletAmount}", type: 'success');
        } else {
            if ($this->selectedUser->wallet_balance < $this->walletAmount) {
                $this->dispatch('toast', message: 'Insufficient wallet balance', type: 'error');

                return;
            }

            $this->selectedUser->decrement('wallet_balance', $this->walletAmount);
            $this->logAdminAction('debit_wallet', $this->selectedUser, [
                'amount' => $this->walletAmount,
                'reason' => $this->walletReason,
            ]);
            $this->dispatch('toast', message: "TZS {$this->walletAmount} debited from wallet", type: 'success');
        }

        $this->reset(['walletAction', 'walletAmount', 'walletReason']);
        $this->selectedUser->refresh();
    }

    public function grantSubscription(): void
    {
        $this->validate([
            'subscriptionPlan' => 'required|in:msingi,kawaida,bora',
            'subscriptionDays' => 'required|integer|min:1|max:365',
        ]);

        $plan = \App\Models\SubscriptionPlan::where('slug', $this->subscriptionPlan)->firstOrFail();

        app(\App\Services\SubscriptionService::class)->activate(
            $this->selectedUser,
            $plan,
            'admin-grant-'.$this->selectedUser->id.'-'.now()->timestamp,
            'admin',
            forceReplace: true
        );

        $this->logAdminAction('grant_subscription', $this->selectedUser, [
            'plan' => $this->subscriptionPlan,
            'days' => $this->subscriptionDays,
        ]);

        $this->dispatch('toast', message: 'Subscription granted', type: 'success');
        $this->reset(['subscriptionPlan', 'subscriptionDays']);
        $this->selectedUser->refresh();
    }

    public function verifyUser(string $status): void
    {
        if ($status === 'approve') {
            $this->selectedUser->update(['is_verified' => true]);
            $this->logAdminAction('approve_verification', $this->selectedUser);
            $this->dispatch('toast', message: 'User verified successfully', type: 'success');
        } else {
            $this->selectedUser->update(['is_verified' => false]);
            $this->logAdminAction('reject_verification', $this->selectedUser);
            $this->dispatch('toast', message: 'Verification rejected', type: 'success');
        }

        $this->selectedUser->refresh();
    }

    public function resetPassword($userId): void
    {
        $user = User::findOrFail($userId);
        $newPassword = \Illuminate\Support\Str::random(8);
        $user->update(['password' => Hash::make($newPassword)]);

        $this->logAdminAction('reset_password', $user);
        $this->dispatch('toast', message: "Password reset to: {$newPassword}", type: 'info');
    }

    public function forceEmailVerification($userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['email_verified_at' => now()]);
        $this->logAdminAction('force_email_verify', $user);
        $this->dispatch('toast', message: 'Email verified', type: 'success');
    }

    public function changeUserRole($userId, string $newRole): void
    {
        $user = User::findOrFail($userId);
        $oldRole = $user->role;
        $user->update(['role' => $newRole]);

        $this->logAdminAction('change_role', $user, [
            'old' => ['role' => $oldRole],
            'new' => ['role' => $newRole],
        ]);
        $this->dispatch('toast', message: "User role changed from {$oldRole} to {$newRole}", type: 'success');

        if ($this->selectedUser && $this->selectedUser->id === $userId) {
            $this->selectedUser->refresh();
        }
    }

    public function reset2FA($userId): void
    {
        $user = User::findOrFail($userId);
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        $this->logAdminAction('reset_2fa', $user);
        $this->dispatch('toast', message: '2FA/OTP reset successfully', type: 'success');
    }

    public function getUserStats(User $user): array
    {
        return [
            'jobs_posted' => $user->jobs()->count(),
            'applications_sent' => $user->applications()->count(),
            'reviews_given' => $user->reviewsGiven()->count(),
            'reviews_received' => $user->reviewsReceived()->count(),
            'disputes_involved' => $user->disputes()->count(),
            'total_earned' => $user->receivedPayments()->where('status', 'released')->sum('worker_amount'),
            'total_spent' => $user->sentPayments()->sum('amount'),
        ];
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.user-management', [
            'users' => $this->users,
            'regions' => User::distinct('mkoa')->whereNotNull('mkoa')->pluck('mkoa'),
            'totalUsers' => $this->totalUsers,
            'activeUsers' => $this->activeUsers,
            'verifiedUsers' => $this->verifiedUsers,
            'premiumUsers' => $this->premiumUsers,
        ])
            ->layout('layouts.admin')
            ->title('User Management');
    }
}
