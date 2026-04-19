{{-- ======================================== --}}
{{-- Winga Top Navigation (Public) --}}
{{-- All text in Kiswahili --}}
{{-- ======================================== --}}
<header class="sticky top-0 z-50 w-full border-b border-zinc-200/60 dark:border-zinc-800 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-xl">
    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            {{-- Logo --}}
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group" wire:navigate>
                    <span class="flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('icon.png') }}" class="w-9 h-9 object-contain shadow-sm rounded-lg" alt="Winga Logo" />
                    </span>
                    <span class="font-extrabold text-xl tracking-tight text-zinc-900 dark:text-white">
                        Winga
                    </span>
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex items-center gap-1">
                    {{-- Ajiri Wafanyakazi Dropdown --}}
                    <flux:dropdown>
                        <flux:button variant="ghost" icon-trailing="chevron-down" class="text-zinc-700 dark:text-zinc-300 hover:text-winga-600 dark:hover:text-winga-400 font-medium text-sm">
                            {{ __('messages.nav.hire_workers') }}
                        </flux:button>
                        <flux:menu class="w-56">
                            <flux:menu.item href="{{ route('tafuta-wafanyakazi') }}" wire:navigate icon="magnifying-glass">
                                {{ __('messages.nav.find_workers') }}
                            </flux:menu.item>
                            <flux:menu.separator />
                            <flux:menu.item href="{{ route('tafuta-wafanyakazi') }}?filter=ustadi" wire:navigate icon="academic-cap">
                                {{ __('messages.nav.by_skill') }}
                            </flux:menu.item>
                            <flux:menu.item href="{{ route('tafuta-wafanyakazi') }}?filter=mahali" wire:navigate icon="map-pin">
                                {{ __('messages.nav.by_location') }}
                            </flux:menu.item>
                            <flux:menu.item href="{{ route('tafuta-wafanyakazi') }}?filter=kategoria" wire:navigate icon="squares-2x2">
                                {{ __('messages.nav.by_category') }}
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>

                    {{-- Tafuta Kazi Dropdown --}}
                    <flux:dropdown>
                        <flux:button variant="ghost" icon-trailing="chevron-down" class="text-zinc-700 dark:text-zinc-300 hover:text-winga-600 dark:hover:text-winga-400 font-medium text-sm">
                            {{ __('messages.nav.find_jobs') }}
                        </flux:button>
                        <flux:menu class="w-56">
                            <flux:menu.item href="{{ route('tafuta-kazi') }}" wire:navigate icon="briefcase">
                                {{ __('messages.nav.all_jobs') }}
                            </flux:menu.item>
                            <flux:menu.separator />
                            <flux:menu.item href="{{ route('tafuta-kazi') }}?filter=ustadi" wire:navigate icon="academic-cap">
                                {{ __('messages.nav.by_skill') }}
                            </flux:menu.item>
                            <flux:menu.item href="{{ route('tafuta-kazi') }}?filter=kategoria" wire:navigate icon="squares-2x2">
                                {{ __('messages.nav.by_category') }}
                            </flux:menu.item>
                            <flux:menu.item href="{{ route('tafuta-kazi') }}?filter=mahali" wire:navigate icon="map-pin">
                                {{ __('messages.nav.by_location') }}
                            </flux:menu.item>
                            <flux:menu.separator />
                            <flux:menu.item href="{{ route('tafuta-kazi') }}?filter=featured" wire:navigate icon="star">
                                {{ __('messages.nav.featured_jobs') }}
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>

                    {{-- Kuhusu --}}
                    <flux:button variant="ghost" href="{{ route('kuhusu') }}" wire:navigate class="text-zinc-700 dark:text-zinc-300 hover:text-winga-600 dark:hover:text-winga-400 font-medium text-sm">
                        {{ __('messages.nav.about') }}
                    </flux:button>

                    {{-- Suluhisho Dropdown --}}
                    <flux:dropdown>
                        <flux:button variant="ghost" icon-trailing="chevron-down" class="text-zinc-700 dark:text-zinc-300 hover:text-winga-600 dark:hover:text-winga-400 font-medium text-sm">
                            {{ __('messages.nav.solutions') }}
                        </flux:button>
                        <flux:menu class="w-56">
                            <flux:menu.item href="{{ route('bei') }}" icon="currency-dollar">
                                {{ __('messages.nav.pricing') }}
                            </flux:menu.item>
                            <flux:menu.item href="#inavyofanya-kazi" icon="cog-6-tooth">
                                {{ __('messages.nav.how_it_works') }}
                            </flux:menu.item>
                            <flux:menu.item href="{{ route('wasifu') }}" icon="user-circle">
                                {{ __('messages.nav.professional_profile') }}
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>

            {{-- Right side: Auth buttons + Dark mode --}}
            <div class="flex items-center gap-3">
                {{-- Language Switcher --}}
                <livewire:shared.locale-switcher />
                
                {{-- Dark Mode Toggle --}}
                <flux:button variant="ghost" size="sm" icon="moon" class="hidden sm:flex text-zinc-500 dark:text-zinc-400"
                    x-data
                    @click="document.documentElement.classList.toggle('dark')" />

                @auth
                    <flux:button variant="ghost" href="{{ route('dashboard') }}" wire:navigate class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        {{ __('messages.nav.dashboard') }}
                    </flux:button>
                @else
                    <flux:button variant="ghost" href="{{ route('login') }}" wire:navigate class="hidden sm:flex text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        {{ __('messages.nav.login') }}
                    </flux:button>
                    <flux:button variant="primary" href="{{ route('register') }}" wire:navigate class="bg-winga-500 hover:bg-winga-600 text-white text-sm font-semibold px-5">
                        {{ __('messages.nav.register') }}
                    </flux:button>
                @endauth

                {{-- Mobile menu button --}}
                <flux:button variant="ghost" size="sm" icon="bars-3" class="lg:hidden text-zinc-700 dark:text-zinc-300"
                    @click="mobileMenu = !mobileMenu" />
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" x-collapse x-cloak class="lg:hidden border-t border-zinc-200 dark:border-zinc-800 pb-4">
            <div class="space-y-1 pt-3">
                <a href="{{ route('tafuta-wafanyakazi') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-winga-50 dark:hover:bg-zinc-800 hover:text-winga-600" wire:navigate>
                    🔍 {{ __('messages.nav.hire_workers') }}
                </a>
                <a href="{{ route('tafuta-kazi') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-winga-50 dark:hover:bg-zinc-800 hover:text-winga-600" wire:navigate>
                    💼 {{ __('messages.nav.find_jobs') }}
                </a>
                <a href="{{ route('kuhusu') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-winga-50 dark:hover:bg-zinc-800 hover:text-winga-600" wire:navigate>
                    ℹ️ {{ __('messages.nav.about') }}
                </a>
                <a href="{{ route('bei') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-winga-50 dark:hover:bg-zinc-800 hover:text-winga-600">
                    💰 {{ __('messages.nav.pricing') }}
                </a>

                <div class="border-t border-zinc-200 dark:border-zinc-700 mt-3 pt-3 px-3 space-y-2">
                    @guest
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-300 border border-zinc-300 dark:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-800" wire:navigate>
                            {{ __('messages.nav.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-winga-500 hover:bg-winga-600 transition" wire:navigate>
                            {{ __('messages.nav.register') }}
                        </a>
                    @endguest
                    @auth
                        <a href="{{ route('dashboard') }}" class="block w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-winga-500 hover:bg-winga-600 transition" wire:navigate>
                            {{ __('messages.nav.dashboard') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
