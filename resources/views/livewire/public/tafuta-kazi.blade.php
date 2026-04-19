<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full" wire:init="loadData">
    {{-- Header Content --}}
    <div class="mb-8 text-center sm:text-left">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 dark:text-white tracking-tight mb-3">
            {{ __('messages.search_jobs.title_1') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-winga-600 to-winga-400">{{ __('messages.search_jobs.title_2') }}</span>
        </h1>
        <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl">
            {{ __('messages.search_jobs.subtitle') }}
        </p>
    </div>

    {{-- Filters Bar --}}
    <div class="bg-white dark:bg-zinc-900 p-3 sm:p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row shadow-sm gap-3 sm:gap-4 mb-8">
        <div class="w-full sm:flex-1">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" :placeholder="__('messages.search_jobs.search_placeholder')" class="w-full" />
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

            @if(in_array($filterType, ['all', 'kategoria']))
            <div class="w-full sm:w-[180px]">
                <flux:select wire:model.live="category" :placeholder="__('messages.search_jobs.all_categories')" class="w-full">
                    <option value="">{{ __('messages.search_jobs.all_categories') }}</option>
                    @foreach($categoriesForFilter as $cat)
                        <option value="{{ $cat['slug'] ?? $cat->slug ?? $cat['id'] ?? $cat->id ?? '' }}">{{ $cat['name'] ?? $cat->name ?? '' }}</option>
                    @endforeach
                </flux:select>
            </div>
            @endif
            
            @if(in_array($filterType, ['all', 'mahali']))
            <div class="w-full sm:w-[180px]">
                <flux:select wire:model.live="location" :placeholder="__('messages.search_jobs.any_location')" class="w-full">
                    <option value="">{{ __('messages.search_jobs.any_location') }}</option>
                    @foreach($locationsForFilter as $location)
                        <option value="{{ $location }}">{{ $location }}</option>
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
                @include('partials.skeleton-job-card')
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
            <p class="text-zinc-600 dark:text-zinc-400 font-medium text-[15px]"><span class="text-zinc-900 dark:text-white font-semibold">{{ $total }}</span> {{ __('messages.search_jobs.results_found') }}</p>
            <div class="flex items-center gap-2 text-[14px]">
                <span class="text-zinc-500 dark:text-zinc-400 font-medium">{{ __('messages.search_jobs.sort_by') }}</span>
                <flux:select wire:model.live="filter" size="sm" class="min-w-[150px] font-semibold bg-white dark:bg-zinc-800 shadow-sm">
                    <option value="mpya">{{ __('messages.search_jobs.sort_newest') }}</option>
                    <option value="bei_kubwa">{{ __('messages.search_jobs.sort_price') }}</option>
                    <option value="haraka">{{ __('messages.search_jobs.sort_urgent') }}</option>
                    <option value="karibu">{{ __('messages.search_jobs.sort_nearby') }}</option>
                </flux:select>
            </div>
        </div>

        {{-- Job cards grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($kazi as $job)
            <article class="group relative bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/80 dark:border-zinc-800 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 hover:border-winga-300 dark:hover:border-winga-700/50 transition-all duration-300 flex flex-col">
                {{-- Floating Action (Favorite) --}}
                <div class="absolute top-4 right-4 z-10 transition-transform duration-300 transform group-hover:scale-110">
                    <button type="button" wire:click="toggleFavorite({{ $job['id'] }})" class="inline-flex items-center justify-center size-9 rounded-full shadow-sm ring-1 ring-zinc-900/5 transition-all focus:outline-none {{ in_array($job['id'], $favoritedJobIds) ? 'bg-amber-50 dark:bg-amber-500/20 text-amber-500' : 'bg-white/90 dark:bg-zinc-800/90 text-zinc-400 hover:text-amber-500 hover:bg-white dark:hover:bg-zinc-800' }}" aria-label="Favoriti">
                        <svg class="size-5" fill="{{ in_array($job['id'], $favoritedJobIds) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    </button>
                </div>

                <div class="p-6 pb-5 flex-1 flex flex-col">
                    {{-- Badges / Meta info --}}
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-2 mb-3 mr-10">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-winga-50 dark:bg-winga-500/10 text-winga-700 dark:text-winga-400 text-[11px] font-semibold px-2.5 py-1 border border-winga-100 dark:border-winga-500/20">
                            {{ __('messages.search_jobs.posted') }} {{ $job['posted_at_human'] ?? __('messages.search_jobs.recently') }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-[12px] font-medium text-zinc-500 dark:text-zinc-400">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('messages.search_jobs.applications') }} {{ $job['applications_count'] ?? 0 }}
                        </span>
                    </div>

                    {{-- Title and Price --}}
                    <div class="mt-1 w-full">
                        <a href="{{ route('kazi.show', $job['slug'] ?? $job['id']) }}" class="block font-bold text-zinc-900 dark:text-white text-[18px] hover:text-winga-600 dark:hover:text-winga-400 transition-colors line-clamp-2 leading-tight" wire:navigate>
                            {{ $job['title'] ?? 'Kazi' }}
                        </a>
                        
                        <div class="flex flex-wrap items-center gap-3 mt-3">
                            <span class="inline-flex items-center text-[15px] font-bold text-winga-600 dark:text-winga-400 bg-winga-50 dark:bg-winga-500/10 px-2.5 py-1 rounded-lg">
                                <span class="text-winga-800/70 dark:text-winga-200/70 text-[12px] font-semibold mr-1.5 uppercase tracking-wide">{{ $job['price_type'] ?? 'Bei' }}:</span>
                                {{ $job['price'] ?? 'Maelewano' }}
                            </span>
                            @if(!empty($job['duration']))
                            <span class="flex items-center gap-1.5 text-[13px] text-zinc-500 dark:text-zinc-400 bg-zinc-50 dark:bg-zinc-800 px-2.5 py-1 rounded-lg">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $job['duration'] }}
                            </span>
                            @endif
                            @if(!empty($job['urgency']) && $job['urgency'] !== 'normal')
                            <span class="text-[12px] font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 px-2.5 py-1 rounded-lg">
                                {{ $job['urgency'] === 'very_urgent' ? __('messages.search_jobs.very_urgent') : __('messages.search_jobs.urgent') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mt-4 grow flex flex-col">
                        <p class="text-[13.5px] text-zinc-600 dark:text-zinc-400 line-clamp-2 leading-relaxed tracking-wide min-h-[40px]">
                            {{ Str::limit($job['description'] ?? '', 150) }}
                        </p>
                        
                        @if(!empty($job['tags']))
                        <div class="flex flex-wrap items-center gap-1.5 mt-4">
                            @foreach(array_slice($job['tags'], 0, 4) as $tag)
                            <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-[11px] font-medium px-2 py-1 border border-zinc-200/60 dark:border-zinc-700/60 transition-colors group-hover:bg-white dark:group-hover:bg-zinc-700 group-hover:border-zinc-300 dark:group-hover:border-zinc-600">
                                {{ $tag }}
                            </span>
                            @endforeach
                            @if(count($job['tags']) > 4)
                            <span class="inline-flex items-center rounded-md text-zinc-500 dark:text-zinc-400 text-[11px] font-medium px-1 py-1">
                                +{{ count($job['tags']) - 4 }} {{ __('messages.search_jobs.more') }}
                            </span>
                            @endif
                        </div>
                        @else
                        {{-- Empty height placeholder to maintain card alignment --}}
                        <div class="h-6 mt-4"></div>
                        @endif
                    </div>
                    
                    {{-- Client Info --}}
                    <div class="mt-5 pt-4 border-t border-zinc-100 dark:border-zinc-800/60 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <img
                                src="{{ $job['client_avatar_url'] ?? ('https://ui-avatars.com/api/?name='.urlencode($job['client_name'] ?? 'U').'&background=0d9488&color=fff&size=64') }}"
                                alt=""
                                class="size-9 rounded-full object-cover ring-2 ring-zinc-50 dark:ring-zinc-800/80 shrink-0"
                            />
                            <div class="min-w-0">
                                <span class="font-semibold text-[13px] text-zinc-800 dark:text-zinc-200 block truncate">{{ $job['client_name'] ?? '—' }}</span>
                                <span class="flex items-center gap-1 text-[12px] text-zinc-500 dark:text-zinc-400 truncate mt-0.5">
                                    <svg class="size-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    {{ $job['location'] ?? '—' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-auto px-6 pb-6 pt-0">
                    <a href="{{ route('kazi.show', $job['slug'] ?? $job['id']) }}" wire:navigate class="block w-full">
                        <button class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:hover:bg-zinc-100 text-white dark:text-zinc-900 border border-transparent px-4 py-2.5 text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/50 focus:ring-offset-2 transition-all">
                            {{ __('messages.search_jobs.apply') }}
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-full bg-white dark:bg-zinc-900 rounded-2xl border-2 border-dashed border-zinc-200 dark:border-zinc-800 p-12 text-center flex flex-col items-center justify-center min-h-[300px]">
                <div class="size-16 rounded-full bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-center mb-5 ring-4 ring-zinc-50 dark:ring-zinc-800/30">
                    <svg class="size-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.search_jobs.no_results_title') }}</h3>
                <p class="text-zinc-500 dark:text-zinc-400 mt-2 max-w-sm text-sm">{{ __('messages.search_jobs.no_results_desc') }}</p>
                @if($search || $category)
                <button wire:click="$set('search', ''); $set('category', '')" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-winga-600 hover:text-winga-500 bg-winga-50 dark:bg-winga-500/10 px-4 py-2 rounded-xl transition-colors">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ __('messages.search_jobs.clear_filters') }}
                </button>
                @endif
            </div>
            @endforelse
        </div>

        @if($total > count($kazi))
        <div class="mt-12 flex justify-center pb-8">
            <button wire:click="loadMore" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-sm font-semibold text-zinc-900 dark:text-white shadow-sm hover:shadow hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-all focus:outline-none focus:ring-2 focus:ring-zinc-500/40 focus:ring-offset-2 w-full sm:w-auto min-w-[200px]">
                <svg wire:loading wire:target="loadMore" class="animate-spin -ml-1 h-4 w-4 text-zinc-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span wire:loading.remove wire:target="loadMore">{{ __('messages.search_jobs.load_more') }}</span>
                <span wire:loading wire:target="loadMore">{{ __('messages.search_jobs.loading') }}</span>
            </button>
        </div>
        @endif
    </div>
</div>
