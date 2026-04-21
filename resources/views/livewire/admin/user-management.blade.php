<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">User Management</h1>
            <p class="text-zinc-500 dark:text-zinc-400">Manage all platform users with full control</p>
        </div>
        <div class="flex items-center gap-3">
            @if(count($selectedUsers) > 0)
            <div class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                {{ count($selectedUsers) }} Selected
            </div>
            @endif
            <button wire:click="$refresh" 
                    onclick="console.log('Button clicked!'); alert('Testing Livewire');"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                🔄 Test Livewire
            </button>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">Total</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-zinc-500">All Users</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">Active</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($activeUsers) }}</p>
            <p class="text-xs text-zinc-500">Active Users</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">Verified</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($verifiedUsers) }}</p>
            <p class="text-xs text-zinc-500">Verified Users</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">Premium</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($premiumUsers) }}</p>
            <p class="text-xs text-zinc-500">Premium Users</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Search users..."
                   class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">

            <select wire:model.live="filterRole" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">All Roles</option>
                <option value="mfanyakazi">Wingas</option>
                <option value="muajili">Wateja</option>
                <option value="admin">Admin</option>
            </select>

            <select wire:model.live="filterStatus" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
                <option value="banned">Banned</option>
                <option value="inactive">Inactive</option>
            </select>

            <select wire:model.live="filterSubscription" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">All Subscriptions</option>
                <option value="active">Active</option>
                <option value="expired">Expired</option>
                <option value="none">None</option>
            </select>

            <select wire:model.live="filterVerification" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">All Verification</option>
                <option value="verified">Verified</option>
                <option value="pending">Pending</option>
                <option value="unverified">Unverified</option>
            </select>

            <select wire:model.live="filterLocation" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">All Regions</option>
                @foreach($regions as $region)
                <option value="{{ $region }}">{{ $region }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <input wire:model.live="dateFrom" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <input wire:model.live="dateTo" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-4">
                <select wire:model.live="bulkAction" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                    <option value="">Bulk Actions</option>
                    <option value="activate">✅ Activate</option>
                    <option value="suspend">🚫 Suspend</option>
                    <option value="ban">❌ Ban</option>
                    <option value="delete">🗑️ Delete</option>
                    <option value="export">📤 Export CSV</option>
                    <option value="send_message">📧 Send Message</option>
                    <option value="grant_subscription">⭐ Grant Subscription</option>
                </select>
                
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model.live="selectAll" class="rounded">
                    <span>Select All ({{ $users->total() }})</span>
                </label>
            </div>

            <div class="text-sm text-zinc-500">
                Showing {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }}
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('name')">
                            User
                            @if($sortField === 'name')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Verification</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Wallet</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Subscription</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            Registered
                            @if($sortField === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($users as $user)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" 
                                   wire:model.live="selectedUsers" 
                                   value="{{ $user->id }}"
                                   class="rounded">
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0d9488&color=fff&size=40' }}"
                                     alt="{{ $user->name }}"
                                     class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-zinc-500">{{ $user->email }}</p>
                                    @if($user->business_name)
                                    <p class="text-xs text-zinc-400">{{ $user->business_name }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-bold rounded-lg
                                {{ $user->role === 'mfanyakazi' ? 'bg-blue-100 text-blue-700' :
                                   ($user->role === 'muajili' ? 'bg-green-100 text-green-700' :
                                   'bg-purple-100 text-purple-700') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                            <div>{{ $user->phone ?? '—' }}</div>
                            @if($user->whatsapp)
                            <span class="text-xs text-green-600">📱</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($user->verified_at)
                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-green-100 text-green-700">
                                ✓ Verified
                            </span>
                            @elseif($user->verificationDocuments->count() > 0)
                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-amber-100 text-amber-700">
                                ⏳ Pending
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-zinc-100 text-zinc-500">
                                Unverified
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-white">
                            TZS {{ number_format($user->wallet?->balance ?? 0) }}
                        </td>
                        <td class="px-4 py-3">
                            @if($user->activeSubscription)
                            <div>
                                <span class="px-2 py-1 text-xs font-bold rounded-lg
                                    {{ $user->activeSubscription->subscriptionPlan?->slug === 'bora' ? 'bg-amber-100 text-amber-700' :
                                       ($user->activeSubscription->subscriptionPlan?->slug === 'kawaida' ? 'bg-sky-100 text-sky-700' :
                                       'bg-zinc-100 text-zinc-700') }}">
                                    {{ $user->activeSubscription->subscriptionPlan?->name ?? $user->activeSubscription->planDisplayName() }}
                                </span>
                                <p class="text-xs text-zinc-500 mt-1">
                                    {{ $user->activeSubscription->expires_at->diffForHumans() }}
                                </p>
                            </div>
                            @else
                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-zinc-100 text-zinc-500">
                                None
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($user->suspended_at && $user->suspended_reason === 'Banned by admin')
                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-red-100 text-red-700">
                                Banned
                            </span>
                            @elseif($user->suspended_at)
                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-amber-100 text-amber-700">
                                Suspended
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-green-100 text-green-700">
                                Active
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $user->created_at->format('d M Y') }}
                            <p class="text-xs">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button wire:click="openProfile({{ $user->id }})"
                                        title="View Profile"
                                        class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                                    👁️
                                </button>
                                <button wire:click="forceEmailVerification({{ $user->id }})"
                                        wire:confirm="Verify {{ $user->name }}'s account?"
                                        title="Verify ID/Data"
                                        class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition">
                                    ✓
                                </button>
                                <button wire:click="reset2FA({{ $user->id }})"
                                        wire:confirm="Reset 2FA/OTP for {{ $user->name }}?"
                                        title="Reset 2FA"
                                        class="px-2 py-1 text-xs bg-purple-600 hover:bg-purple-700 text-white rounded transition">
                                    🔐
                                </button>
                                @if($user->suspended_at)
                                <button wire:click="toggleUserStatus({{ $user->id }}, 'activate')"
                                        wire:confirm="Activate {{ $user->name }}?"
                                        title="Activate"
                                        class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition">
                                    ✅
                                </button>
                                @else
                                <button wire:click="toggleUserStatus({{ $user->id }}, 'suspend')"
                                        wire:confirm="Suspend {{ $user->name }}?"
                                        title="Suspend"
                                        class="px-2 py-1 text-xs bg-amber-600 hover:bg-amber-700 text-white rounded transition">
                                    ⏸️
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-16 text-center text-zinc-400">
                            <div class="text-4xl mb-3">👥</div>
                            <p class="font-medium">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- User Profile Modal --}}
    @if($showProfileModal && $selectedUser)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <img src="{{ $selectedUser->avatar ? asset('storage/'.$selectedUser->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($selectedUser->name).'&background=0d9488&color=fff&size=48' }}"
                         alt="{{ $selectedUser->name }}"
                         class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $selectedUser->name }}</h2>
                        <p class="text-sm text-zinc-500">{{ $selectedUser->email }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-1 text-xs font-bold rounded-lg
                                {{ $selectedUser->role === 'mfanyakazi' ? 'bg-blue-100 text-blue-700' :
                                   ($selectedUser->role === 'muajili' ? 'bg-green-100 text-green-700' :
                                   'bg-purple-100 text-purple-700') }}">
                                {{ ucfirst($selectedUser->role) }}
                            </span>
                            @if($selectedUser->verified_at)
                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-green-100 text-green-700">
                                ✓ Verified
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="impersonateUser({{ $selectedUser->id }})"
                            wire:confirm="Impersonate this user?"
                            class="px-3 py-1.5 text-sm bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                        👤 Impersonate
                    </button>
                    <button wire:click="closeProfile"
                            class="text-zinc-400 hover:text-zinc-600 transition">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="border-b border-zinc-200 dark:border-zinc-800">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button wire:click="$set('profileTab', 'overview')"
                            class="{{ $profileTab === 'overview' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Overview
                    </button>
                    <button wire:click="$set('profileTab', 'activity')"
                            class="{{ $profileTab === 'activity' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Activity
                    </button>
                    <button wire:click="$set('profileTab', 'financials')"
                            class="{{ $profileTab === 'financials' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Financials
                    </button>
                    <button wire:click="$set('profileTab', 'subscription')"
                            class="{{ $profileTab === 'subscription' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Subscription
                    </button>
                    <button wire:click="$set('profileTab', 'verification')"
                            class="{{ $profileTab === 'verification' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Verification
                    </button>
                    <button wire:click="$set('profileTab', 'danger')"
                            class="{{ $profileTab === 'danger' ? 'border-red-500 text-red-600' : 'border-transparent text-zinc-500 hover:text-red-700' }} 
                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Danger Zone
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="p-6 overflow-y-auto max-h-[60vh]">
                @switch($profileTab)
                    case 'overview'
                        <div class="space-y-6">
                            {{-- Personal Info --}}
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Personal Information</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name</label>
                                        <p class="text-zinc-900 dark:text-white">{{ $selectedUser->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Email</label>
                                        <p class="text-zinc-900 dark:text-white">{{ $selectedUser->email }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Phone</label>
                                        <p class="text-zinc-900 dark:text-white">{{ $selectedUser->phone ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Business</label>
                                        <p class="text-zinc-900 dark:text-white">{{ $selectedUser->business_name ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Location</label>
                                        <p class="text-zinc-900 dark:text-white">{{ $selectedUser->mkoa ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Member Since</label>
                                        <p class="text-zinc-900 dark:text-white">{{ $selectedUser->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Account Status --}}
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Account Status</h3>
                                <div class="flex items-center gap-4">
                                    @if($selectedUser->banned_at)
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-bold">Banned</span>
                                    @elseif($selectedUser->suspended_at)
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-sm font-bold">Suspended</span>
                                    @else
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-bold">Active</span>
                                    @endif
                                    
                                    <div class="flex gap-2">
                                        <button wire:click="toggleUserStatus({{ $selectedUser->id }}, 'activate')"
                                                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-sm transition">
                                            ✅ Activate
                                        </button>
                                        <button wire:click="toggleUserStatus({{ $selectedUser->id }}, 'suspend')"
                                                class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded text-sm transition">
                                            ⏸️ Suspend
                                        </button>
                                        <button wire:click="toggleUserStatus({{ $selectedUser->id }}, 'ban')"
                                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm transition">
                                            ❌ Ban
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Wallet Actions --}}
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Wallet Management</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                        <div>
                                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                                                TZS {{ number_format($selectedUser->wallet?->balance ?? 0) }}
                                            </p>
                                            <p class="text-sm text-zinc-500">Current Balance</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Action</label>
                                            <select wire:model.live="walletAction" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                                <option value="">Select action</option>
                                                <option value="credit">Credit</option>
                                                <option value="debit">Debit</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Amount (TZS)</label>
                                            <input wire:model.live="walletAmount" type="number" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Reason</label>
                                        <input wire:model.live="walletReason" type="text" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                    </div>
                                    
                                    <button wire:click="executeWalletAction"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                        Execute Wallet Action
                                    </button>
                                </div>
                            </div>
                        </div>

                    case 'activity'
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">User Activity</h3>
                            
                            @php $stats = $this->getUserStats($selectedUser); @endphp
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['jobs_posted'] }}</p>
                                    <p class="text-sm text-zinc-500">Jobs Posted</p>
                                </div>
                                <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['applications_sent'] }}</p>
                                    <p class="text-sm text-zinc-500">Applications</p>
                                </div>
                                <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['reviews_received'] }}</p>
                                    <p class="text-sm text-zinc-500">Reviews</p>
                                </div>
                                <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['disputes_involved'] }}</p>
                                    <p class="text-sm text-zinc-500">Disputes</p>
                                </div>
                            </div>

                            {{-- Activity Logs --}}
                            <div>
                                <h4 class="font-medium text-zinc-900 dark:text-white mb-2">Recent Activity</h4>
                                <div class="space-y-2">
                                    @forelse($selectedUser->activityLogs->take(20) as $log)
                                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-zinc-900 dark:text-white">{{ $log->action }}</span>
                                            <span class="text-sm text-zinc-500">{{ $log->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($log->description)
                                        <p class="text-sm text-zinc-600">{{ $log->description }}</p>
                                        @endif
                                    </div>
                                    @empty
                                    <p class="text-zinc-500">No recent activity</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    case 'financials'
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Financial Summary</h3>
                            
                            @php $stats = $this->getUserStats($selectedUser); @endphp
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <p class="text-2xl font-bold text-green-600">TZS {{ number_format($stats['total_earned']) }}</p>
                                    <p class="text-sm text-green-700">Total Earned</p>
                                </div>
                                <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <p class="text-2xl font-bold text-red-600">TZS {{ number_format($stats['total_spent']) }}</p>
                                    <p class="text-sm text-red-700">Total Spent</p>
                                </div>
                            </div>
                            
                            {{-- Transaction History --}}
                            <div>
                                <h4 class="font-medium text-zinc-900 dark:text-white mb-2">Transaction History</h4>
                                <div class="space-y-2">
                                    @forelse($selectedUser->payments->take(20) as $payment)
                                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-zinc-900 dark:text-white">{{ $payment->type }}</span>
                                            <span class="text-sm font-bold {{ $payment->type === 'payout' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $payment->type === 'payout' ? '+' : '-' }}TZS {{ number_format($payment->amount) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-zinc-500">{{ $payment->created_at->format('d M Y H:i') }}</p>
                                        @if($payment->description)
                                        <p class="text-xs text-zinc-400">{{ $payment->description }}</p>
                                        @endif
                                    </div>
                                    @empty
                                    <p class="text-zinc-500">No transactions</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    case 'subscription'
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Subscription Management</h3>
                            
                            {{-- Current Subscription --}}
                            @if($selectedUser->activeSubscription)
                            <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                <h4 class="font-medium text-zinc-900 dark:text-white mb-2">Current Plan</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm text-zinc-500">Plan</label>
                                        <p class="font-medium">{{ $selectedUser->activeSubscription->subscriptionPlan?->name ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm text-zinc-500">Expires</label>
                                        <p class="font-medium">{{ $selectedUser->activeSubscription->expires_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                <p class="text-zinc-500">No active subscription</p>
                            </div>
                            @endif

                            {{-- Grant Subscription --}}
                            <div>
                                <h4 class="font-medium text-zinc-900 dark:text-white mb-2">Grant Subscription</h4>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Plan</label>
                                            <select wire:model.live="subscriptionPlan" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                                <option value="">Select Plan</option>
                                                <option value="msingi">Msingi</option>
                                                <option value="kawaida">Kawaida</option>
                                                <option value="bora">Bora</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Days</label>
                                            <input wire:model.live="subscriptionDays" type="number" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                        </div>
                                    </div>
                                    <button wire:click="grantSubscription"
                                            class="px-4 py-2 bg-winga-600 hover:bg-winga-700 text-white rounded-lg text-sm transition">
                                        ⭐ Grant Subscription
                                    </button>
                                </div>
                            </div>

                            {{-- Subscription History --}}
                            <div>
                                <h4 class="font-medium text-zinc-900 dark:text-white mb-2">History</h4>
                                <div class="space-y-2">
                                    @forelse($selectedUser->subscriptions->take(10) as $sub)
                                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium">{{ $sub->subscriptionPlan?->name ?? $sub->planDisplayName() }}</span>
                                            <span class="text-sm {{ $sub->status === 'active' ? 'text-green-600' : 'text-zinc-500' }}">
                                                {{ $sub->status }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-zinc-500">
                                            {{ $sub->starts_at->format('d M Y') }} - {{ $sub->expires_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    @empty
                                    <p class="text-zinc-500">No subscription history</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    case 'verification'
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Verification Status</h3>
                            
                            <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                @if($selectedUser->verified_at)
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-bold text-green-600">Verified</p>
                                    <p class="text-sm text-zinc-500">Verified on {{ $selectedUser->verified_at->format('d M Y') }}</p>
                                </div>
                                @else
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-bold text-amber-600">Not Verified</p>
                                    <p class="text-sm text-zinc-500">User has not been verified yet</p>
                                </div>
                                @endif
                            </div>

                            {{-- Verification Documents --}}
                            <div>
                                <h4 class="font-medium text-zinc-900 dark:text-white mb-2">Verification Documents</h4>
                                <div class="space-y-2">
                                    @forelse($selectedUser->verificationDocuments as $doc)
                                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium">{{ $doc->type }}</span>
                                            <span class="text-sm text-zinc-500">{{ $doc->created_at->format('d M Y') }}</span>
                                        </div>
                                        <p class="text-sm text-zinc-600">{{ $doc->notes }}</p>
                                    </div>
                                    @empty
                                    <p class="text-zinc-500">No verification documents</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Verification Actions --}}
                            @if(!$selectedUser->verified_at)
                            <div class="flex gap-2">
                                <button wire:click="verifyUser('approve')"
                                        wire:confirm="Approve this user's verification?"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                                    ✓ Approve
                                </button>
                                <button wire:click="verifyUser('reject')"
                                        wire:confirm="Reject this user's verification?"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition">
                                    ❌ Reject
                                </button>
                            </div>
                            @endif
                        </div>

                    case 'danger'
                        <div class="space-y-6 border-2 border-red-200 dark:border-red-800 rounded-lg p-6">
                            <h3 class="text-lg font-bold text-red-600">⚠️ Danger Zone</h3>
                            <p class="text-sm text-zinc-600">These actions are irreversible and will affect the user's access to the platform.</p>
                            
                            <div class="space-y-4">
                                {{-- Reset Password --}}
                                <div class="flex items-center justify-between p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-zinc-900 dark:text-white">Reset Password</h4>
                                        <p class="text-sm text-zinc-500">Force user to reset their password on next login</p>
                                    </div>
                                    <button wire:click="resetPassword({{ $selectedUser->id }})"
                                            wire:confirm="Reset this user's password?"
                                            class="px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm transition">
                                        🔑 Reset
                                    </button>
                                </div>

                                {{-- Force Email Verification --}}
                                <div class="flex items-center justify-between p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-zinc-900 dark:text-white">Force Email Verification</h4>
                                        <p class="text-sm text-zinc-500">Mark email as verified without user action</p>
                                    </div>
                                    <button wire:click="forceEmailVerification({{ $selectedUser->id }})"
                                            wire:confirm="Force verify this user's email?"
                                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                        📧 Verify
                                    </button>
                                </div>

                                {{-- Delete User --}}
                                <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-red-600">Delete User</h4>
                                        <p class="text-sm text-red-500">Permanently delete user and all associated data</p>
                                    </div>
                                    <button wire:click="toggleUserStatus({{ $selectedUser->id }}, 'delete')"
                                            wire:confirm="PERMANENTLY delete this user? This cannot be undone!"
                                            class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition">
                                        🗑️ Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                @endswitch
            </div>
        </div>
    </div>
    @endif
</div>
                                    <button class="px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm transition">
                                        Clear Sessions
                                    </button>
                                </div>

                                {{-- Verify Email/Phone --}}
                                <div class="flex items-center justify-between p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-zinc-900 dark:text-white">Manual Verification</h4>
                                        <p class="text-sm text-zinc-500">Manually verify email and phone</p>
                                    </div>
                                    <button class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                                        Verify
                                    </button>
                                </div>

                                {{-- Delete Account --}}
                                <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <div>
                                        <h4 class="font-medium text-red-600">Delete Account</h4>
                                        <p class="text-sm text-red-500">Permanently delete user account and data</p>
                                    </div>
                                    <button wire:click="deleteUser({{ $selectedUser->id }})"
                                            wire:confirm="Are you sure you want to permanently delete this user? This action cannot be undone."
                                            class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                @endswitch
            </div>
        </div>
    </div>
    @endif
</div>
