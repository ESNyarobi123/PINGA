<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @stack('styles')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        {{-- Sidebar --}}
        <flux:sidebar sticky collapsible class="border-e border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <a href="/" class="flex items-center gap-2 px-2" wire:navigate>
                    <x-app-logo-icon class="size-8" />
                    <span class="text-md font-bold text-zinc-900 dark:text-white in-data-flux-sidebar-collapsed-desktop:hidden">Winga</span>
                    <flux:badge color="red" size="sm" class="in-data-flux-sidebar-collapsed-desktop:hidden">Admin</flux:badge>
                </a>
                <flux:sidebar.collapse class="hidden lg:flex" />
                <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider in-data-flux-sidebar-collapsed-desktop:hidden">{{ __('messages.admin_sidebar.management') }}</div>
                <div class="grid gap-1">
                    <flux:sidebar.item icon="squares-2x2" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('messages.admin_sidebar.dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="users" :href="route('admin.watumiaji')" :current="request()->routeIs('admin.watumiaji')" wire:navigate>
                        {{ __('messages.admin_sidebar.users') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="briefcase" :href="route('admin.kazi')" :current="request()->routeIs('admin.kazi')" wire:navigate>
                        {{ __('messages.admin_sidebar.all_jobs') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="clock" :href="route('admin.kazi.pending')" :current="request()->routeIs('admin.kazi.pending')" wire:navigate>
                        {{ __('messages.admin_sidebar.pending_jobs') }}
                        @php $pendingCount = \App\Models\Job::where('is_approved', false)->count(); @endphp
                        @if($pendingCount > 0)
                        <flux:badge color="red" size="sm">{{ $pendingCount }}</flux:badge>
                        @endif
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="banknotes" :href="route('admin.malipo')" :current="request()->routeIs('admin.malipo')" wire:navigate>
                        {{ __('messages.admin_sidebar.payments') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="exclamation-triangle" :href="route('admin.migogoro')" :current="request()->routeIs('admin.migogoro')" wire:navigate>
                        {{ __('messages.admin_sidebar.disputes') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chat-bubble-left-right" :href="route('admin.mazungumzo')" :current="request()->routeIs('admin.mazungumzo')" wire:navigate>
                        {{ __('messages.admin_sidebar.conversations') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrow-up-tray" :href="route('admin.maombi-kutoa')" :current="request()->routeIs('admin.maombi-kutoa')" wire:navigate>
                        {{ __('messages.admin_sidebar.withdrawal_requests') }}
                    </flux:sidebar.item>
                </div>

                <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider in-data-flux-sidebar-collapsed-desktop:hidden">{{ __('messages.admin_sidebar.system') }}</div>
                <div class="grid gap-1">
                    <flux:sidebar.item icon="tag" :href="route('admin.kategoria')" :current="request()->routeIs('admin.kategoria')" wire:navigate>
                        {{ __('messages.admin_sidebar.categories') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="credit-card" :href="route('admin.subscription-plans')" :current="request()->routeIs('admin.subscription-plans')" wire:navigate>
                        {{ __('messages.admin_sidebar.subscription_plans') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="star" :href="route('admin.subscriptions')" :current="request()->routeIs('admin.subscriptions')" wire:navigate>
                        {{ __('messages.admin_sidebar.subscriptions') }}
                    </flux:sidebar.item>
                </div>

                <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider in-data-flux-sidebar-collapsed-desktop:hidden">{{ __('messages.admin_sidebar.reports_tools') }}</div>
                <div class="grid gap-1">
                    <flux:sidebar.item icon="clipboard-document-list" :href="route('admin.audit-logs')" :current="request()->routeIs('admin.audit-logs')" wire:navigate>
                        {{ __('messages.admin_sidebar.audit_logs') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chat-bubble-left-right" :href="route('messages')" :current="request()->routeIs('messages*')" wire:navigate>
                        {{ __('messages.sidebar.messages') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('admin.settings')" :current="request()->routeIs('admin.settings')" wire:navigate>
                        {{ __('messages.admin_sidebar.settings') }}
                    </flux:sidebar.item>
                </div>
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        {{-- Mobile Header --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            @livewire('shared.locale-switcher')
            @livewire('shared.notification-bell')
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />
                <flux:menu>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('messages.sidebar.settings') }}</flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                            {{ __('messages.sidebar.logout') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:main>
            <div class="hidden lg:flex items-center justify-end gap-2 px-6 pt-4 pb-0">
                @livewire('shared.locale-switcher')
                @livewire('shared.notification-bell')
            </div>
            {{ $slot }}
        </flux:main>

        @fluxScripts
        @stack('scripts')
    </body>
</html>
