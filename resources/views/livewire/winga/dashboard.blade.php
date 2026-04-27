<div wire:init="loadData" wire:poll.30s="loadData">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-winga-600 to-winga-500 rounded-2xl p-4 mb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
            </svg>
        </div>
        <div class="relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold mb-1">{{ __('messages.winga_dash.welcome', ['name' => auth()->user()->name]) }}</h1>
                    <p class="text-winga-100 text-sm sm:text-base">{{ __('messages.winga_dash.subtitle') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-2 py-1">
                        <span class="text-xs font-semibold inline-flex items-center gap-1">
                            <x-fluent-icon name="star-20" :size="16" />
                            {{ auth()->user()->averageRating() ?? 0 }} {{ __('messages.winga_dash.rating') }}
                        </span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-2 py-1">
                        <span class="text-xs font-semibold inline-flex items-center gap-1">
                            <x-fluent-icon name="data-bar-vertical-ascending-20" :size="16" />
                            {{ $stats['kazi_zilizomalika'] ?? 0 }} {{ __('messages.winga_dash.completed_jobs') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Available Jobs --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['kazi_karibu'] ?? 0 }}</p>
                    <p class="text-sm text-zinc-500">{{ __('messages.winga_dash.nearby_jobs') }}</p>
                </div>
            </div>
            <div class="text-xs text-blue-600 font-medium inline-flex items-center gap-1">
                <x-fluent-icon name="data-trending-20" :size="16" />
                {{ __('messages.winga_dash.available_now') }}
            </div>
        </div>

        {{-- Active Applications --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['maombi_active'] ?? 0 }}</p>
                    <p class="text-sm text-zinc-500">{{ __('messages.winga_dash.your_applications') }}</p>
                </div>
            </div>
            <div class="text-xs text-amber-600 font-medium inline-flex items-center gap-1">
                <x-fluent-icon name="clock-20" :size="16" />
                {{ __('messages.winga_dash.active') }}
            </div>
        </div>

        {{-- Total Earnings --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['mapato_jumla'] ?? 0) }}</p>
                    <p class="text-sm text-zinc-500">{{ __('messages.winga_dash.total_earnings') }}</p>
                </div>
            </div>
            <div class="text-xs text-green-600 font-medium inline-flex items-center gap-1">
                <x-fluent-icon name="coin-multiple-20" :size="16" />
                {{ __('messages.winga_dash.so_far') }}
            </div>
        </div>

        {{-- Wallet Balance --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format(auth()->user()->wallet_balance ?? 0) }}</p>
                    <p class="text-sm text-zinc-500">{{ __('messages.winga_dash.wallet_balance') }}</p>
                </div>
            </div>
            <div class="text-xs text-purple-600 font-medium inline-flex items-center gap-1">
                <x-fluent-icon name="savings-20" :size="16" />
                {{ __('messages.winga_dash.loaded') }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Jobs (2 columns) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Ongoing Job Alert --}}
            @if($ongoingJob)
            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <x-fluent-icon name="data-trending-24" :size="28" class="drop-shadow-sm" />
                        <h2 class="text-xl font-bold">{{ __('messages.winga_dash.ongoing_job') }}</h2>
                    </div>
                    <p class="text-orange-100 mb-4">{{ $ongoingJob->getLocalizedTitle() }}</p>
                    <a href="{{ route('winga.weka-code') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-orange-600 font-bold rounded-xl hover:bg-orange-50 transition-colors" wire:navigate>
                        {{ __('messages.winga_dash.enter_payment_code') }}
                    </a>
                </div>
            </div>
            @endif

            {{-- Recent Jobs List --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white inline-flex items-center gap-2">
                            <x-fluent-icon name="data-trending-24" :size="24" />
                            {{ __('messages.winga_dash.new_available_jobs') }}
                        </h2>
                        <a href="{{ route('tafuta-kazi') }}" class="text-sm text-winga-600 hover:text-winga-500 font-medium" wire:navigate>
                            {{ __('messages.winga_dash.see_more') }}
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @if($recentJobs && $recentJobs->count() > 0)
                        @foreach($recentJobs->take(5) as $job)
                        <div class="p-6 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-zinc-900 dark:text-white mb-1">{{ $job->getLocalizedTitle() }}</h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-zinc-500 dark:text-zinc-400 mb-3">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $job->location ?? 'Remote' }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            TZS {{ number_format($job->budget_min ?? 0) }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $job->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    @if($job->skills)
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($job->skills->take(3) as $skill)
                                        <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-xs font-medium px-2 py-1">
                                            {{ $skill->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('winga.kazi-detail', ['slug' => $job->slug, 'action' => 'apply']) }}" class="px-4 py-2 bg-winga-600 text-white text-sm font-medium rounded-lg hover:bg-winga-700 transition-colors inline-block text-center" wire:navigate>
                                        {{ __('messages.winga_dash.apply') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                            <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.winga_dash.no_new_jobs_title') }}</h3>
                        <p class="text-zinc-500 dark:text-zinc-400 mb-4">{{ __('messages.winga_dash.no_new_jobs_desc') }}</p>
                        <a href="{{ route('tafuta-kazi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors" wire:navigate>
                            {{ __('messages.winga_dash.search_more_jobs') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Actions (1 column) --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-5 inline-flex items-center gap-2">
                    <x-fluent-icon name="options-24" :size="24" />
                    {{ __('messages.winga_dash.quick_actions') }}
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    {{-- Find Jobs --}}
                    <a href="{{ route('tafuta-kazi') }}" class="group relative flex flex-col items-center gap-2.5 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:border-blue-300 dark:hover:border-blue-700 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" wire:navigate>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-sm shadow-blue-200 dark:shadow-blue-900/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-sm text-zinc-900 dark:text-white leading-tight">{{ __('messages.winga_dash.qa_find_jobs_title') }}</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5 leading-tight">{{ __('messages.winga_dash.qa_find_jobs_desc') }}</p>
                        </div>
                    </a>

                    {{-- Accepted Applications --}}
                    <a href="{{ route('winga.maombi-yangu', ['filter' => 'accepted']) }}" class="group relative flex flex-col items-center gap-2.5 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:border-emerald-300 dark:hover:border-emerald-700 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" wire:navigate>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-sm shadow-emerald-200 dark:shadow-emerald-900/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-sm text-zinc-900 dark:text-white leading-tight">{{ __('messages.winga_dash.qa_accepted_apps') }}</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5 leading-tight">{{ __('messages.winga_dash.qa_accepted_apps_desc') }}</p>
                        </div>
                    </a>

                    {{-- My Services --}}
                    <a href="{{ route('winga.huduma-zangu') }}" class="group relative flex flex-col items-center gap-2.5 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:border-teal-300 dark:hover:border-teal-700 hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" wire:navigate>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 shadow-sm shadow-teal-200 dark:shadow-teal-900/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-sm text-zinc-900 dark:text-white leading-tight">{{ __('messages.winga_dash.qa_my_services') }}</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5 leading-tight">{{ __('messages.winga_dash.qa_my_services_desc') }}</p>
                        </div>
                    </a>

                    {{-- Client Requests --}}
                    <a href="{{ route('winga.huduma-maombi') }}" class="group relative flex flex-col items-center gap-2.5 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:border-indigo-300 dark:hover:border-indigo-700 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" wire:navigate>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm shadow-indigo-200 dark:shadow-indigo-900/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-sm text-zinc-900 dark:text-white leading-tight">{{ __('messages.winga_dash.qa_client_requests') }}</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5 leading-tight">{{ __('messages.winga_dash.qa_client_requests_desc') }}</p>
                        </div>
                    </a>

                    {{-- Portfolio --}}
                    <a href="{{ route('winga.portfolio') }}" class="group relative flex flex-col items-center gap-2.5 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:border-green-300 dark:hover:border-green-700 hover:bg-green-50/50 dark:hover:bg-green-900/10 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" wire:navigate>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 shadow-sm shadow-green-200 dark:shadow-green-900/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-sm text-zinc-900 dark:text-white leading-tight">{{ __('messages.winga_dash.portfolio') }}</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5 leading-tight">{{ __('messages.winga_dash.view_work') }}</p>
                        </div>
                    </a>

                    {{-- Enter Payment Code --}}
                    <a href="{{ route('winga.weka-code') }}" class="group relative flex flex-col items-center gap-2.5 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:border-purple-300 dark:hover:border-purple-700 hover:bg-purple-50/50 dark:hover:bg-purple-900/10 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" wire:navigate>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 shadow-sm shadow-purple-200 dark:shadow-purple-900/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-sm text-zinc-900 dark:text-white leading-tight">{{ __('messages.winga_dash.enter_code') }}</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5 leading-tight">{{ __('messages.winga_dash.receive_payment') }}</p>
                        </div>
                    </a>
                </div>

                {{-- Quick Links Bar --}}
                <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-800 flex flex-wrap items-center justify-center gap-x-4 gap-y-2">
                    <a href="{{ route('winga.kazi-karibu') }}" class="text-xs font-medium text-winga-600 hover:text-winga-500 dark:text-winga-400 inline-flex items-center gap-1 transition-colors" wire:navigate>
                        <x-fluent-icon name="location-20" :size="14" />
                        {{ __('messages.winga_dash.qa_nearby_link') }}
                    </a>
                    <span class="text-zinc-300 dark:text-zinc-700">|</span>
                    <a href="{{ route('winga.post-huduma') }}" class="text-xs font-medium text-winga-600 hover:text-winga-500 dark:text-winga-400 inline-flex items-center gap-1 transition-colors" wire:navigate>
                        <x-fluent-icon name="add-20" :size="14" />
                        {{ __('messages.winga_dash.qa_add_service_link') }}
                    </a>
                    <span class="text-zinc-300 dark:text-zinc-700">|</span>
                    <a href="{{ route('winga.maombi-yangu') }}" class="text-xs font-medium text-winga-600 hover:text-winga-500 dark:text-winga-400 inline-flex items-center gap-1 transition-colors" wire:navigate>
                        <x-fluent-icon name="arrow-right-20" :size="14" />
                        {{ __('messages.winga_dash.qa_all_applications_link') }}
                    </a>
                </div>
            </div>

            {{-- Weekly Stats --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-4 inline-flex items-center gap-2">
                    <x-fluent-icon name="data-bar-vertical-ascending-24" :size="24" />
                    {{ __('messages.winga_dash.weekly_stats') }}
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-500">{{ __('messages.winga_dash.applications_week') }}</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $stats['maombi_wiki'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-500">{{ __('messages.winga_dash.accepted_week') }}</span>
                        <span class="font-bold text-green-600">{{ $stats['kukubaliwa_wiki'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-500">{{ __('messages.winga_dash.earnings_week') }}</span>
                        <span class="font-bold text-green-600">TZS {{ number_format($stats['mapato_wiki'] ?? 0) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
