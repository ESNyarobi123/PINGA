@php
    $w = $this->wafanyakazi;
    $avatarUrl = $w->avatar ? asset('storage/'.$w->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($w->name).'&background=0d9488&color=fff&size=256';
    $location = trim(implode(', ', array_filter([$w->mtaa, $w->wilaya, $w->mkoa])));
    $rating = round((float) ($w->reviews_received_avg_rating ?? 0), 1);
    $bio = $w->bio ?? __('messages.worker_profile.no_bio');
    $portfolio = $w->portfolio;
@endphp
<div class="min-h-screen bg-zinc-50/50 dark:bg-zinc-950 pb-12">
    {{-- Hero Section (Premium Profile Card) --}}
    <header class="relative bg-white dark:bg-zinc-900 border-b border-zinc-200/80 dark:border-zinc-800/80 pt-8 pb-10 sm:pt-10 sm:pb-12 overflow-hidden shadow-sm">
        {{-- Background Pattern/Gradient --}}
        <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-winga-50 to-transparent dark:from-winga-900/20 dark:to-transparent pointer-events-none"></div>
        
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb & Back Link --}}
            <nav class="flex items-center justify-between mb-8">
                <a href="{{ route('tafuta-wafanyakazi') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-zinc-500 dark:text-zinc-400 hover:text-winga-600 dark:hover:text-winga-400 transition-colors" wire:navigate>
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('messages.worker_profile.all_workers') }}
                </a>
                
                {{-- Action Buttons (Top Right) --}}
                <div class="flex items-center gap-2 lg:gap-3">
                    <button type="button" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-sm font-semibold text-zinc-700 dark:text-zinc-300 shadow-sm hover:bg-zinc-50 dark:hover:bg-zinc-800 hover:text-red-600 dark:hover:text-red-500 hover:border-red-200 dark:hover:border-red-800/50 transition-all focus:outline-none ring-1 ring-zinc-900/5">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span class="hidden sm:inline">{{ __('messages.worker_profile.save_profile') }}</span>
                    </button>
                    <button type="button" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-sm font-semibold text-zinc-700 dark:text-zinc-300 shadow-sm hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-all focus:outline-none ring-1 ring-zinc-900/5">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        <span class="hidden sm:inline">{{ __('messages.worker_profile.share') }}</span>
                    </button>
                </div>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-start gap-6 sm:gap-8 relative z-10">
                {{-- Profile Picture --}}
                <div class="shrink-0 relative">
                    <img src="{{ $avatarUrl }}" alt="{{ $w->name }}" class="size-28 sm:size-36 rounded-2xl object-cover ring-4 ring-white dark:ring-zinc-900 shadow-xl bg-white dark:bg-zinc-800" />
                    
                    {{-- Rating Badge Overlapping Picture --}}
                    @if($rating > 0)
                    <div class="absolute -bottom-3 -right-3 bg-white dark:bg-zinc-900 rounded-xl p-1 shadow-md border border-zinc-100 dark:border-zinc-800">
                        <span class="flex items-center gap-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-sm font-bold px-2 py-1 rounded-lg">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ $rating }}
                        </span>
                    </div>
                    @endif
                </div>
                
                {{-- Profile Info --}}
                <div class="min-w-0 flex-1 pt-1 sm:pt-3">
                    <div class="flex flex-wrap items-center gap-3 mb-1.5">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 dark:text-white tracking-tight leading-tight">
                            {{ $w->name }}
                        </h1>

                        {{-- Subscription & Verification Badges --}}
                        @if(!empty($highlights))
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- Plan Badge --}}
                            @if(!empty($highlights['plan']))
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-black uppercase tracking-wider {{ $highlights['plan']['class'] }}">
                                @if($highlights['plan']['slug'] === 'bora')
                                    <svg class="size-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endif
                                {{ $highlights['plan']['name'] }}
                            </span>
                            @endif

                            {{-- Verified Badge --}}
                            @if(!empty($highlights['verified']))
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider {{ $highlights['verified']['class'] }}">
                                <svg class="size-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                {{ $highlights['verified']['label'] }}
                            </span>
                            @endif

                            {{-- Top Rated Badge --}}
                            @if(!empty($highlights['top_rated']))
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider {{ $highlights['top_rated']['class'] }}">
                                {{ $highlights['top_rated']['label'] }}
                            </span>
                            @endif

                            {{-- Response Time Badge --}}
                            @if(!empty($highlights['response_time']))
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider {{ $highlights['response_time']['class'] }}">
                                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $highlights['response_time']['label'] }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- Custom URL --}}
                    @if(!empty($highlights['custom_url']))
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm text-zinc-500">winga.com/w/</span>
                        <span class="text-sm font-bold text-winga-600">{{ $highlights['custom_url']['slug'] }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ $highlights['custom_url']['url'] }}')" class="text-zinc-400 hover:text-winga-600 transition" title="{{ __('messages.worker_profile.copy_link') }}">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                    @endif

                    {{-- Offer Title (If exists, else generic role) --}}
                    <h2 class="text-lg text-winga-600 dark:text-winga-400 font-medium mb-4">
                        {{ $w->offer_title ?? __('messages.worker_profile.service_expert') }}
                    </h2>

                    {{-- Meta Stats --}}
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-[14px] text-zinc-600 dark:text-zinc-400 font-medium">
                        @if($location)
                        <span class="inline-flex items-center gap-2">
                            <div class="p-1.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            {{ $location }}
                        </span>
                        @endif
                        
                        <span class="inline-flex items-center gap-2">
                            <div class="p-1.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            {{ __('messages.worker_profile.member_since') }} {{ $w->created_at->format('M Y') }}
                        </span>
                        
                        <span class="inline-flex items-center gap-2">
                            <div class="p-1.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            {{ __('messages.worker_profile.completed_jobs') }} {{ $w->completed_jobs_count ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Layout (Unified Cards style) --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
            
            {{-- Left Column: Main Details (Unified Body) --}}
            <div class="lg:col-span-8 space-y-6">
                
                <article class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm overflow-hidden flex flex-col">
                    
                    {{-- Section 1: Kuhusu / Bio --}}
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="size-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.worker_profile.about_worker') }}</h2>
                        </div>
                        <div class="prose prose-zinc dark:prose-invert max-w-none text-zinc-700 dark:text-zinc-300 text-[15px] leading-relaxed whitespace-pre-line">
                            {{ $bio }}
                        </div>
                    </div>

                    {{-- Sub-section Separator --}}
                    @if($w->skills->isNotEmpty())
                    <div class="px-6 sm:px-8">
                        <hr class="border-zinc-100 dark:border-zinc-800/80">
                    </div>
                    
                    {{-- Section 2: Ujuzi (Skills) --}}
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="size-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.worker_profile.skills_expertise') }}</h2>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($w->skills as $skill)
                            <span class="inline-flex items-center rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-[13px] font-semibold px-4 py-2 border border-zinc-200/60 dark:border-zinc-700 transition-colors hover:bg-zinc-200 dark:hover:bg-zinc-700">
                                {{ $skill->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </article>

                {{-- Portfolio Section (Separate Modern Kadi) --}}
                @if($portfolio->isNotEmpty())
                <article class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded-lg bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center text-orange-600 dark:text-orange-400">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.worker_profile.portfolio') }}</h2>
                            </div>
                            <a href="#" class="text-sm font-semibold text-winga-600 dark:text-winga-400 hover:text-winga-500 transition-colors">{{ __('messages.worker_profile.view_all') }}</a>
                        </div>
                        
                        {{-- Portfolio Grid Modern --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @foreach($portfolio->take(4) as $item)
                            <div class="group relative rounded-2xl overflow-hidden border border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-800/30 hover:shadow-md transition-all">
                                {{-- Image Area --}}
                                <div class="aspect-[4/3] w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                                    @if($item->image_path)
                                    <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                    @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-zinc-400 dark:text-zinc-600 group-hover:bg-winga-50 dark:group-hover:bg-winga-900/10 transition-colors">
                                        <svg class="size-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                        <span class="text-xs font-semibold uppercase tracking-wider">{{ __('messages.worker_profile.no_image') }}</span>
                                    </div>
                                    @endif
                                </div>
                                {{-- Content Overlay --}}
                                <div class="p-4 bg-white dark:bg-zinc-900 w-full border-t border-zinc-100 dark:border-zinc-800">
                                    <p class="font-bold text-[15px] text-zinc-900 dark:text-white line-clamp-1">{{ $item->title }}</p>
                                    @if($item->description)
                                    <p class="text-[13px] text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2 leading-relaxed">{{ $item->description }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </article>
                @endif
            </div>

            {{-- Right Column: Sidebar (Pricing & Action Center) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-6 space-y-6">
                    
                    {{-- Pricing & Contact Action Card --}}
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-md shadow-zinc-200/30 dark:shadow-none overflow-hidden relative">
                        
                        {{-- Top Pattern decoration --}}
                        <div class="h-2 w-full bg-gradient-to-r from-emerald-500 to-emerald-400"></div>

                        <div class="p-6 sm:p-8">
                            <div class="flex items-center gap-2 text-zinc-500 dark:text-zinc-400 mb-2">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-[13px] font-bold uppercase tracking-wider">{{ __('messages.worker_profile.starting_from') }}</span>
                            </div>
                            
                            <h3 class="text-[1.8rem] sm:text-[2rem] font-extrabold text-zinc-900 dark:text-white tracking-tight leading-none mb-6">
                                {{ $w->bei_wastani ? 'TZS '.number_format($w->bei_wastani) : __('messages.worker_profile.negotiable') }}
                                @if($w->bei_wastani)
                                <span class="text-sm font-medium text-zinc-400 dark:text-zinc-500 lowercase">/ {{ $w->bei_aina ?? 'siku' }}</span>
                                @endif
                            </h3>

                            @auth
                                {{-- Task 6: Phone/WhatsApp visible only after job is accepted & paid --}}
                                @if($canViewContact && $w->phone)
                                <div class="flex flex-col gap-3">
                                    <a href="tel:{{ $w->phone }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:hover:bg-zinc-100 text-white dark:text-zinc-900 border border-transparent px-6 py-4 text-[16px] font-bold shadow-lg focus:outline-none transition-all transform hover:-translate-y-0.5">
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ __('messages.worker_profile.call_direct') }}
                                    </a>
                                    {{-- WhatsApp Button --}}
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $w->phone) }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white border border-transparent px-6 py-4 text-[16px] font-bold shadow-lg shadow-emerald-500/25 focus:outline-none transition-all transform hover:-translate-y-0.5 group overflow-hidden relative">
                                        <div class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                        <svg class="size-6 relative" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zM12 2.032c5.514 0 10 4.486 10 10s-4.486 10-10 10c-1.846 0-3.575-.5-5.084-1.385l-4.916 1.45 1.469-4.789A9.954 9.954 0 012 12.032c0-5.514 4.486-10 10-10zm0 1.833A8.167 8.167 0 1020.167 12 8.167 8.167 0 0012 3.865z"/></svg>
                                        <span class="relative">{{ __('messages.worker_profile.whatsapp') }}</span>
                                    </a>
                                </div>
                                @else
                                <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Nambari imefichwa</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Nambari ya simu na WhatsApp itaonekana baada ya kukubali na kulipa kazi.</p>
                                </div>
                                @endif
                            @else
                                <div class="text-center">
                                    <div class="w-16 h-16 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-4">
                                        <svg class="size-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                    <p class="text-[14px] text-zinc-600 dark:text-zinc-400 mb-5 leading-relaxed">
                                        {!! __('messages.worker_profile.login_prompt') !!}
                                    </p>
                                    <button wire:click="openContactModal" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-zinc-900 to-zinc-800 hover:from-zinc-800 hover:to-zinc-700 dark:from-white dark:to-zinc-100 dark:hover:from-zinc-100 dark:hover:to-zinc-200 text-white dark:text-zinc-900 border border-transparent px-6 py-3.5 text-[15px] font-bold shadow-lg focus:outline-none transition-all transform hover:-translate-y-0.5">
                                        {{ __('messages.worker_profile.view_phone') }}
                                    </button>
                                </div>
                            @endauth
                        </div>
                        
                        {{-- Security Badge --}}
                        <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/40 border-t border-zinc-100 dark:border-zinc-800 flex items-center gap-3">
                            <div class="p-2 bg-white dark:bg-zinc-800 rounded-lg shadow-sm shrink-0 border border-zinc-200 dark:border-zinc-700">
                                <svg class="size-5 text-zinc-500 dark:text-zinc-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-[12px] font-medium text-zinc-500 dark:text-zinc-400 leading-snug">
                                {!! __('messages.worker_profile.escrow_note') !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <flux:modal wire:model.live="showLoginModal" class="max-w-sm p-0 overflow-hidden bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl border border-white/20 dark:border-zinc-800/50 shadow-2xl rounded-2xl">
        <div class="relative w-full p-6 sm:p-7 text-center">
            {{-- Close Button --}}
            <button type="button" wire:click="closeLoginModal" class="absolute top-3 right-3 p-2 rounded-full text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100/80 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/80 transition-all focus:outline-none">
                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2 tracking-tight">{{ __('messages.worker_profile.login_title') }}</h2>
            <p class="text-zinc-500 dark:text-zinc-400 text-[13.5px] mb-6 leading-relaxed">{{ __('messages.worker_profile.login_desc') }}</p>
            
            <div class="flex flex-col gap-3 w-full">
                <a href="{{ route('login') }}" wire:navigate class="w-full">
                    <button class="w-full flex items-center justify-center gap-2 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 px-4 py-2.5 text-[14px] font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-zinc-900/50">
                        {{ __('messages.worker_profile.login_btn') }}
                    </button>
                </a>
                
                <a href="{{ route('register') }}" wire:navigate class="w-full text-center group mt-1">
                    <button class="text-[13.5px] font-semibold text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors flex items-center justify-center gap-1.5 mx-auto">
                        {{ __('messages.worker_profile.register_btn') }}
                        <svg class="size-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </a>
            </div>
        </div>
    </flux:modal>
    
    {{-- CSS for Shimmer Animation --}}
    <style>
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }
    </style>
</div>
