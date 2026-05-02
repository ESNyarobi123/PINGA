<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.watumiaji') }}" wire:navigate class="p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            {{-- Profile Picture --}}
            <div class="relative">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700">
                @else
                    <div class="w-16 h-16 rounded-full bg-winga-600 flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                @if($user->is_verified)
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center border-2 border-white dark:border-zinc-900">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $user->email }}</p>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">ID: {{ $user->id }} • Joined {{ $user->created_at->diffForHumans() }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($user->is_verified)
                <flux:badge color="green" size="sm">✓ Verified</flux:badge>
            @else
                <flux:badge color="amber" size="sm">⚠ Unverified</flux:badge>
            @endif
            @if($user->suspended_at)
                <flux:badge color="red" size="sm">🚫 Suspended</flux:badge>
            @else
                <flux:badge color="green" size="sm">✓ Active</flux:badge>
            @endif
            <flux:badge color="zinc" size="sm" class="capitalize">{{ $user->role }}</flux:badge>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mb-6 flex flex-wrap gap-2">
        @if(!$user->is_verified)
        <flux:button wire:click="verifyUser" wire:confirm="Verify this user?" size="sm" variant="primary">
            ✓ Verify User
        </flux:button>
        @endif
        <flux:button wire:click="reset2FA" wire:confirm="Reset 2FA for {{ $user->name }}?" size="sm" variant="ghost">
            🔐 Reset 2FA
        </flux:button>
        <flux:dropdown>
            <flux:button size="sm" variant="ghost">Change Role</flux:button>
            <flux:menu>
                <flux:menu.item wire:click="changeRole('winga')" wire:confirm="Change to Winga?">Winga (Worker)</flux:menu.item>
                <flux:menu.item wire:click="changeRole('mteja')" wire:confirm="Change to Mteja?">Mteja (Client)</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
        @if($user->suspended_at)
        <flux:button wire:click="suspendUser" wire:confirm="Activate this user?" size="sm" variant="filled" color="green">
            ✅ Activate Account
        </flux:button>
        @else
        <flux:button wire:click="suspendUser" wire:confirm="Suspend {{ $user->name }}?" size="sm" variant="danger">
            ⏸️ Suspend Account
        </flux:button>
        @endif
    </div>

    {{-- Tabs (role-aware) --}}
    <div class="mb-6 border-b border-zinc-200 dark:border-zinc-700">
        <nav class="flex gap-6 overflow-x-auto">
            <button wire:click="$set('activeTab', 'overview')" 
                    class="pb-3 border-b-2 transition whitespace-nowrap {{ $activeTab === 'overview' ? 'border-winga-600 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
                Overview
            </button>

            @if($user->role === 'winga')
            {{-- Winga: Show Services & Portfolio --}}
            <button wire:click="$set('activeTab', 'services')" 
                    class="pb-3 border-b-2 transition whitespace-nowrap {{ $activeTab === 'services' ? 'border-winga-600 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
                Huduma ({{ $user->services->count() }})
            </button>
            <button wire:click="$set('activeTab', 'portfolio')" 
                    class="pb-3 border-b-2 transition whitespace-nowrap {{ $activeTab === 'portfolio' ? 'border-winga-600 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
                Portfolio ({{ $user->portfolios->count() }})
            </button>
            <button wire:click="$set('activeTab', 'applications')" 
                    class="pb-3 border-b-2 transition whitespace-nowrap {{ $activeTab === 'applications' ? 'border-winga-600 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
                Maombi ({{ $user->applications->count() }})
            </button>
            <button wire:click="$set('activeTab', 'subscription')" 
                    class="pb-3 border-b-2 transition whitespace-nowrap {{ $activeTab === 'subscription' ? 'border-winga-600 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
                Subscription
            </button>
            @else
            {{-- Mteja: Show Jobs Posted --}}
            <button wire:click="$set('activeTab', 'jobs')" 
                    class="pb-3 border-b-2 transition whitespace-nowrap {{ $activeTab === 'jobs' ? 'border-winga-600 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
                Kazi ({{ $user->jobs->count() }})
            </button>
            @endif
        </nav>
    </div>

    {{-- Tab Content --}}
    @if($activeTab === 'overview')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Personal Information --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Personal Information</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Full Name</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Email</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Phone</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->phone ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Role</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white capitalize">{{ $user->role }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Location</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->mkoa ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Registered</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->created_at->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Account Status --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Account Status</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Verification Status</dt>
                    <dd class="text-sm font-medium">
                        @if($user->is_verified)
                            <span class="text-green-600">✓ Verified</span>
                        @else
                            <span class="text-amber-600">⚠ Unverified</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Account Status</dt>
                    <dd class="text-sm font-medium">
                        @if($user->suspended_at)
                            <span class="text-red-600">🚫 Suspended</span>
                        @else
                            <span class="text-green-600">✓ Active</span>
                        @endif
                    </dd>
                </div>
                @if($user->suspended_at)
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Suspended At</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->suspended_at->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Reason</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->suspended_reason ?? 'N/A' }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Wallet Balance</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">TZS {{ number_format($user->wallet_balance ?? 0) }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Onboarding</dt>
                    <dd class="text-sm font-medium">
                        @if($user->onboarding_completed)
                            <span class="text-green-600">✓ Completed</span>
                        @else
                            <span class="text-amber-600">⚠ Incomplete</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Bio --}}
        @if($user->bio)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 md:col-span-2">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Bio</h3>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $user->bio }}</p>
        </div>
        @endif

        {{-- Skills --}}
        @if($user->skills && $user->skills->count() > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 md:col-span-2">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Skills</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($user->skills as $skill)
                    <flux:badge color="zinc">{{ $skill->name }}</flux:badge>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    @if($activeTab === 'jobs')
    {{-- Mteja: Jobs Posted --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
        @if($user->jobs->count() > 0)
        <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @foreach($user->jobs as $job)
            <div class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-zinc-900 dark:text-white">{{ $job->title }}</h4>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ Str::limit($job->description, 100) }}</p>
                        <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-zinc-500">
                            <span>{{ $job->created_at->diffForHumans() }}</span>
                            @if($job->location)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $job->location }}
                            </span>
                            @endif
                            @if($job->budget_min || $job->budget_max)
                            <span class="font-medium text-zinc-700 dark:text-zinc-300">
                                TZS {{ number_format($job->budget_min ?? 0) }}{{ $job->budget_max ? ' - ' . number_format($job->budget_max) : '+' }}
                            </span>
                            @endif
                            <span>{{ $job->applications()->count() }} maombi</span>
                        </div>
                    </div>
                    <flux:badge color="{{ $job->status === 'open' ? 'green' : ($job->status === 'closed' ? 'red' : ($job->status === 'in_progress' ? 'blue' : 'zinc')) }}">
                        {{ $job->status }}
                    </flux:badge>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center text-zinc-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-zinc-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            Mteja huyu hajaweka kazi yoyote bado
        </div>
        @endif
    </div>
    @endif

    @if($activeTab === 'services')
    {{-- Winga: Services / Huduma --}}
    <div class="space-y-4">
        @if($user->services->count() > 0)
            @foreach($user->services as $service)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-semibold text-zinc-900 dark:text-white text-base">{{ $service->title }}</h4>
                                <flux:badge size="sm" color="{{ $service->status === 'active' ? 'green' : ($service->status === 'draft' ? 'amber' : 'zinc') }}">
                                    {{ $service->status ?? 'active' }}
                                </flux:badge>
                            </div>
                            @if($service->category)
                            <p class="text-xs text-winga-600 dark:text-winga-400 font-medium mb-2">{{ $service->category->name }}</p>
                            @endif
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ Str::limit($service->description, 150) }}</p>
                        </div>
                        @if($service->price)
                        <div class="text-right shrink-0">
                            <p class="text-lg font-bold text-zinc-900 dark:text-white">TZS {{ number_format($service->price) }}</p>
                            @if($service->price_type)
                            <p class="text-xs text-zinc-500">{{ $service->price_type }}</p>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- Packages --}}
                    @if($service->packages->count() > 0)
                    <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-2">Packages ({{ $service->packages->count() }})</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            @foreach($service->packages as $package)
                            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-3">
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $package->title }}</p>
                                @if($package->description)
                                <p class="text-xs text-zinc-500 mt-0.5">{{ Str::limit($package->description, 60) }}</p>
                                @endif
                                <p class="text-sm font-bold text-winga-600 dark:text-winga-400 mt-1">TZS {{ number_format($package->price) }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mt-3 flex items-center gap-3 text-xs text-zinc-500">
                        <span>Imewekwa {{ $service->created_at->diffForHumans() }}</span>
                        @if($service->images && count($service->images) > 0)
                        <span>{{ count($service->images) }} picha</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-8 text-center text-zinc-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-zinc-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            Winga huyu hajaweka huduma yoyote bado
        </div>
        @endif
    </div>
    @endif

    @if($activeTab === 'portfolio')
    {{-- Winga: Portfolio --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($user->portfolios as $item)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden hover:shadow-md transition-shadow group">
            @if($item->image_path)
            <div class="aspect-video bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            @else
            <div class="aspect-video bg-linear-to-br from-winga-100 to-winga-200 dark:from-winga-900/30 dark:to-winga-800/30 flex items-center justify-center">
                <svg class="w-10 h-10 text-winga-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            @endif
            <div class="p-4">
                <h4 class="font-semibold text-zinc-900 dark:text-white text-sm">{{ $item->title }}</h4>
                @if($item->description)
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ Str::limit($item->description, 80) }}</p>
                @endif
                <div class="flex items-center justify-between mt-3">
                    <span class="text-xs text-zinc-400">{{ $item->created_at->diffForHumans() }}</span>
                    @if($item->is_featured)
                    <flux:badge size="sm" color="amber">Featured</flux:badge>
                    @endif
                </div>
                @if($item->project_url)
                <a href="{{ $item->project_url }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-xs text-winga-600 hover:text-winga-700 font-medium">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Tazama Mradi
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-8 text-center text-zinc-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-zinc-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Winga huyu hajaweka portfolio yoyote bado
        </div>
        @endforelse
    </div>
    @endif

    @if($activeTab === 'applications')
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
        @if($user->applications->count() > 0)
        <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @foreach($user->applications as $application)
            <div class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-medium text-zinc-900 dark:text-white">{{ $application->job->title ?? 'N/A' }}</h4>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ Str::limit($application->cover_letter ?? 'No cover letter', 100) }}</p>
                        <div class="flex items-center gap-4 mt-2 text-xs text-zinc-500">
                            <span>{{ $application->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <flux:badge color="{{ $application->status === 'hired' ? 'green' : ($application->status === 'rejected' ? 'red' : 'amber') }}">
                        {{ $application->status }}
                    </flux:badge>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center text-zinc-500">No applications yet</div>
        @endif
    </div>
    @endif

    @if($activeTab === 'subscription')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Active Subscription --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Active Subscription</h3>
            @if($user->activeSubscription)
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Plan</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->activeSubscription->subscriptionPlan?->name ?? $user->activeSubscription->planDisplayName() }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Status</dt>
                    <dd class="text-sm font-medium">
                        <flux:badge color="green">{{ $user->activeSubscription->status }}</flux:badge>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-zinc-500 dark:text-zinc-400">Expires At</dt>
                    <dd class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->activeSubscription->expires_at->format('d M Y') }}</dd>
                </div>
            </dl>
            @else
            <p class="text-sm text-zinc-500">No active subscription</p>
            @endif
        </div>

        {{-- Subscription History --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Subscription History</h3>
            @if($user->subscriptions->count() > 0)
            <div class="space-y-3">
                @foreach($user->subscriptions->take(5) as $subscription)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $subscription->subscriptionPlan?->name ?? $subscription->planDisplayName() }}</p>
                        <p class="text-xs text-zinc-500">{{ $subscription->created_at->format('d M Y') }}</p>
                    </div>
                    <flux:badge size="sm" color="{{ $subscription->status === 'active' ? 'green' : 'zinc' }}">
                        {{ $subscription->status }}
                    </flux:badge>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-zinc-500">No subscription history</p>
            @endif
        </div>
    </div>
    @endif
</div>
