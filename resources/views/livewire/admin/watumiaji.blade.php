<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" class="flex items-center gap-2">{{ __('messages.admin_users.title') }} <flux:badge size="sm" color="blue">{{ $users->total() }}</flux:badge></flux:heading>
            <flux:subheading>{{ __('messages.admin_users.subtitle') }}</flux:subheading>
        </div>
        
        <div class="flex items-center gap-2">
            @if(count($selectedUsers) > 0)
                <flux:dropdown>
                    <flux:button variant="filled" color="zinc" icon-trailing="chevron-down">{{ __('messages.admin_users.bulk_action') }} ({{ count($selectedUsers) }})</flux:button>
                    <flux:menu>
                        <flux:menu.item icon="check-badge" wire:click="$dispatch('toast', {message: 'Wamethibitishwa!'})">{{ __('messages.admin_users.verify_nida') }}</flux:menu.item>
                        <flux:menu.item icon="exclamation-triangle" color="red">{{ __('messages.admin_users.suspend_account') }}</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            @endif
            <flux:button icon="plus" variant="primary" wire:click="openAddUserModal">{{ __('messages.admin_users.add_user') }}</flux:button>
        </div>
    </div>

    {{-- Add User Modal --}}
    <flux:modal name="add-user" variant="flyout" class="space-y-6" wire:model="showAddUserModal">
        <div>
            <flux:heading size="lg">Add New User</flux:heading>
            <flux:subheading>Create a new user account</flux:subheading>
        </div>

        <form wire:submit="createUser" class="space-y-4">
            <flux:input wire:model="newUserName" label="Full Name" placeholder="Enter full name" required />
            <flux:input wire:model="newUserEmail" type="email" label="Email Address" placeholder="user@example.com" required />
            <flux:input wire:model="newUserPhone" type="tel" label="Phone Number" placeholder="+255..." />
            <flux:select wire:model="newUserRole" label="Role" required>
                <option value="winga">Winga (Worker)</option>
                <option value="mteja">Mteja (Client)</option>
            </flux:select>
            <flux:input wire:model="newUserPassword" type="password" label="Password" placeholder="Minimum 8 characters" required />

            <div class="flex gap-2 justify-end pt-4">
                <flux:button type="button" variant="ghost" wire:click="closeAddUserModal">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Create User</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Role Tabs --}}
    <div class="flex items-center gap-2 mb-5 p-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl w-fit">
        @foreach(['all' => ['label' => __('messages.admin_users.all'), 'count' => $wingaCount + $mtejCount, 'icon' => '👥'], 'winga' => ['label' => __('messages.admin_users.winga'), 'count' => $wingaCount, 'icon' => '🛠️'], 'mteja' => ['label' => __('messages.admin_users.clients'), 'count' => $mtejCount, 'icon' => '💼']] as $key => $tab)
        <button wire:click="$set('roleFilter', '{{ $key }}')"
            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition
                {{ $roleFilter === $key ? 'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
            <span>{{ $tab['icon'] }}</span>
            <span>{{ $tab['label'] }}</span>
            <span class="text-xs px-1.5 py-0.5 rounded-full {{ $roleFilter === $key ? 'bg-winga-500 text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400' }}">{{ $tab['count'] }}</span>
        </button>
        @endforeach
    </div>

    {{-- Filters & Search --}}
    <div class="flex gap-4 mb-6">
        <div class="w-full max-w-sm">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="{{ __('messages.admin_users.search_placeholder') }}" />
        </div>
        <div class="w-48">
            <flux:select wire:model.live="roleFilter" placeholder="{{ __('messages.admin_users.filter_by_type') }}">
                <flux:select.option value="">{{ __('messages.admin_users.all') }}</flux:select.option>
                <flux:select.option value="mteja">{{ __('messages.admin_users.client') }}</flux:select.option>
                <flux:select.option value="winga">{{ __('messages.admin_users.winga') }}</flux:select.option>
            </flux:select>
        </div>
        <div class="w-56">
            <flux:select wire:model.live="statusFilter" placeholder="{{ __('messages.admin_users.other_filters') }}">
                <flux:select.option value="">{{ __('messages.admin_users.all') }}</flux:select.option>
                <flux:select.option value="new">{{ __('messages.admin_users.new_users') }}</flux:select.option>
                <flux:select.option value="unverified">{{ __('messages.admin_users.unverified') }}</flux:select.option>
                <flux:select.option value="high_rated">{{ __('messages.admin_users.high_rated') }}</flux:select.option>
                <flux:select.option value="complaints">{{ __('messages.admin_users.with_complaints') }}</flux:select.option>
            </flux:select>
        </div>
    </div>

    {{-- Table --}}
    <flux:table class="whitespace-nowrap">
        <flux:table.columns>
            <flux:table.column>
                <flux:checkbox wire:model.live="selectAll" />
            </flux:table.column>
            <flux:table.column>{{ __('messages.admin_users.name') }}</flux:table.column>
            <flux:table.column>{{ __('messages.admin_users.contact') }}</flux:table.column>
            <flux:table.column>{{ __('messages.admin_users.role') }}</flux:table.column>
            <flux:table.column>{{ __('messages.admin_users.verification') }}</flux:table.column>
            <flux:table.column>{{ __('messages.admin_users.rating_jobs') }}</flux:table.column>
            <flux:table.column>{{ __('messages.admin_users.status') }}</flux:table.column>
            <flux:table.column align="end">{{ __('messages.admin_users.actions') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($users as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell>
                        <flux:checkbox wire:model.live="selectedUsers" :value="(string)$user->id" />
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="flex items-center gap-3">
                            <flux:avatar size="sm" :name="$user->name" :initials="$user->initials()"/>
                            <div class="min-w-0">
                                <p class="font-medium text-zinc-900 dark:text-zinc-100 truncate w-32 md:w-auto">{{ $user->name }}</p>
                                <p class="text-xs text-zinc-500">{{ $user->location ?? $user->mkoa ?? 'Unknown' }}</p>
                            </div>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <p class="text-sm font-medium">{{ $user->phone }}</p>
                        <p class="text-xs text-zinc-500">{{ $user->email }}</p>
                    </flux:table.cell>

                    <flux:table.cell>
                        @if($user->role === 'admin')
                            <flux:badge size="sm" color="red">Admin</flux:badge>
                        @elseif($user->role === 'mteja')
                            <flux:badge size="sm" color="blue">Mteja</flux:badge>
                        @else
                            <flux:badge size="sm" color="green" icon="wrench">Winga</flux:badge>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        @if($user->isVerified())
                            <flux:badge size="sm" color="green" icon="shield-check">{{ __('messages.admin_users.verified') }}</flux:badge>
                        @else
                            <flux:badge size="sm" color="zinc">{{ __('messages.admin_users.unverified') }}</flux:badge>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="flex items-center gap-1.5 font-semibold text-accent-orange-500 text-sm">
                            ⭐ {{ number_format($user->averageRating(), 1) }}
                        </div>
                        <p class="text-xs text-zinc-500 mt-0.5">{{ $user->jobs_count ?? $user->jobs()->count() }} {{ __('messages.admin_users.past_jobs') }}</p>
                    </flux:table.cell>

                    <flux:table.cell>
                        @if($user->suspended_at)
                            <flux:badge size="sm" color="red" inset="top bottom">Suspended</flux:badge>
                        @else
                            <flux:badge size="sm" color="green" inset="top bottom">Active</flux:badge>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell align="end">
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                            <flux:menu class="w-56">
                                <flux:menu.item icon="eye" wire:click="openProfile({{ $user->id }})">{{ __('messages.admin_users.view_profile') }}</flux:menu.item>
                                <flux:menu.separator />
                                @if($user->isVerified())
                                    <flux:menu.item icon="shield-exclamation" wire:click="unverifyUser({{ $user->id }})" wire:confirm="Remove verification for {{ $user->name }}?">Unverify User</flux:menu.item>
                                @else
                                    <flux:menu.item icon="shield-check" wire:click="verifyUser({{ $user->id }})" wire:confirm="Verify {{ $user->name }}'s account?">{{ __('messages.admin_users.verify_nida') }}</flux:menu.item>
                                @endif
                                <flux:menu.submenu heading="2FA/OTP Settings">
                                    @if($user->two_factor_confirmed_at)
                                        <flux:menu.item wire:click="disable2FA({{ $user->id }})" wire:confirm="Disable 2FA for {{ $user->name }}?">Disable 2FA</flux:menu.item>
                                    @else
                                        <flux:menu.item wire:click="enable2FA({{ $user->id }})" wire:confirm="Enable 2FA for {{ $user->name }}?">Enable 2FA</flux:menu.item>
                                    @endif
                                    <flux:menu.item wire:click="reset2FA({{ $user->id }})" wire:confirm="Reset 2FA/OTP for {{ $user->name }}?">Reset 2FA</flux:menu.item>
                                </flux:menu.submenu>
                                <flux:menu.separator />
                                <flux:menu.submenu heading="Change Role">
                                    <flux:menu.item wire:click="changeRole({{ $user->id }}, 'winga')" wire:confirm="Change role to Winga?">Winga (Worker)</flux:menu.item>
                                    <flux:menu.item wire:click="changeRole({{ $user->id }}, 'mteja')" wire:confirm="Change role to Mteja?">Mteja (Client)</flux:menu.item>
                                </flux:menu.submenu>
                                <flux:menu.separator />
                                @if($user->suspended_at)
                                    <flux:menu.item icon="check-circle" wire:click="suspendUser({{ $user->id }})" wire:confirm="Activate {{ $user->name }}?">Activate Account</flux:menu.item>
                                @else
                                    <flux:menu.item icon="exclamation-triangle" variant="danger" wire:click="suspendUser({{ $user->id }})" wire:confirm="Suspend {{ $user->name }}?">{{ __('messages.admin_users.suspend_ban') }}</flux:menu.item>
                                @endif
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="8" class="text-center py-6 text-zinc-500 dark:text-zinc-400">
                        {{ __('messages.admin_users.no_users') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    {{-- Toast listener code (could be globally included in admin layout usually) --}}
    @script
    <script>
        $wire.on('toast', (data) => {
            alert(data.message); // Fallback if regular toast isn't set up yet
        });
    </script>
    @endscript
</div>
