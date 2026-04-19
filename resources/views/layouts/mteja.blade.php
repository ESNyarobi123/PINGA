<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" crossorigin="anonymous"></script>
        @stack('styles')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        {{-- Sidebar --}}
        <flux:sidebar sticky collapsible class="border-e border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 !pt-0 flux-no-scrollbar">
            <flux:sidebar.header class="!pt-2">
                <a href="/" class="flex items-center gap-2 px-2" wire:navigate>
                    <x-app-logo-icon class="size-8" />
                    <span class="text-md font-bold text-zinc-900 dark:text-white in-data-flux-sidebar-collapsed-desktop:hidden">Winga</span>
                    <flux:badge color="blue" size="sm" class="in-data-flux-sidebar-collapsed-desktop:hidden">Mteja</flux:badge>
                </a>
                <flux:sidebar.collapse class="hidden lg:flex" />
                <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                {{-- Mteja Navigation --}}
                <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider in-data-flux-sidebar-collapsed-desktop:hidden">{{ __('messages.mteja_sidebar.client') }}</div>
                <div class="grid gap-1">
                    <flux:sidebar.item icon="home" :href="route('mteja.dashboard')" :current="request()->routeIs('mteja.dashboard')" wire:navigate>
                        {{ __('messages.mteja_sidebar.dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="plus" :href="route('mteja.post-kazi')" :current="request()->routeIs('mteja.post-kazi')" wire:navigate>
                        {{ __('messages.mteja_sidebar.post_new_job') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="briefcase" :href="route('mteja.kazi-zangu')" :current="request()->routeIs('mteja.kazi-zangu')" wire:navigate>
                        {{ __('messages.mteja_sidebar.my_jobs') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="key" :href="route('mteja.codes')" :current="request()->routeIs('mteja.codes')" wire:navigate>
                        <span class="flex items-center gap-1.5">
                            {{ __('messages.mteja_sidebar.job_codes') }}
                            <span class="inline-flex items-center justify-center px-1 py-0.5 bg-orange-500/20 text-orange-400 rounded-full" aria-hidden="true">
                                <x-fluent-icon name="person-key-20" :size="14" class="text-orange-400" />
                            </span>
                        </span>
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="users" :href="route('mteja.maombi')" :current="request()->routeIs('mteja.maombi')" wire:navigate>
                        {{ __('messages.mteja_sidebar.job_applications') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="user-group" :href="route('mteja.mawinga')" :current="request()->routeIs('mteja.mawinga')" wire:navigate>
                        {{ __('messages.mteja_sidebar.my_wingas') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="clipboard-document-list" :href="route('mteja.huduma')" :current="request()->routeIs('mteja.huduma')" wire:navigate>
                        {{ __('messages.mteja_sidebar.browse_services') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="wallet" :href="route('mteja.wallet')" :current="request()->routeIs('mteja.wallet')" wire:navigate>
                        {{ __('messages.mteja_sidebar.wallet') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chat-bubble-left-right" :href="route('messages')" :current="request()->routeIs('messages*')" wire:navigate>
                        {{ __('messages.mteja_sidebar.conversations') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chart-bar" :href="route('mteja.analytics')" :current="request()->routeIs('mteja.analytics')" wire:navigate>
                        {{ __('messages.mteja_sidebar.analytics') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="sparkles" :href="route('mteja.smart-match')" :current="request()->routeIs('mteja.smart-match')" wire:navigate>
                        <span class="flex items-center gap-1.5">
                            Smart Match
                            <span class="px-1.5 py-0.5 bg-violet-500/20 text-violet-400 text-[9px] font-bold rounded-full">AI</span>
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
        </flux:sidebar>

        {{-- Mobile Header --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            {{-- Locale Switcher + Notification Bell (Mobile) --}}
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

        {{-- Main Content --}}
        <flux:main>
            {{-- Desktop Notification Bell + Locale Switcher --}}
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
