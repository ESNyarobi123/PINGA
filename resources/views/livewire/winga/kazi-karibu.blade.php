<div>
    {{-- Page Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.kazi_karibu.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.kazi_karibu.subtitle') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:select wire:model="sortBy" class="w-full sm:w-auto">
                    <option value="latest">{{ __('messages.kazi_karibu.sort_latest') }}</option>
                    <option value="budget_high">{{ __('messages.kazi_karibu.sort_budget_high') }}</option>
                    <option value="budget_low">{{ __('messages.kazi_karibu.sort_budget_low') }}</option>
                </flux:select>
            </div>
        </div>
    </div>

    {{-- Search and Filters --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                type="search" 
                placeholder="{{ __('messages.kazi_karibu.search_placeholder') }}" 
                icon="magnifying-glass"
            />
            <flux:select wire:model.live.debounce.300ms="category" class="w-full">
                <option value="">{{ __('messages.kazi_karibu.category_all') }}</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live.debounce.300ms="location" class="w-full">
                <option value="">{{ __('messages.kazi_karibu.location_all') }}</option>
                @foreach($locations as $location)
                <option value="{{ $location }}">{{ $location }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live.debounce.300ms="budget" class="w-full">
                <option value="">{{ __('messages.kazi_karibu.budget_all') }}</option>
                <option value="0-50000">Tsh 0 - 50,000</option>
                <option value="50000-100000">Tsh 50,000 - 100,000</option>
                <option value="100000-200000">Tsh 100,000 - 200,000</option>
                <option value="200000+">Tsh 200,000+</option>
            </flux:select>
        </div>
    </div>

    {{-- Jobs Grid --}}
    <div class="space-y-4">
        @if($jobs->count() > 0)
        <div class="space-y-4">
            @foreach($jobs as $job)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4 sm:p-6 hover:shadow-lg transition-shadow">
                <div class="flex flex-col lg:flex-row items-start justify-between gap-4 lg:gap-6">
                    <div class="flex-1 w-full min-w-0">
                        <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                            <div class="w-12 h-12 rounded-xl bg-winga-100 dark:bg-winga-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-winga-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base sm:text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ $job->getLocalizedTitle() }} @if($job->isTranslationPending())<span class="text-xs inline-flex align-middle" title="Translating..."><x-fluent-icon name="arrow-sync-20" :size="16" /></span>@endif</h3>
                                <p class="text-zinc-600 dark:text-zinc-400 mb-4 line-clamp-2 text-sm sm:text-base">{{ $job->getLocalizedDescription() }}</p>
                                
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-zinc-500 dark:text-zinc-400 mb-4">
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
                                        TZS {{ number_format($job->budget_min ?? 0) }} - {{ number_format($job->budget_max ?? 0) }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $job->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                @if($job->skills)
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @foreach($job->skills->take(4) as $skill)
                                    <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-xs font-medium px-2 py-1">
                                        {{ $skill->name }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif

                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                            <span class="text-xs font-bold text-zinc-600 dark:text-zinc-300">{{ $job->employer->initials() }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $job->employer->name }}</p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 inline-flex items-center gap-1">
                                            <x-fluent-icon name="star-16" :size="14" />
                                            {{ $job->employer->averageRating() ?? 0 }}
                                        </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-row sm:flex-col gap-2 w-full sm:w-auto sm:min-w-[140px] flex-shrink-0 items-center sm:items-stretch">
                        <a href="{{ route('winga.kazi-detail', ['slug' => $job->slug, 'action' => 'apply']) }}" class="flex-1 sm:flex-none px-4 py-2 bg-winga-100 text-winga-900 text-sm font-semibold rounded-lg border border-winga-200 hover:bg-winga-200/70 transition-colors text-center shadow-sm" wire:navigate>
                            {{ __('messages.kazi_karibu.apply_job') }}
                        </a>
                        <a href="{{ route('winga.kazi-detail', $job->slug) }}" class="flex-1 sm:flex-none px-4 py-2 border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 text-sm font-medium rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center" wire:navigate>
                            {{ __('messages.kazi_karibu.view_full_job') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
        @else
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.kazi_karibu.no_jobs') }}</h3>
            <p class="text-zinc-500 dark:text-zinc-400 mb-4">{{ __('messages.kazi_karibu.no_jobs_desc') }}</p>
            <button wire:click="$wire->call('resetFilters')" class="px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors">
                {{ __('messages.kazi_karibu.reset_filters') }}
            </button>
        </div>
        @endif
    </div>

</div>
