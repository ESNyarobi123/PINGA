<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full" wire:init="loadData">
    {{-- Header Content --}}
    <div class="mb-8 text-center sm:text-left">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 dark:text-white tracking-tight mb-3">
            {{ __('messages.search_workers.title_1') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-winga-600 to-winga-400">{{ __('messages.search_workers.title_2') }}</span>
        </h1>
        <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl">
            {{ __('messages.search_workers.subtitle') }}
        </p>
    </div>

    {{-- Filters Bar --}}
    <div class="bg-white dark:bg-zinc-900 p-3 sm:p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row shadow-sm gap-3 sm:gap-4 mb-8">
        <div class="w-full sm:flex-1">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" :placeholder="__('messages.search_workers.search_placeholder')" class="w-full" />
        </div>

        <div class="flex w-full sm:w-auto flex-col sm:flex-row items-center gap-3 shrink-0">
            @if(in_array($filterType, ['all', 'ustadi']))
            <div class="w-full sm:w-[180px]">
                <flux:select wire:model.live="skill" :placeholder="__('messages.search_workers.all_skills')" class="w-full">
                    <option value="">{{ __('messages.search_workers.all_skills') }}</option>
                    @foreach($skillsForFilter as $s)
                        <option value="{{ $s['slug'] ?? $s->slug ?? $s['name'] ?? $s->name ?? '' }}">{{ $s['name'] ?? $s->name ?? '' }}</option>
                    @endforeach
                </flux:select>
            </div>
            @endif
            
            @if(in_array($filterType, ['all', 'mahali']))
            <div class="w-full sm:w-[180px]">
                <flux:select wire:model.live="location" :placeholder="__('messages.search_workers.any_location')" class="w-full">
                    <option value="">{{ __('messages.search_workers.any_location') }}</option>
                    @foreach($locationsForFilter as $location)
                        <option value="{{ $location }}">{{ $location }}</option>
                    @endforeach
                </flux:select>
            </div>
            @endif

            @if(in_array($filterType, ['all', 'kategoria']))
            <div class="w-full sm:w-[180px]">
                <flux:select wire:model.live="category" :placeholder="__('messages.search_jobs.all_categories')" class="w-full">
                    <option value="">{{ __('messages.search_jobs.all_categories') }}</option>
                    @foreach($categoriesForFilter as $cat)
                        <option value="{{ $cat['slug'] ?? $cat->slug ?? '' }}">{{ $cat['name'] ?? $cat->name ?? '' }}</option>
                    @endforeach
                </flux:select>
            </div>
            @endif
        </div>
    </div>

    {{-- Skeleton: show first paint + while loading (perceived speed) --}}
    <div
        class="@if(!$showSkeleton) hidden @endif"
        wire:loading.class.remove="hidden"
        wire:loading.class="flex flex-col"
    >
        <div class="flex items-center justify-between mb-6">
            <flux:skeleton.group animate="shimmer">
                <flux:skeleton.line class="w-32 h-6" />
            </flux:skeleton.group>
            <flux:skeleton.group animate="shimmer" class="flex items-center gap-2">
                <flux:skeleton.line class="w-24 h-5" />
                <flux:skeleton class="h-10 min-w-[150px] rounded-lg" />
            </flux:skeleton.group>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(range(1, 6) as $i)
                @include('partials.skeleton-worker-card')
            @endforeach
        </div>
    </div>

    {{-- Real content: hide until ready and not loading --}}
    <div
        class="@if($showSkeleton) hidden @endif"
        wire:loading.class="hidden"
    >
        {{-- Top Bar: Results count & Sort --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <p class="text-zinc-600 dark:text-zinc-400 font-medium text-[15px]"><span class="text-zinc-900 dark:text-white font-semibold">{{ $total }}</span> {{ __('messages.search_workers.results_found') }}</p>
            <div class="flex items-center gap-2 text-[14px]">
                <span class="text-zinc-500 dark:text-zinc-400 font-medium">{{ __('messages.search_workers.sort_by') }}</span>
                <flux:select wire:model.live="filter" size="sm" class="min-w-[150px] font-semibold bg-white dark:bg-zinc-800 shadow-sm">
                    <option value="mpya">{{ __('messages.search_workers.sort_newest') }}</option>
                    <option value="rating">{{ __('messages.search_workers.sort_rating') }}</option>
                    <option value="karibu">{{ __('messages.search_workers.sort_nearby') }}</option>
                </flux:select>
            </div>
        </div>

        {{-- Worker cards grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($wafanyakazi as $w)
            <article class="group relative bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 hover:border-winga-300 dark:hover:border-winga-700/50 transition-all duration-300 flex flex-col {{ $w['subscription']['border_class'] ?? '' }}">
                {{-- Subscription Badge --}}
                @if(!empty($w['subscription']))
                <div class="absolute top-4 left-4 z-10">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $w['subscription']['badge_class'] }}">
                        @if($w['subscription']['slug'] === 'bora')
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endif
                        {{ $w['subscription']['name'] }}
                    </span>
                </div>
                @endif

                {{-- Floating Action (Favorite) --}}
                <div class="absolute top-4 right-4 z-10 transition-transform duration-300 transform group-hover:scale-110">
                    <button type="button" wire:click="toggleFavorite({{ $w['id'] }})" class="inline-flex items-center justify-center size-9 rounded-full shadow-sm ring-1 ring-zinc-900/5 transition-all focus:outline-none {{ in_array($w['id'], $favoritedWorkerIds) ? 'bg-red-50 dark:bg-red-500/20 text-red-500' : 'bg-white/90 dark:bg-zinc-800/90 text-zinc-400 hover:text-red-500 hover:bg-white dark:hover:bg-zinc-800' }}" aria-label="Favoriti">
                        <svg class="size-5" fill="{{ in_array($w['id'], $favoritedWorkerIds) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </div>

                <div class="p-6 pb-5 flex-1 flex flex-col">
                    {{-- Header Profile Info --}}
                    <div class="flex flex-col items-center text-center">
                        <a href="{{ route('wafanyakazi.show', $w['id']) }}" wire:navigate class="relative inline-block mt-2">
                            <img
                                src="{{ $w['avatar_url'] ?? ('https://ui-avatars.com/api/?name='.urlencode($w['name'] ?? '').'&background=0d9488&color=fff&size=128') }}"
                                alt=""
                                class="size-20 rounded-full object-cover ring-4 ring-zinc-50 dark:ring-zinc-800/80 group-hover:ring-winga-100 dark:group-hover:ring-winga-900/30 transition-all duration-300"
                            />
                            @if(!empty($w['rating_percent']))
                            <div class="absolute -bottom-2 -right-2 bg-white dark:bg-zinc-900 rounded-full p-0.5 shadow-sm">
                                <span class="flex items-center gap-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[11px] font-bold px-1.5 py-0.5 rounded-full border border-white dark:border-zinc-900">
                                    <svg class="size-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ $w['rating_percent'] }}%
                                </span>
                            </div>
                            @endif
                        </a>

                        <div class="mt-4 w-full">
                            <a href="{{ route('wafanyakazi.show', $w['id']) }}" class="block font-bold text-zinc-900 dark:text-white text-[17px] hover:text-winga-600 dark:hover:text-winga-400 transition-colors line-clamp-1" wire:navigate>
                                {{ $w['name'] ?? __('messages.search_workers.worker') }}
                            </a>
                            <h3 class="font-medium text-winga-600 dark:text-winga-400 text-[14px] mt-0.5 line-clamp-1">
                                {{ $w['offer_title'] ?? __('messages.search_workers.various_services') }}
                            </h3>
                            
                            <div class="flex items-center justify-center gap-3 mt-2 text-[13px] text-zinc-500 dark:text-zinc-400">
                                <span class="flex items-center gap-1">
                                    <svg class="size-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    <span class="truncate max-w-[120px]">{{ $w['location'] ?? '—' }}</span>
                                </span>
                                @if(!empty($w['bei_wastani']))
                                <div class="w-1 h-1 rounded-full bg-zinc-300 dark:bg-zinc-700"></div>
                                <span class="font-semibold text-zinc-700 dark:text-zinc-300">
                                    Tsh {{ number_format($w['bei_wastani']) }} <span class="text-zinc-400 font-normal">/ {{ $w['bei_aina'] ?? 'siku' }}</span>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800/60 grow flex flex-col justify-end">
                        <p class="text-[13.5px] text-zinc-600 dark:text-zinc-400 line-clamp-2 leading-relaxed tracking-wide min-h-[40px]">
                            {{ $w['offer_description'] ?? __('messages.search_workers.no_description') }}
                        </p>
                        
                        @if(!empty($w['skills']))
                        <div class="flex flex-wrap items-center gap-1.5 mt-4">
                            @foreach(array_slice($w['skills'], 0, 3) as $skillName)
                            <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-[11px] font-medium px-2 py-1 border border-zinc-200/60 dark:border-zinc-700/60 transition-colors group-hover:bg-white dark:group-hover:bg-zinc-700 group-hover:border-zinc-300 dark:group-hover:border-zinc-600">
                                {{ $skillName }}
                            </span>
                            @endforeach
                            @if(count($w['skills']) > 3)
                            <span class="inline-flex items-center rounded-md text-zinc-500 dark:text-zinc-400 text-[11px] font-medium px-1 py-1">
                                +{{ count($w['skills']) - 3 }} {{ __('messages.search_workers.more') }}
                            </span>
                            @endif
                        </div>
                        @else
                        {{-- Empty height placeholder to maintain card alignment --}}
                        <div class="h-6 mt-4"></div>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-auto px-6 pb-6 pt-0 flex flex-col sm:flex-row gap-2.5">
                    <a href="{{ route('wafanyakazi.show', $w['id']) }}" wire:navigate class="w-full">
                        <button class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:hover:bg-zinc-100 text-white dark:text-zinc-900 border border-transparent px-4 py-2.5 text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/50 focus:ring-offset-2 transition-all">
                            {{ __('messages.search_workers.view_profile') }}
                        </button>
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-full bg-white dark:bg-zinc-900 rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-800 p-12 text-center flex flex-col items-center justify-center min-h-[300px]">
                <div class="size-16 rounded-full bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-center mb-5 ring-4 ring-zinc-50 dark:ring-zinc-800/30">
                    <svg class="size-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.search_workers.no_results_title') }}</h3>
                <p class="text-zinc-500 dark:text-zinc-400 mt-2 max-w-sm text-sm">{{ __('messages.search_workers.no_results_desc') }}</p>
                @if($search || $skill || $location)
                <button wire:click="$set('search', ''); $set('skill', ''); $set('location', '')" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-winga-600 hover:text-winga-500 bg-winga-50 dark:bg-winga-500/10 px-4 py-2 rounded-xl transition-colors">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ __('messages.search_workers.clear_filters') }}
                </button>
                @endif
            </div>
            @endforelse
        </div>

        @if($total > count($wafanyakazi))
        <div class="mt-12 flex justify-center pb-8">
            <button wire:click="loadMore" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-sm font-semibold text-zinc-900 dark:text-white shadow-sm hover:shadow hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-all focus:outline-none focus:ring-2 focus:ring-zinc-500/40 focus:ring-offset-2 w-full sm:w-auto min-w-[200px]">
                <svg wire:loading wire:target="loadMore" class="animate-spin -ml-1 h-4 w-4 text-zinc-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span wire:loading.remove wire:target="loadMore">{{ __('messages.search_workers.load_more') }}</span>
                <span wire:loading wire:target="loadMore">{{ __('messages.search_workers.loading') }}</span>
            </button>
        </div>
        @endif
    </div>
</div>
