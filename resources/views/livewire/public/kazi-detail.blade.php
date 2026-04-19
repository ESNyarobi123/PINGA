@php
    $job = $this->job;
    $employer = $job->employer;
    $priceType = $job->budget_type === 'hourly' ? __('messages.job_detail.hourly') : __('messages.job_detail.fixed');
    $price = $job->budget_max
        ? 'TZS '.number_format($job->budget_min).' – '.number_format($job->budget_max).($job->budget_type === 'hourly' ? ' /hr' : '')
        : __('messages.job_detail.under').' '.number_format($job->budget_min ?? 0);
    $deadline = $job->created_at->copy()->addDays(14)->locale('sw');
    $deadlineLabel = __('messages.job_detail.submit_before').' '.$deadline->day.' '.$deadline->translatedFormat('F').' '.$deadline->year;
@endphp
<div class="min-h-screen bg-zinc-50/50 dark:bg-zinc-950 pb-12">
    {{-- Hero Section --}}
    <header class="relative bg-white dark:bg-zinc-900 border-b border-zinc-200/80 dark:border-zinc-800/80 pt-8 pb-10 sm:pt-10 sm:pb-12 overflow-hidden shadow-sm">
        {{-- Background Pattern/Gradient --}}
        <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-winga-50/50 to-transparent dark:from-winga-900/10 dark:to-transparent pointer-events-none"></div>
        
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb & Back Link --}}
            <nav class="flex items-center gap-2 text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-6 sm:mb-8">
                <a href="{{ route('tafuta-kazi') }}" class="inline-flex items-center gap-1.5 hover:text-winga-600 dark:hover:text-winga-400 transition-colors" wire:navigate>
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('messages.job_detail.search_jobs') }}
                </a>
                <span class="text-zinc-300 dark:text-zinc-700">/</span>
                <span class="text-zinc-400 dark:text-zinc-500 truncate max-w-[200px] sm:max-w-xs">{{ $job->category->name ?? __('messages.job_detail.category') }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1">
                    {{-- Title and Badges --}}
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-2 mb-4">
                        @if($job->urgency && in_array($job->urgency, ['urgent', 'very_urgent']))
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-500/10 text-red-700 dark:text-red-400 px-3 py-1 text-xs font-bold border border-red-500/20">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ __('messages.job_detail.very_urgent') }}
                        </span>
                        @endif
                        @if($job->category)
                        <span class="inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 px-3 py-1 text-xs font-semibold border border-zinc-200/60 dark:border-zinc-700/60">
                            {{ $job->category->name }}
                        </span>
                        @endif
                        <span class="inline-flex items-center rounded-full bg-winga-50 dark:bg-winga-900/20 text-winga-700 dark:text-winga-400 px-3 py-1 text-xs font-semibold border border-winga-200/50 dark:border-winga-800/50">
                            {{ $job->applications_count ?? 0 }} {{ __('messages.job_detail.applications') }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-[2.25rem] font-extrabold text-zinc-900 dark:text-white tracking-tight leading-tight mb-5">
                        {{ $job->getLocalizedTitle() }}
                    </h1>

                    {{-- Meta Info --}}
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-[14px] text-zinc-600 dark:text-zinc-400 font-medium">
                        <span class="inline-flex items-center gap-2">
                            <div class="p-1.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            {{ $job->location ?? __('messages.job_detail.location_unknown') }}
                        </span>
                        <span class="inline-flex items-center gap-2">
                            <div class="p-1.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            {{ __('messages.job_detail.posted') }} {{ $job->created_at->diffForHumans() }}
                        </span>
                        @if($job->duration)
                        <span class="inline-flex items-center gap-2">
                            <div class="p-1.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            </div>
                            {{ __('messages.job_detail.duration') }} {{ $job->duration }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons (Desktop/Tablet Top Right) --}}
                <div class="flex items-center gap-3 lg:shrink-0 mt-2 lg:mt-0">
                    <button type="button" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-sm font-semibold text-zinc-700 dark:text-zinc-300 shadow-sm hover:bg-zinc-50 dark:hover:bg-zinc-800 hover:text-amber-600 dark:hover:text-amber-500 hover:border-amber-200 dark:hover:border-amber-800/50 transition-all focus:outline-none ring-1 ring-zinc-900/5">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        <span class="hidden sm:inline">{{ __('messages.job_detail.save') }}</span>
                    </button>
                    <button type="button" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-sm font-semibold text-zinc-700 dark:text-zinc-300 shadow-sm hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-all focus:outline-none ring-1 ring-zinc-900/5">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        <span class="hidden sm:inline">{{ __('messages.job_detail.share') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content & Sidebar Layout --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
            
            {{-- Left Column: Details --}}
            <div class="lg:col-span-8 space-y-6">
                
                {{-- Single Unified Body for Description --}}
                <article class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-sm overflow-hidden flex flex-col">
                    
                    {{-- Section 1: Maelezo --}}
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="size-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.job_detail.full_description') }}</h2>
                        </div>
                        <div class="prose prose-zinc dark:prose-invert max-w-none text-zinc-700 dark:text-zinc-300 text-[15px] leading-relaxed whitespace-pre-line">
                            {{ $job->getLocalizedDescription() }}
                        </div>
                    </div>

                    {{-- Sub-section Dividers --}}
                    @if($job->requirements)
                    <div class="px-6 sm:px-8">
                        <hr class="border-zinc-100 dark:border-zinc-800/80">
                    </div>
                    
                    {{-- Section 2: Mahitaji --}}
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="size-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.job_detail.requirements') }}</h2>
                        </div>
                        <div class="prose prose-zinc dark:prose-invert max-w-none text-zinc-700 dark:text-zinc-300 text-[15px] leading-relaxed whitespace-pre-line">
                            {{ $job->getLocalizedRequirements() }}
                        </div>
                    </div>
                    @endif

                    @if($job->skills->isNotEmpty())
                    <div class="px-6 sm:px-8">
                        <hr class="border-zinc-100 dark:border-zinc-800/80">
                    </div>

                    {{-- Section 3: Ujuzi --}}
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="size-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.job_detail.skills_needed') }}</h2>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($job->skills as $skill)
                            <span class="inline-flex items-center rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-[13px] font-semibold px-4 py-2 border border-zinc-200/60 dark:border-zinc-700">
                                {{ $skill->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </article>
            </div>

            {{-- Right Column: Sidebar (Pricing, Client info and CTA) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-6 space-y-6">
                    
                    {{-- Main Call-To-Action Card --}}
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 shadow-md shadow-zinc-200/30 dark:shadow-none overflow-hidden relative">
                        
                        {{-- Top Pattern decoration --}}
                        <div class="h-2 w-full bg-gradient-to-r from-winga-500 to-winga-400"></div>

                        <div class="p-6 sm:p-8">
                            <div class="flex items-center gap-2 text-zinc-500 dark:text-zinc-400 mb-2">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-[13px] font-bold uppercase tracking-wider">{{ $priceType }}</span>
                            </div>
                            
                            <h3 class="text-[1.8rem] sm:text-[2rem] font-extrabold text-zinc-900 dark:text-white tracking-tight leading-none mb-4">
                                {{ $price }}
                            </h3>

                            <div class="flex items-center gap-2 py-3 px-4 rounded-xl bg-amber-50/50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 mb-6">
                                <svg class="size-5 text-amber-600 dark:text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-xs font-medium text-amber-800 dark:text-amber-300">
                                    {{ $deadlineLabel }}
                                </p>
                            </div>
                            
                            <button wire:click="openApplyModal" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-zinc-900 to-zinc-800 hover:from-zinc-800 hover:to-zinc-700 dark:from-white dark:to-zinc-100 dark:hover:from-zinc-100 dark:hover:to-zinc-200 text-white dark:text-zinc-900 border border-transparent px-6 py-4 text-[16px] font-bold shadow-lg focus:outline-none focus:ring-2 focus:ring-zinc-900/50 focus:ring-offset-2 transition-all transform hover:-translate-y-0.5 relative group overflow-hidden">
                                {{-- Shimmer effect on hover --}}
                                <div class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/20 dark:via-zinc-900/10 to-transparent"></div>
                                <span class="relative">{{ __('messages.job_detail.apply_now') }}</span>
                                <svg class="size-5 relative" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                        
                        {{-- Client/Employer Info --}}
                        <div class="p-6 bg-zinc-50 dark:bg-zinc-800/40 border-t border-zinc-100 dark:border-zinc-800">
                            <p class="text-[12px] font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 mb-3 ml-1">{{ __('messages.job_detail.about_employer') }}</p>
                            
                            <div class="flex items-center gap-4">
                                <img src="{{ $employer && $employer->avatar ? asset('storage/'.$employer->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($employer->name ?? 'U').'&background=0d9488&color=fff&size=80' }}" alt="" class="size-12 rounded-full object-cover ring-2 ring-white dark:ring-zinc-900 shadow-sm shrink-0" />
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-[15px] text-zinc-900 dark:text-white truncate">
                                        {{ $employer->name ?? __('messages.job_detail.anonymous_employer') }}
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <svg class="size-3.5 text-zinc-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <p class="text-[13px] font-medium text-emerald-600 dark:text-emerald-400">{{ __('messages.job_detail.verified') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Small Trust/Protection Badge --}}
                    <div class="flex items-center justify-center gap-2 text-zinc-400 dark:text-zinc-500 px-4">
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <p class="text-[12px] font-medium text-center">{{ __('messages.job_detail.escrow_note') }}</p>
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
            
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2 tracking-tight">{{ __('messages.job_detail.login_title') }}</h2>
            <p class="text-zinc-500 dark:text-zinc-400 text-[13.5px] mb-6 leading-relaxed">{{ __('messages.job_detail.login_desc') }}</p>
            
            <div class="flex flex-col gap-3 w-full">
                <a href="{{ route('login') }}" wire:navigate class="w-full">
                    <button class="w-full flex items-center justify-center gap-2 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 px-4 py-2.5 text-[14px] font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-zinc-900/50">
                        {{ __('messages.job_detail.login_btn') }}
                    </button>
                </a>
                
                <a href="{{ route('register') }}" wire:navigate class="w-full text-center group mt-1">
                    <button class="text-[13.5px] font-semibold text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors flex items-center justify-center gap-1.5 mx-auto">
                        {{ __('messages.job_detail.register_btn') }}
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
