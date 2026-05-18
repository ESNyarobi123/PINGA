<div wire:init="loadData">
    <livewire:shared.announcement-modal scope="mteja" />

    {{-- Compact Welcome Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.mteja_dash.welcome', ['name' => auth()->user()->name]) }}</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.mteja_dash.subtitle') }}</p>
            </div>
            <a href="{{ route('mteja.post-kazi') }}" class="group inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200" wire:navigate>
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('messages.mteja_dash.post_new_job') }}
            </a>
        </div>
    </div>

    {{-- Compact Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Jobs --}}
        <div class="group bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-700 hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="flex-1">
                    @if($showSkeleton)
                        <div class="h-6 bg-zinc-200 dark:bg-zinc-700 rounded w-12 animate-pulse"></div>
                    @else
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['total_kazi'] ?? 0 }}</p>
                    @endif
                    <p class="text-xs text-zinc-500">{{ __('messages.mteja_dash.total_jobs') }}</p>
                </div>
            </div>
        </div>

        {{-- Active Jobs --}}
        <div class="group bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm hover:shadow-lg hover:border-emerald-300 dark:hover:border-emerald-700 hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    @if($showSkeleton)
                        <div class="h-6 bg-zinc-200 dark:bg-zinc-700 rounded w-12 animate-pulse"></div>
                    @else
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['kazi_active'] ?? 0 }}</p>
                    @endif
                    <p class="text-xs text-zinc-500">{{ __('messages.mteja_dash.open_jobs') }}</p>
                </div>
            </div>
        </div>

        {{-- New Applications --}}
        <div class="group bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm hover:shadow-lg hover:border-amber-300 dark:hover:border-amber-700 hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    @if($showSkeleton)
                        <div class="h-6 bg-zinc-200 dark:bg-zinc-700 rounded w-12 animate-pulse"></div>
                    @else
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['maombi_mapya'] ?? 0 }}</p>
                    @endif
                    <p class="text-xs text-zinc-500">{{ __('messages.mteja_dash.new_applications') }}</p>
                </div>
            </div>
        </div>

        {{-- Wallet Balance --}}
        <div class="group bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm hover:shadow-lg hover:border-purple-300 dark:hover:border-purple-700 hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    @if($showSkeleton)
                        <div class="h-6 bg-zinc-200 dark:bg-zinc-700 rounded w-20 animate-pulse"></div>
                    @else
                        <p class="text-lg font-bold text-zinc-900 dark:text-white truncate">{{ number_format($stats['wallet'] ?? 0) }}</p>
                    @endif
                    <p class="text-xs text-zinc-500">Wallet</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Jobs (2 columns) --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <div class="p-5 border-b border-zinc-200 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white inline-flex items-center gap-2">
                            <x-fluent-icon name="clipboard-24" :size="24" />
                            {{ __('messages.mteja_dash.recent_jobs') }}
                        </h2>
                        <a href="{{ route('mteja.kazi-zangu') }}" class="text-sm text-winga-600 hover:text-winga-500 font-medium" wire:navigate>
                            {{ __('messages.mteja_dash.view_all') }}
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @if($recentJobs && $recentJobs->count() > 0)
                        @foreach($recentJobs->take(5) as $job)
                        <div class="group p-5 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 hover:border-l-4 hover:border-l-emerald-500 transition-all duration-200 cursor-pointer">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-2 group-hover:text-emerald-600 transition-colors">{{ $job->getLocalizedTitle() }}</h3>
                                    <div class="flex flex-wrap items-center gap-3 text-sm text-zinc-600 dark:text-zinc-300 mb-3">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-{{ $job->status === 'open' ? 'emerald' : ($job->status === 'in_progress' ? 'amber' : 'blue') }}-100 text-{{ $job->status === 'open' ? 'emerald' : ($job->status === 'in_progress' ? 'amber' : 'blue') }}-700 text-xs font-semibold">
                                            {{ $job->status }}
                                        </span>
                                        <span class="flex items-center gap-1.5 text-zinc-600 dark:text-zinc-300">
                                            <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            {{ $job->location ?? 'Remote' }}
                                        </span>
                                        <span class="flex items-center gap-1.5 font-medium text-zinc-700 dark:text-zinc-200">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            TZS {{ number_format($job->budget_min ?? 0) }}
                                        </span>
                                    </div>
                                    @if($job->skills && $job->skills->count() > 0)
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($job->skills->take(3) as $skill)
                                        <span class="inline-flex items-center rounded-md bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200 text-xs font-medium px-2.5 py-1">
                                            {{ $skill->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('mteja.maombi', ['job_id' => $job->id]) }}" class="group/btn inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-xl hover:scale-105 hover:from-emerald-700 hover:to-teal-700 transition-all duration-200" wire:navigate>
                                        <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        {{ $job->applications_count ?? 0 }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.mteja_dash.no_jobs_title') }}</h3>
                            <p class="text-zinc-500 dark:text-zinc-400 mb-4">{{ __('messages.mteja_dash.no_jobs_desc') }}</p>
                            <a href="{{ route('mteja.post-kazi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors" wire:navigate>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ __('messages.mteja_dash.post_job') }}
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
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-4 inline-flex items-center gap-2">
                    <x-fluent-icon name="options-24" :size="24" />
                    {{ __('messages.mteja_dash.quick_actions') }}
                </h2>
                <div class="space-y-3">
                    <a href="{{ route('mteja.post-kazi') }}" class="w-full flex items-center gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors" wire:navigate>
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="flex-1 text-left">
                            <p class="font-medium text-zinc-900 dark:text-white">{{ __('messages.mteja_dash.post_new_job') }}</p>
                            <p class="text-sm text-zinc-500">{{ __('messages.mteja_dash.post_new_job_desc') }}</p>
                        </div>
                    </a>

                    <a href="{{ route('mteja.huduma') }}" class="w-full flex items-center gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors" wire:navigate>
                        <div class="w-10 h-10 rounded-lg bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center">
                            <x-fluent-icon name="clipboard-24" :size="22" class="text-teal-600 dark:text-teal-400" />
                        </div>
                        <div class="flex-1 text-left">
                            <p class="font-medium text-zinc-900 dark:text-white">{{ __('messages.mteja_dash.browse_services') }}</p>
                            <p class="text-sm text-zinc-500">{{ __('messages.mteja_dash.browse_services_desc') }}</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('mteja.maombi') }}" class="w-full flex items-center gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors" wire:navigate>
                        <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 text-left">
                            <p class="font-medium text-zinc-900 dark:text-white">{{ __('messages.mteja_dash.view_applications') }}</p>
                            <p class="text-sm text-zinc-500">{{ __('messages.mteja_dash.verify_workers') }}</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('mteja.kazi-zangu') }}" class="w-full flex items-center gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors" wire:navigate>
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="flex-1 text-left">
                            <p class="font-medium text-zinc-900 dark:text-white">{{ __('messages.mteja_dash.my_jobs') }}</p>
                            <p class="text-sm text-zinc-500">{{ __('messages.mteja_dash.manage_jobs') }}</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('mteja.wallet') }}" class="w-full flex items-center gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors" wire:navigate>
                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1 text-left">
                            <p class="font-medium text-zinc-900 dark:text-white">{{ __('messages.mteja_dash.my_wallet') }}</p>
                            <p class="text-sm text-zinc-500">{{ __('messages.mteja_dash.view_balance') }}</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Tips Card --}}
            <div class="bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 inline-flex items-center gap-2">
                    <x-fluent-icon name="lightbulb-24" :size="24" />
                    {{ __('messages.mteja_dash.tips_title') }}
                </h2>
                <ul class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 shrink-0"><x-fluent-icon name="checkmark-circle-16" :size="16" /></span>
                        <span>{{ __('messages.mteja_dash.tip_1') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 shrink-0"><x-fluent-icon name="checkmark-circle-16" :size="16" /></span>
                        <span>{{ __('messages.mteja_dash.tip_2') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 shrink-0"><x-fluent-icon name="checkmark-circle-16" :size="16" /></span>
                        <span>{{ __('messages.mteja_dash.tip_3') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
