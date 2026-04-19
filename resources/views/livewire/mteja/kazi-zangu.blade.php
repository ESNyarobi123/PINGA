<div>
    {{-- Compact Header with Filters --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.my_jobs.title') }}</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.my_jobs.subtitle') }}</p>
            </div>
            <a href="{{ route('mteja.post-kazi') }}" class="group px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-200" wire:navigate>
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('messages.my_jobs.post_job') }}
                </span>
            </a>
        </div>

        {{-- Modern Filter Pills --}}
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('filter', 'all')" class="group relative px-4 py-2 rounded-full font-medium transition-all duration-200 {{ $filter === 'all' ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg scale-105' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:scale-105 border border-zinc-200 dark:border-zinc-700' }}">
                <span class="relative z-10">{{ __('messages.my_jobs.filter_all') }}</span>
                @if($filter === 'all')
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-full blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                @endif
            </button>
            <button wire:click="$set('filter', 'open')" class="group relative px-4 py-2 rounded-full font-medium transition-all duration-200 {{ $filter === 'open' ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white shadow-lg scale-105' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:scale-105 border border-zinc-200 dark:border-zinc-700' }}">
                <span class="relative z-10">{{ __('messages.my_jobs.filter_open') }}</span>
                @if($filter === 'open')
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                @endif
            </button>
            <button wire:click="$set('filter', 'in_progress')" class="group relative px-4 py-2 rounded-full font-medium transition-all duration-200 {{ $filter === 'in_progress' ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg scale-105' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:scale-105 border border-zinc-200 dark:border-zinc-700' }}">
                <span class="relative z-10">{{ __('messages.my_jobs.filter_in_progress') }}</span>
                @if($filter === 'in_progress')
                <div class="absolute inset-0 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                @endif
            </button>
            <button wire:click="$set('filter', 'completed')" class="group relative px-4 py-2 rounded-full font-medium transition-all duration-200 {{ $filter === 'completed' ? 'bg-gradient-to-r from-blue-500 to-indigo-500 text-white shadow-lg scale-105' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:scale-105 border border-zinc-200 dark:border-zinc-700' }}">
                <span class="relative z-10">{{ __('messages.my_jobs.filter_completed') }}</span>
                @if($filter === 'completed')
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                @endif
            </button>
            <button wire:click="$set('filter', 'cancelled')" class="group relative px-4 py-2 rounded-full font-medium transition-all duration-200 {{ $filter === 'cancelled' ? 'bg-gradient-to-r from-red-500 to-pink-500 text-white shadow-lg scale-105' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:scale-105 border border-zinc-200 dark:border-zinc-700' }}">
                <span class="relative z-10">{{ __('messages.my_jobs.filter_cancelled') }}</span>
                @if($filter === 'cancelled')
                <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-pink-500 rounded-full blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                @endif
            </button>
        </div>
    </div>

    {{-- Jobs List - Compact Modern Cards --}}
    <div class="space-y-3">
        @forelse($jobs as $job)
        <div class="group bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-start justify-between gap-4 mb-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white truncate group-hover:text-emerald-600 transition-colors">{{ $job->getLocalizedTitle() }}</h3>
                            <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $job->status === 'open' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                {{ $job->status === 'in_progress' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                {{ $job->status === 'completed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                {{ $job->status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                {{ $job->status === 'draft' ? 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-400' : '' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-1 mb-2">{{ $job->getLocalizedDescription() }}</p>
                        
                        <div class="flex flex-wrap gap-3 text-xs text-zinc-500 dark:text-zinc-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ $job->location ?? 'Remote' }}
                            </span>
                            <span class="flex items-center gap-1 font-semibold text-emerald-600 dark:text-emerald-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ number_format($job->budget_min ?? 0) }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ $job->applications_count ?? 0 }} {{ __('messages.my_jobs.applications') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $job->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    @if($job->status === 'open')
                        <a href="{{ route('mteja.maombi', ['job_id' => $job->id]) }}" class="group/btn px-3 py-1.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-xs font-medium rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200" wire:navigate>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 group-hover/btn:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('messages.my_jobs.applications') }} ({{ $job->applications_count ?? 0 }})
                            </span>
                        </a>
                    @endif

                    @if($job->status === 'in_progress')
                        @if(isset($generatedCodes[$job->id]))
                            <div class="flex items-center gap-2">
                                <div class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-lg font-mono font-bold text-sm">
                                    {{ $generatedCodes[$job->id] }}
                                </div>
                                <span class="text-xs text-zinc-500">← {{ __('messages.my_jobs.code') }}</span>
                            </div>
                        @else
                            <button wire:click="generateCode({{ $job->id }})" class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 hover:scale-105 transition-all duration-200">
                                {{ __('messages.my_jobs.generate_code') }}
                            </button>
                            <button wire:click="holdCode({{ $job->id }})" class="px-3 py-1.5 bg-amber-600 text-white text-xs font-medium rounded-lg hover:bg-amber-700 hover:scale-105 transition-all duration-200">
                                {{ __('messages.my_jobs.hold') }}
                            </button>
                        @endif
                    @endif

                    @if($job->status === 'completed')
                        <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 text-xs font-medium">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('messages.my_jobs.completed') }}
                        </div>
                    @endif

                    <a href="{{ route('mteja.kazi-detail', $job->id) }}" class="ml-auto px-3 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 hover:scale-105 transition-all duration-200" wire:navigate>
                        {{ __('messages.my_jobs.details') }}
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.my_jobs.no_jobs') }}</h3>
            <p class="text-zinc-500 dark:text-zinc-400 mb-4">
                @if($filter === 'all')
                    {{ __('messages.my_jobs.no_jobs_desc') }}
                @else
                    {{ __('messages.my_jobs.no_jobs_filter', ['filter' => $filter]) }}
                @endif
            </p>
            @if($filter === 'all')
            <a href="{{ route('mteja.post-kazi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors" wire:navigate>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('messages.my_jobs.post_new') }}
            </a>
            @endif
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($jobs->hasPages())
    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
    @endif
</div>
