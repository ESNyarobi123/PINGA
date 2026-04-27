<x-layouts::public>
    {{-- ================================================ --}}
    {{-- HERO SECTION --}}
    {{-- ================================================ --}}
    <section class="relative overflow-hidden min-h-[600px] lg:min-h-[700px]" x-data="{ currentSlide: 0, slides: 9 }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 5000)">
        {{-- Slideshow Background --}}
        <div class="absolute inset-0">
            @php
                $slideImages = [
                    'Slide Show /Winga (1).png',
                    'Slide Show /Coding (1).png',
                    'Slide Show /Delivery Boy.png',
                    'Slide Show /Farmigation (1).png',
                    'Slide Show /Home tuition.png',
                    'Slide Show /Nursing.png',
                    'Slide Show /Plumbing (1).png',
                    'Slide Show /Ujenzi (1).png',
                    'Slide Show /Umeme.png',
                ];
            @endphp

            @foreach($slideImages as $index => $image)
                <div x-show="currentSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    <img src="{{ asset($image) }}"
                         alt="Slide {{ $index + 1 }}"
                         class="w-full h-full object-cover" />
                </div>
            @endforeach
            
            {{-- Dark overlay for better text readability - darker on mobile --}}
            <div class="absolute inset-0 bg-gradient-to-br from-black/85 via-black/80 to-black/85 lg:from-black/80 lg:via-black/75 lg:to-black/80 dark:from-black/90 dark:via-black/85 dark:to-black/90 dark:lg:from-black/85 dark:lg:via-black/80 dark:lg:to-black/85"></div>
        </div>

        {{-- Slideshow Navigation Arrows --}}
        <button @click="currentSlide = currentSlide === 0 ? slides - 1 : currentSlide - 1"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 shadow-lg flex items-center justify-center text-white transition-all duration-300 hover:scale-110 group">
            <svg class="w-5 h-5 lg:w-6 lg:h-6 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button @click="currentSlide = (currentSlide + 1) % slides"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 shadow-lg flex items-center justify-center text-white transition-all duration-300 hover:scale-110 group">
            <svg class="w-5 h-5 lg:w-6 lg:h-6 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Dots Indicator --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
            @for($i = 0; $i < 4; $i++)
                <button @click="currentSlide = {{ $i }}"
                        :class="currentSlide === {{ $i }} ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"
                        class="h-2 rounded-full transition-all duration-300"></button>
            @endfor
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left: Text Content --}}
                <div class="max-w-xl relative z-10">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/30 backdrop-blur-md border border-white/40 px-4 py-1.5 mb-6 shadow-xl">
                        <span class="w-2 h-2 rounded-full bg-winga-400 animate-pulse"></span>
                        <span class="text-sm font-medium text-white drop-shadow-lg">{{ __('messages.home.badge') }}</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] tracking-tight" style="text-shadow: 0 4px 12px rgba(0,0,0,0.8), 0 2px 4px rgba(0,0,0,0.6);">
                        {{ __('messages.home.hero_title_1') }}
                        <span class="text-winga-400" style="text-shadow: 0 4px 12px rgba(0,0,0,0.8), 0 2px 4px rgba(0,0,0,0.6);">
                            {{ __('messages.home.hero_title_2') }}
                        </span>
                    </h1>

                    <p class="mt-6 text-lg text-white leading-relaxed" style="text-shadow: 0 2px 8px rgba(0,0,0,0.8), 0 1px 3px rgba(0,0,0,0.6);">
                        {{ __('messages.home.hero_desc') }}
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <a href="{{ route('register') }}" wire:navigate
                           class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-winga-500 hover:bg-winga-600 text-white font-semibold text-base shadow-lg shadow-winga-500/25 hover:shadow-winga-600/30 transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            {{ __('messages.home.cta_start') }}
                        </a>
                        <a href="{{ route('tafuta-kazi') }}" wire:navigate
                           class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl border-2 border-white/40 backdrop-blur-sm text-white font-semibold text-base hover:bg-white/10 hover:border-white/60 transition-all duration-300">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            {{ __('messages.home.cta_find_jobs') }}
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center gap-8 mt-10 pt-8">
                        <div>
                            <p class="text-2xl font-bold text-white" style="text-shadow: 0 2px 8px rgba(0,0,0,0.8);">5,000+</p>
                            <p class="text-sm text-white" style="text-shadow: 0 2px 6px rgba(0,0,0,0.7);">{{ __('messages.home.stat_workers') }}</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white" style="text-shadow: 0 2px 8px rgba(0,0,0,0.8);">1,200+</p>
                            <p class="text-sm text-white" style="text-shadow: 0 2px 6px rgba(0,0,0,0.7);">{{ __('messages.home.stat_daily_jobs') }}</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-winga-400" style="text-shadow: 0 2px 8px rgba(0,0,0,0.8);">98%</p>
                            <p class="text-sm text-white" style="text-shadow: 0 2px 6px rgba(0,0,0,0.7);">{{ __('messages.home.stat_satisfaction') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Visual Card Stack --}}
                <div class="relative hidden lg:block z-10">
                    {{-- Floating job cards --}}
                    <div class="relative w-full h-[480px]">
                        {{-- Card 1 --}}
                        <div class="absolute top-0 right-0 w-80 bg-white dark:bg-zinc-800 rounded-2xl shadow-xl shadow-zinc-200/50 dark:shadow-black/30 border border-zinc-100 dark:border-zinc-700 p-5 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-winga-100 dark:bg-winga-900 flex items-center justify-center p-2"><img src="{{ asset('icon.png') }}" class="w-full h-full object-contain" alt="Icon" /></div>
                                <div>
                                    <h3 class="font-semibold text-zinc-900 dark:text-white text-sm">{{ __('messages.home.card_web_dev') }}</h3>
                                    <p class="text-xs text-zinc-500">Dar es Salaam</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-0.5 rounded-full bg-winga-100 dark:bg-winga-800 text-winga-700 dark:text-winga-300 text-xs font-medium">Laravel</span>
                                <span class="px-2 py-0.5 rounded-full bg-accent-orange-100 dark:bg-accent-orange-900 text-accent-orange-700 dark:text-accent-orange-300 text-xs font-medium">React</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-winga-600 dark:text-winga-400 font-bold text-sm">TSh 800,000</span>
                                <span class="text-xs text-zinc-400">{{ __('messages.home.card_2hrs_ago') }}</span>
                            </div>
                        </div>

                        {{-- Card 2 --}}
                        <div class="absolute top-28 left-0 w-72 bg-white dark:bg-zinc-800 rounded-2xl shadow-xl shadow-zinc-200/50 dark:shadow-black/30 border border-zinc-100 dark:border-zinc-700 p-5 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-accent-orange-100 dark:bg-accent-orange-900 flex items-center justify-center p-2"><img src="{{ asset('icon.png') }}" class="w-full h-full object-contain" alt="Icon" /></div>
                                <div>
                                    <h3 class="font-semibold text-zinc-900 dark:text-white text-sm">{{ __('messages.home.card_logo_designer') }}</h3>
                                    <p class="text-xs text-zinc-500">Arusha</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-winga-600 dark:text-winga-400 font-bold text-sm">TSh 150,000</span>
                                <span class="text-xs text-zinc-400">{{ __('messages.home.card_30min_ago') }}</span>
                            </div>
                        </div>

                        {{-- Card 3 --}}
                        <div class="absolute bottom-8 right-8 w-76 bg-white dark:bg-zinc-800 rounded-2xl shadow-xl shadow-zinc-200/50 dark:shadow-black/30 border border-zinc-100 dark:border-zinc-700 p-5 transform rotate-1 hover:rotate-0 transition-transform duration-500">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center p-2"><img src="{{ asset('icon.png') }}" class="w-full h-full object-contain" alt="Icon" /></div>
                                <div>
                                    <h3 class="font-semibold text-zinc-900 dark:text-white text-sm">{{ __('messages.home.card_electrician') }}</h3>
                                    <p class="text-xs text-zinc-500">Mwanza</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300 text-xs font-medium">Umeme</span>
                                <span class="px-2 py-0.5 rounded-full bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300 text-xs font-medium">Solar</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-winga-600 dark:text-winga-400 font-bold text-sm">TSh 200,000/siku</span>
                                <span class="text-xs text-zinc-400">{{ __('messages.home.card_today') }}</span>
                            </div>
                        </div>

                        {{-- Floating notification --}}
                        <div class="absolute top-56 right-4 bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-100 dark:border-zinc-700 px-4 py-3 flex items-center gap-3 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-8 h-8 rounded-full bg-winga-500 flex items-center justify-center">
                                <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-zinc-900 dark:text-white">{{ __('messages.home.card_payment_sent') }}</p>
                                <p class="text-xs text-zinc-500">TSh 500,000 → Amina S.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave separator --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full text-white dark:text-zinc-900">
                <path d="M0 60V30C240 0 480 0 720 30C960 60 1200 60 1440 30V60H0Z" fill="currentColor"/>
            </svg>
        </div>
    </section>

    {{-- ================================================ --}}
    {{-- TRUSTED BY / SOCIAL PROOF --}}
    {{-- ================================================ --}}
    <section class="bg-white dark:bg-zinc-900 py-10 border-b border-zinc-100 dark:border-zinc-800 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-zinc-700 dark:text-zinc-400 font-semibold mb-8 uppercase tracking-wider">{{ __('messages.home.trusted_by') }}</p>
            <div class="relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-16 bg-gradient-to-r from-white dark:from-zinc-900 to-transparent z-10 pointer-events-none"></div>
                <div class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-white dark:from-zinc-900 to-transparent z-10 pointer-events-none"></div>
                <div class="flex logos-slide">
                    @php
                        $logos = [
                            ['src' => 'trust/Vodacom-Logo.wine.png', 'name' => 'Vodacom Tanzania'],
                            ['src' => 'trust/Selcom_solid_red.png', 'name' => 'Selcom'],
                            ['src' => 'trust/Logo-crdb-bank-tanzania-clipart-PNG.png', 'name' => 'CRDB Bank'],
                            ['src' => 'trust/AUHF-blog_featured-image_NMB-bank-tanzania-1024x341.jpg.jpeg', 'name' => 'NMB Bank'],
                            ['src' => 'trust/azam-media-01.png', 'name' => 'Azam Media'],
                            ['src' => 'trust/BONGO LIVE.jpg.jpeg', 'name' => 'Bongo Live'],
                        ];
                    @endphp
                    {{-- First set --}}
                    @foreach($logos as $logo)
                        <div class="flex-shrink-0 group mx-6" title="{{ $logo['name'] }}">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-zinc-100 dark:bg-zinc-800 border-2 border-zinc-200 dark:border-zinc-700 flex items-center justify-center overflow-hidden opacity-70 group-hover:opacity-100 group-hover:border-winga-500 group-hover:ring-4 group-hover:ring-winga-200 dark:group-hover:ring-winga-800 group-hover:shadow-lg group-hover:shadow-winga-100 dark:group-hover:shadow-winga-900/30 transition-all duration-300">
                                <img src="{{ asset($logo['src']) }}" alt="{{ $logo['name'] }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain">
                            </div>
                            <p class="text-[10px] sm:text-xs text-zinc-700 dark:text-zinc-400 text-center mt-2 font-semibold">{{ $logo['name'] }}</p>
                        </div>
                    @endforeach
                    {{-- Second set (duplicate for seamless loop) --}}
                    @foreach($logos as $logo)
                        <div class="flex-shrink-0 group mx-6" title="{{ $logo['name'] }}">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-zinc-100 dark:bg-zinc-800 border-2 border-zinc-200 dark:border-zinc-700 flex items-center justify-center overflow-hidden opacity-70 group-hover:opacity-100 group-hover:border-winga-500 group-hover:ring-4 group-hover:ring-winga-200 dark:group-hover:ring-winga-800 group-hover:shadow-lg group-hover:shadow-winga-100 dark:group-hover:shadow-winga-900/30 transition-all duration-300">
                                <img src="{{ asset($logo['src']) }}" alt="{{ $logo['name'] }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain">
                            </div>
                            <p class="text-[10px] sm:text-xs text-zinc-700 dark:text-zinc-400 text-center mt-2 font-semibold">{{ $logo['name'] }}</p>
                        </div>
                    @endforeach
                    {{-- Third set (duplicate for seamless loop) --}}
                    @foreach($logos as $logo)
                        <div class="flex-shrink-0 group mx-6" title="{{ $logo['name'] }}">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-zinc-100 dark:bg-zinc-800 border-2 border-zinc-200 dark:border-zinc-700 flex items-center justify-center overflow-hidden opacity-70 group-hover:opacity-100 group-hover:border-winga-500 group-hover:ring-4 group-hover:ring-winga-200 dark:group-hover:ring-winga-800 group-hover:shadow-lg group-hover:shadow-winga-100 dark:group-hover:shadow-winga-900/30 transition-all duration-300">
                                <img src="{{ asset($logo['src']) }}" alt="{{ $logo['name'] }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain">
                            </div>
                            <p class="text-[10px] sm:text-xs text-zinc-700 dark:text-zinc-400 text-center mt-2 font-semibold">{{ $logo['name'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <style>
            @keyframes slide {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(-100% / 3)); }
            }
            .logos-slide {
                animation: slide 40s linear infinite;
                display: flex;
                width: max-content;
            }
            .logos-slide:hover {
                animation-play-state: paused;
            }
        </style>
    </section>

    {{-- ================================================ --}}
    {{-- HOW IT WORKS --}}
    {{-- ================================================ --}}
    <section id="inavyofanya-kazi" class="bg-white dark:bg-zinc-900 py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-block px-3 py-1 rounded-full bg-winga-100 dark:bg-winga-900/50 text-winga-700 dark:text-winga-300 text-sm font-semibold mb-4">{{ __('messages.home.steps_badge') }}</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white">
                    {{ __('messages.home.steps_title') }}
                </h2>
                <p class="mt-4 text-lg text-zinc-500 dark:text-zinc-400">
                    {{ __('messages.home.steps_subtitle') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @php
                    $steps = [
                        ['num' => '01', 'svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>', 'title' => __('messages.home.step1_title'), 'desc' => __('messages.home.step1_desc'), 'color' => 'winga'],
                        ['num' => '02', 'svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>', 'title' => __('messages.home.step2_title'), 'desc' => __('messages.home.step2_desc'), 'color' => 'accent-orange'],
                        ['num' => '03', 'svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>', 'title' => __('messages.home.step3_title'), 'desc' => __('messages.home.step3_desc'), 'color' => 'winga'],
                        ['num' => '04', 'svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>', 'title' => __('messages.home.step4_title'), 'desc' => __('messages.home.step4_desc'), 'color' => 'accent-orange'],
                    ];
                @endphp

                @foreach($steps as $step)
                    <div class="group relative bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl p-6 lg:p-8 hover:bg-white dark:hover:bg-zinc-800 shadow-sm hover:shadow-xl transition-all duration-500 border border-zinc-100 dark:border-zinc-700/50 hover:border-winga-200 dark:hover:border-winga-700">
                        {{-- Step number --}}
                        <span class="absolute -top-3 -left-1 text-5xl font-black text-zinc-100 dark:text-zinc-800 group-hover:text-winga-100 dark:group-hover:text-winga-900/30 transition-colors duration-500">{{ $step['num'] }}</span>

                        <div class="relative">
                            <div class="w-14 h-14 rounded-xl bg-{{ $step['color'] }}-100 dark:bg-{{ $step['color'] }}-900/30 flex items-center justify-center text-winga-600 dark:text-winga-400 mb-4 group-hover:scale-110 transition-transform duration-500">
                                {!! $step['svg'] !!}
                            </div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">{{ $step['title'] }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================================================ --}}
    {{-- FEATURED WINGAS (SUBSCRIPTION-GATED SPOTLIGHT) --}}
    {{-- ================================================ --}}
    @if(isset($featuredWingas) && $featuredWingas->isNotEmpty())
    <section class="bg-zinc-50 dark:bg-zinc-950 py-20 lg:py-24 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-winga-100 dark:bg-winga-900/50 text-winga-700 dark:text-winga-300 text-sm font-semibold mb-3">
                        {{ __('messages.home.featured_badge') }}
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white">
                        {{ __('messages.home.featured_title') }}
                    </h2>
                    <p class="mt-2 text-zinc-500 dark:text-zinc-400">{{ __('messages.home.featured_subtitle') }}</p>
                </div>
                <a href="{{ route('tafuta-wafanyakazi') }}" wire:navigate
                   class="inline-flex items-center gap-2 text-winga-600 dark:text-winga-400 font-semibold hover:text-winga-700 dark:hover:text-winga-300 transition-colors shrink-0">
                    {{ __('messages.home.featured_view_all') }}
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>

            {{-- Scrollable carousel --}}
            <div class="relative">
                <div id="winga-carousel" class="flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scroll-smooth scrollbar-none"
                     style="-ms-overflow-style:none; scrollbar-width:none;">
                    @foreach($featuredWingas as $winga)
                    <a href="{{ route('wafanyakazi.show', $winga->id) }}" wire:navigate
                       class="flex-shrink-0 w-72 snap-start group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 hover:border-winga-400 dark:hover:border-winga-500 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        {{-- Top gradient banner --}}
                        <div class="h-20 bg-gradient-to-r from-winga-500 to-winga-600 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.4) 1px, transparent 0); background-size: 16px 16px;"></div>
                            {{-- Featured badge --}}
                            <div class="absolute top-3 right-3 text-[10px] font-bold text-white uppercase tracking-wider bg-amber-500/90 backdrop-blur-sm px-2 py-0.5 rounded-md flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ __('messages.home.featured_card_badge') }}
                            </div>
                        </div>
                        {{-- Profile content --}}
                        <div class="px-5 pt-0 pb-5">
                            {{-- Avatar overlapping banner --}}
                            <div class="-mt-10 mb-3 flex items-end gap-3">
                                @if($winga->avatar)
                                    <img src="{{ asset('storage/'.$winga->avatar) }}"
                                         class="w-16 h-16 rounded-full border-4 border-white dark:border-zinc-900 object-cover shadow-md group-hover:ring-2 group-hover:ring-winga-300 dark:group-hover:ring-winga-600 transition-all duration-300"
                                         alt="{{ $winga->name }}" />
                                @else
                                    <div class="w-16 h-16 rounded-full border-4 border-white dark:border-zinc-900 bg-winga-500 flex items-center justify-center shadow-md group-hover:ring-2 group-hover:ring-winga-300 dark:group-hover:ring-winga-600 transition-all duration-300">
                                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($winga->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                {{-- Rating pill next to avatar --}}
                                <div class="pb-1">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-500/20">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ number_format($winga->average_rating ?? 4.5, 1) }}
                                    </span>
                                </div>
                            </div>
                            {{-- Name & location --}}
                            <h3 class="font-bold text-zinc-900 dark:text-white text-base truncate group-hover:text-winga-600 dark:group-hover:text-winga-400 transition-colors">
                                {{ $winga->name }}
                            </h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 flex items-center gap-1">
                                <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                {{ $winga->mkoa ?? $winga->wilaya ?? 'Tanzania' }}
                            </p>
                            {{-- Skills --}}
                            @if($winga->skills->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                @foreach($winga->skills->take(3) as $skill)
                                <span class="text-[11px] px-2 py-0.5 rounded-full bg-winga-50 dark:bg-winga-900/30 text-winga-700 dark:text-winga-400 font-medium border border-winga-100 dark:border-winga-800">
                                    {{ $skill->name }}
                                </span>
                                @endforeach
                                @if($winga->skills->count() > 3)
                                <span class="text-[11px] px-1.5 py-0.5 text-zinc-400 dark:text-zinc-500 font-medium">
                                    +{{ $winga->skills->count() - 3 }}
                                </span>
                                @endif
                            </div>
                            @endif
                            {{-- Price & CTA --}}
                            <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                <span class="text-sm font-bold text-winga-600 dark:text-winga-400">
                                    TZS {{ number_format($winga->bei_wastani ?? 0) }}<span class="text-xs font-normal text-zinc-400">/siku</span>
                                </span>
                                <span class="text-xs font-semibold text-winga-600 dark:text-winga-400 group-hover:underline flex items-center gap-0.5 transition-colors">
                                    {{ __('messages.search_workers.view_profile') }}
                                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Scroll arrows --}}
                <button onclick="document.getElementById('winga-carousel').scrollBy({left: -320, behavior: 'smooth'})"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-full shadow-lg flex items-center justify-center text-zinc-700 dark:text-zinc-300 hover:bg-winga-50 hover:text-winga-600 transition hidden sm:flex">
                    ‹
                </button>
                <button onclick="document.getElementById('winga-carousel').scrollBy({left: 320, behavior: 'smooth'})"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-full shadow-lg flex items-center justify-center text-zinc-700 dark:text-zinc-300 hover:bg-winga-50 hover:text-winga-600 transition hidden sm:flex">
                    ›
                </button>
            </div>
        </div>
    </section>
    @endif

    {{-- ================================================ --}}
    {{-- CODE PAYMENT FEATURE HIGHLIGHT --}}
    {{-- ================================================ --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-winga-600 via-winga-700 to-winga-800 py-20 lg:py-28">
        {{-- Background pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 32px 32px;"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-white/15 text-white text-sm font-semibold mb-5">{{ __('messages.home.code_badge') }}</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white leading-tight">
                        {{ __('messages.home.code_title') }} <span class="text-accent-orange-400">{{ __('messages.home.code_title_highlight') }}</span>
                    </h2>
                    <p class="mt-5 text-lg text-winga-100/80 leading-relaxed">
                        {{ __('messages.home.code_desc') }}
                    </p>
                    <ul class="mt-8 space-y-4">
                        @foreach([__('messages.home.code_point_1'), __('messages.home.code_point_2'), __('messages.home.code_point_3'), __('messages.home.code_point_4')] as $point)
                            <li class="flex items-center gap-3 text-white/90">
                                <span class="w-6 h-6 rounded-full bg-accent-orange-500 flex items-center justify-center shrink-0">
                                    <svg class="size-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Code entry visual --}}
                <div class="flex justify-center">
                    <div class="bg-white dark:bg-zinc-800 rounded-3xl shadow-2xl p-8 w-full max-w-sm">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 rounded-2xl bg-winga-100 dark:bg-winga-900 mx-auto flex items-center justify-center mb-4">
                                <svg class="size-8 text-winga-600 dark:text-winga-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <h3 class="font-bold text-zinc-900 dark:text-white text-lg">{{ __('messages.home.code_enter_title') }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.home.code_enter_subtitle') }}</p>
                        </div>

                        {{-- Code input display --}}
                        <div class="flex justify-center gap-2 mb-6">
                            @foreach(['7', '3', '9', '4', '2', '8'] as $digit)
                                <div class="w-11 h-13 rounded-xl bg-zinc-100 dark:bg-zinc-700 border-2 border-winga-300 dark:border-winga-600 flex items-center justify-center text-xl font-bold text-zinc-900 dark:text-white">
                                    {{ $digit }}
                                </div>
                            @endforeach
                        </div>

                        <button class="w-full py-3 rounded-xl bg-winga-500 hover:bg-winga-600 text-white font-semibold text-sm transition-all shadow-lg shadow-winga-500/20">
                            {{ __('messages.home.code_confirm_btn') }}
                        </button>

                        <p class="text-center text-xs text-zinc-400 mt-3">
                            {{ __('messages.home.code_payment_note') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================ --}}
    {{-- CATEGORIES --}}
    {{-- ================================================ --}}
    <section class="bg-zinc-50 dark:bg-zinc-950 py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white">
                    {{ __('messages.home.categories_title') }}
                </h2>
                <p class="mt-4 text-lg text-zinc-500 dark:text-zinc-400">
                    {{ __('messages.home.categories_subtitle') }}
                </p>
            </div>

            @php
                $categories = [
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><path d="M8 21h8M12 17v4"/></svg>',
                        'name' => __('messages.home.cat_tech'), 'count' => '340+', 'color' => 'bg-violet-100 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                        'name' => __('messages.home.cat_creative'), 'count' => '220+', 'color' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
                        'name' => __('messages.home.cat_writing'), 'count' => '180+', 'color' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>',
                        'name' => __('messages.home.cat_marketing'), 'count' => '150+', 'color' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                        'name' => __('messages.home.cat_construction'), 'count' => '290+', 'color' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4 4 4-4m-4-5v9M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29"/></svg>',
                        'name' => __('messages.home.cat_transport'), 'count' => '120+', 'color' => 'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
                        'name' => __('messages.home.cat_education'), 'count' => '95+', 'color' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
                        'name' => __('messages.home.cat_health'), 'count' => '75+', 'color' => 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>',
                        'name' => __('messages.home.cat_agriculture'), 'count' => '110+', 'color' => 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                        'name' => __('messages.home.cat_home'), 'count' => '200+', 'color' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>',
                        'name' => __('messages.home.cat_entertainment'), 'count' => '85+', 'color' => 'bg-fuchsia-100 dark:bg-fuchsia-900/30 text-fuchsia-600 dark:text-fuchsia-400'
                    ],
                    [
                        'svg' => '<svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>',
                        'name' => __('messages.home.cat_office'), 'count' => '160+', 'color' => 'bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400'
                    ],
                ];
            @endphp

            <div x-data="{ showAll: false }">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    @foreach($categories as $index => $cat)
                        <a href="{{ route('tafuta-kazi') }}?category={{ Str::slug($cat['name']) }}"
                           x-show="showAll || {{ $index }} < 6"
                           x-cloak
                           class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-white dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700/50 hover:border-winga-300 dark:hover:border-winga-600 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-xl {{ $cat['color'] }} flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                {!! $cat['svg'] !!}
                            </div>
                            <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 text-center">{{ $cat['name'] }}</span>
                            <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $cat['count'] }} {{ __('messages.home.cat_jobs_suffix') }}</span>
                        </a>
                    @endforeach
                </div>

                <div class="text-center mt-10 space-y-3">
                    <button @click="showAll = !showAll"
                       class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl border border-winga-300 dark:border-winga-600 text-winga-600 dark:text-winga-400 font-semibold hover:bg-winga-50 dark:hover:bg-winga-900/20 transition-colors">
                        <span x-text="showAll ? '{{ __('messages.home.categories_show_less') }}' : '{{ __('messages.home.categories_view_more') }}'"></span>
                        <svg :class="showAll ? 'rotate-180' : ''" class="size-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div>
                        <a href="{{ route('kazi-by-category') }}" wire:navigate
                           class="inline-flex items-center gap-2 text-zinc-500 dark:text-zinc-400 text-sm font-medium hover:text-winga-600 dark:hover:text-winga-400 transition-colors">
                            {{ __('messages.home.categories_view_all') }}
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================ --}}
    {{-- WHY WINGA --}}
    {{-- ================================================ --}}
    <section class="bg-white dark:bg-zinc-900 py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white">
                    {{ __('messages.home.why_title') }} <span class="text-winga-500">Winga</span>?
                </h2>
                <p class="mt-4 text-lg text-zinc-500 dark:text-zinc-400">
                    {{ __('messages.home.why_subtitle') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $features = [
                        ['svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>', 'title' => __('messages.home.why_secure_title'), 'desc' => __('messages.home.why_secure_desc')],
                        ['svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>', 'title' => __('messages.home.why_fast_title'), 'desc' => __('messages.home.why_fast_desc')],
                        ['svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>', 'title' => __('messages.home.why_nearby_title'), 'desc' => __('messages.home.why_nearby_desc')],
                        ['svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>', 'title' => __('messages.home.why_mobile_title'), 'desc' => __('messages.home.why_mobile_desc')],
                        ['svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>', 'title' => __('messages.home.why_mpesa_title'), 'desc' => __('messages.home.why_mpesa_desc')],
                        ['svg' => '<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>', 'title' => __('messages.home.why_rating_title'), 'desc' => __('messages.home.why_rating_desc')],
                    ];
                @endphp

                @foreach($features as $feature)
                    <div class="group p-6 lg:p-8 rounded-2xl bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/50 hover:bg-winga-50 dark:hover:bg-winga-900/20 hover:border-winga-200 dark:hover:border-winga-700 transition-all duration-500">
                        <div class="w-12 h-12 rounded-xl bg-winga-100 dark:bg-winga-900/30 text-winga-600 dark:text-winga-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            {!! $feature['svg'] !!}
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================================================ --}}
    {{-- CTA SECTION --}}
    {{-- ================================================ --}}
    <section class="relative overflow-hidden bg-zinc-900 dark:bg-zinc-950 py-20 lg:py-28">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0 bg-gradient-to-br from-winga-600 to-accent-orange-500"></div>
        </div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.08) 1px, transparent 0); background-size: 24px 24px;"></div>

        <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                {{ __('messages.home.cta_title_1') }}
                <span class="text-winga-400">{{ __('messages.home.cta_title_2') }}</span>?
            </h2>
            <p class="mt-6 text-lg text-zinc-400 max-w-2xl mx-auto">
                {{ __('messages.home.cta_subtitle') }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
                <a href="{{ route('register') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-winga-500 hover:bg-winga-600 text-white font-bold text-base shadow-xl shadow-winga-500/25 hover:shadow-winga-600/30 transition-all duration-300 hover:-translate-y-0.5">
                    {{ __('messages.home.cta_worker') }}
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('register') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-base border border-white/20 transition-all duration-300">
                    {{ __('messages.home.cta_employer') }}
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </section>
</x-layouts::public>
