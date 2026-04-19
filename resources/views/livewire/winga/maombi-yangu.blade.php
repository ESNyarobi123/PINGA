<div>
    {{-- Page Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.maombi_yangu.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.maombi_yangu.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:select wire:model.live.debounce.300ms="filter" class="w-full sm:w-auto">
                    <option value="all">{{ __('messages.maombi_yangu.filter_all') }} ({{ $counts['all'] }})</option>
                    <option value="pending">{{ __('messages.maombi_yangu.filter_pending') }} ({{ $counts['pending'] }})</option>
                    <option value="accepted">{{ __('messages.maombi_yangu.filter_accepted') }} ({{ $counts['accepted'] }})</option>
                    <option value="rejected">{{ __('messages.maombi_yangu.filter_rejected') }} ({{ $counts['rejected'] }})</option>
                    <option value="withdrawn">{{ __('messages.maombi_yangu.filter_withdrawn') }} ({{ $counts['withdrawn'] }})</option>
                </flux:select>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $counts['all'] }}</p>
                <p class="text-sm text-zinc-500">{{ __('messages.maombi_yangu.total') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-amber-600">{{ $counts['pending'] }}</p>
                <p class="text-sm text-zinc-500">{{ __('messages.maombi_yangu.filter_pending') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $counts['accepted'] }}</p>
                <p class="text-sm text-zinc-500">{{ __('messages.maombi_yangu.filter_accepted') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600">{{ $counts['rejected'] }}</p>
                <p class="text-sm text-zinc-500">{{ __('messages.maombi_yangu.filter_rejected') }}</p>
            </div>
        </div>
    </div>

    {{-- Applications List --}}
    @if($applications->count() > 0)
    <div class="space-y-4">
        @foreach($applications as $application)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4 sm:p-6 hover:shadow-lg transition-shadow">
            <div class="flex flex-col lg:flex-row items-start justify-between gap-4 lg:gap-6">
                <div class="flex-1 w-full min-w-0">
                    <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                        <div class="w-12 h-12 rounded-xl bg-winga-100 dark:bg-winga-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-winga-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base sm:text-lg font-semibold text-zinc-900 dark:text-white mb-2">
                                <a href="{{ route('winga.kazi-detail', ['slug' => $application->job->slug]) }}" class="hover:text-winga-600 transition-colors" wire:navigate>
                                    {{ $application->job->title }}
                                </a>
                            </h3>
                            <p class="text-zinc-600 dark:text-zinc-400 mb-4 line-clamp-2 text-sm sm:text-base">{{ $application->job->description }}</p>
                            
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $application->job->location ?? 'Remote' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    TZS {{ number_format($application->proposed_budget ?? $application->job->budget_min ?? 0) }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $application->created_at->diffForHumans() }}
                                </span>
                            </div>

                            @if($application->cover_letter)
                            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-3 mb-4">
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ Str::limit($application->cover_letter, 150) }}</p>
                            </div>
                            @endif

                            <div class="flex flex-wrap items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                        <span class="text-xs font-bold text-zinc-600 dark:text-zinc-300">{{ $application->job->employer->initials() }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $application->job->employer->name }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 inline-flex items-center gap-1">
                                        <x-fluent-icon name="star-16" :size="14" />
                                        {{ $application->job->employer->averageRating() ?? 0 }}
                                    </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-row sm:flex-col gap-2 w-full sm:w-auto sm:min-w-[140px] flex-shrink-0 items-center sm:items-stretch">
                    {{-- Status Badge --}}
                    <div class="flex-shrink-0">
                        @switch($application->status)
                            @case('pending')
                                <span class="inline-flex items-center rounded-md bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-medium px-2.5 py-1">
                                    {{ __('messages.maombi_yangu.status_pending') }}
                                </span>
                                @break
                            @case('accepted')
                                <span class="inline-flex items-center rounded-md bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium px-2.5 py-1">
                                    {{ __('messages.maombi_yangu.status_accepted') }}
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center rounded-md bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-medium px-2.5 py-1">
                                    {{ __('messages.maombi_yangu.status_rejected') }}
                                </span>
                                @break
                            @case('withdrawn')
                                <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 text-xs font-medium px-2.5 py-1">
                                    {{ __('messages.maombi_yangu.status_withdrawn') }}
                                </span>
                                @break
                        @endswitch
                    </div>
                    
                    {{-- Action Buttons --}}
                    @if($application->status === 'pending')
                    <button wire:click="withdraw({{ $application->id }})" class="flex-1 sm:flex-none px-3 py-1.5 border border-red-300 dark:border-red-600 text-red-700 dark:text-red-400 text-xs font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-center">
                        {{ __('messages.maombi_yangu.withdraw') }}
                    </button>
                    @endif
                    
                    <a href="{{ route('winga.kazi-detail', ['slug' => $application->job->slug]) }}" class="flex-1 sm:flex-none px-3 py-1.5 bg-winga-600 text-white text-xs font-medium rounded-lg hover:bg-winga-700 transition-colors text-center" wire:navigate>
                        {{ __('messages.maombi_yangu.view_job') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $applications->links() }}
    </div>
    @else
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
            <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.maombi_yangu.no_applications') }}</h3>
        <p class="text-zinc-500 dark:text-zinc-400 mb-4">{{ __('messages.maombi_yangu.no_applications_desc') }}</p>
        <a href="{{ route('winga.kazi-karibu') }}" class="px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors" wire:navigate>
            {{ __('messages.maombi_yangu.search_jobs') }}
        </a>
    </div>
    @endif
</div>
