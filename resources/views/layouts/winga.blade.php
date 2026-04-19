<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" crossorigin="anonymous"></script>
        @stack('styles')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        {{-- Sidebar --}}
        <flux:sidebar sticky collapsible class="border-e border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 flux-no-scrollbar">
            <flux:sidebar.header>
                <a href="/" class="flex items-center gap-2 px-2" wire:navigate>
                    <x-app-logo-icon class="size-8" />
                    <span class="text-md font-bold text-zinc-900 dark:text-white in-data-flux-sidebar-collapsed-desktop:hidden">Winga</span>
                    <flux:badge color="green" size="sm" class="in-data-flux-sidebar-collapsed-desktop:hidden">Winga</flux:badge>
                </a>
                <flux:sidebar.collapse class="hidden lg:flex" />
                <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider in-data-flux-sidebar-collapsed-desktop:hidden">{{ __('messages.winga_sidebar.worker') }}</div>
                <div class="grid gap-1">
                    <flux:sidebar.item icon="home" :href="route('winga.dashboard')" :current="request()->routeIs('winga.dashboard')" wire:navigate>
                        {{ __('messages.winga_sidebar.dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="briefcase" :href="route('winga.kazi-karibu')" :current="request()->routeIs('winga.kazi-karibu')" wire:navigate>
                        {{ __('messages.winga_sidebar.nearby_jobs') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="document-text" :href="route('winga.maombi-yangu')" :current="request()->routeIs('winga.maombi-yangu')" wire:navigate>
                        {{ __('messages.winga_sidebar.my_applications') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="photo" :href="route('winga.portfolio')" :current="request()->routeIs('winga.portfolio')" wire:navigate>
                        {{ __('messages.winga_sidebar.portfolio') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="banknotes" :href="route('winga.mapato')" :current="request()->routeIs('winga.mapato')" wire:navigate>
                        {{ __('messages.winga_sidebar.earnings') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrow-up-tray" :href="route('winga.tomba-ombi')" :current="request()->routeIs('winga.tomba-ombi')" wire:navigate>
                        {{ __('messages.winga_sidebar.request_withdrawal') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="star" :href="route('winga.subscription')" :current="request()->routeIs('winga.subscription')" wire:navigate>
                        <span class="flex items-center gap-2">
                            Subscription
                            @php $activeSub = auth()->user()->activeSubscription; @endphp
                            @if($activeSub)
                                <span class="px-1.5 py-0.5 bg-green-500/20 text-green-500 dark:text-green-400 text-[9px] font-black rounded-full uppercase">ACTIVE</span>
                            @elseif(auth()->user()->subscriptions()->where('status', 'expired')->exists())
                                <span class="px-1.5 py-0.5 bg-red-500/20 text-red-500 dark:text-red-400 text-[9px] font-black rounded-full uppercase">EXPIRED</span>
                            @endif
                        </span>
                    </flux:sidebar.item>
                    <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider in-data-flux-sidebar-collapsed-desktop:hidden">{{ __('messages.winga_sidebar.services_block') }}</div>
                    <flux:sidebar.item icon="plus-circle" :href="route('winga.post-huduma')" :current="request()->routeIs('winga.post-huduma')" wire:navigate>
                        {{ __('messages.winga_sidebar.post_service') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="clipboard-document-list" :href="route('winga.huduma-zangu')" :current="request()->routeIs('winga.huduma-zangu')" wire:navigate>
                        {{ __('messages.winga_sidebar.my_services') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="inbox" :href="route('winga.huduma-maombi')" :current="request()->routeIs('winga.huduma-maombi')" wire:navigate>
                        <span class="flex items-center gap-2">
                            {{ __('messages.winga_sidebar.service_requests') }}
                            @php
                                $pendingHuduma = \App\Models\ServiceRequest::query()
                                    ->whereHas('service', fn ($q) => $q->where('user_id', auth()->id()))
                                    ->where('status', 'pending')
                                    ->count();
                            @endphp
                            @if($pendingHuduma > 0)
                                <flux:badge color="amber" size="sm">{{ $pendingHuduma }}</flux:badge>
                            @endif
                        </span>
                    </flux:sidebar.item>
                </div>

                {{-- Smart Tools --}}
                <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider in-data-flux-sidebar-collapsed-desktop:hidden">{{ __('messages.winga_sidebar.maps_tools') }}</div>
                <div class="grid gap-1">
                    <flux:sidebar.item icon="map" :href="route('winga.ramani')" :current="request()->routeIs('winga.ramani')" wire:navigate>
                        {{ __('messages.winga_sidebar.job_map') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="key" :href="route('winga.weka-code')" :current="request()->routeIs('winga.weka-code')" wire:navigate>
                        <span class="flex items-center gap-1.5">
                            {{ __('messages.winga_sidebar.enter_code') }}
                            <span class="inline-flex items-center justify-center px-1 py-0.5 bg-orange-500/20 text-orange-400 rounded-full" aria-hidden="true">
                                <x-fluent-icon name="person-key-20" :size="14" class="text-orange-400" />
                            </span>
                        </span>
                    </flux:sidebar.item>
                </div>

                <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider in-data-flux-sidebar-collapsed-desktop:hidden">{{ __('messages.sidebar.support') }}</div>
                <div class="grid gap-1">
                    <flux:sidebar.item icon="chat-bubble-left" :href="route('messages')" :current="request()->routeIs('messages*')" wire:navigate>
                        {{ __('messages.sidebar.messages') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('profile.edit')" wire:navigate>
                        {{ __('messages.sidebar.settings') }}
                    </flux:sidebar.item>
                </div>
            </flux:sidebar.nav>

            <flux:spacer />

            {{-- Earnings summary --}}
            <div class="mx-3 mb-3 p-3 rounded-xl bg-winga-50 dark:bg-winga-900/20 border border-winga-200 dark:border-winga-800 in-data-flux-sidebar-collapsed-desktop:hidden">
                <p class="text-xs text-winga-600 dark:text-winga-400 font-medium inline-flex items-center gap-1.5">
                    <x-fluent-icon name="coin-multiple-20" :size="16" class="text-winga-600 dark:text-winga-400" />
                    {{ __('messages.winga_sidebar.wallet_balance') }}
                </p>
                <p class="text-lg font-bold text-winga-700 dark:text-winga-300">TSh {{ number_format(auth()->user()->wallet_balance ?? 0) }}</p>
            </div>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        {{-- Mobile Header --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            {{-- Locale Switcher + Notification Bell --}}
            @livewire('shared.locale-switcher')
            @livewire('shared.notification-bell')
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />
                <flux:menu>
                    <flux:menu.item :href="route('messages')" icon="chat-bubble-left" wire:navigate>{{ __('messages.sidebar.messages') }}</flux:menu.item>
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
            {{-- Desktop Locale Switcher + Notification Bell --}}
            <div class="hidden lg:flex items-center justify-end gap-2 px-6 py-2">
                @livewire('shared.locale-switcher')
                @livewire('shared.notification-bell')
            </div>
            {{ $slot }}
        </flux:main>

        @fluxScripts
        @stack('scripts')
    </body>
</html>
