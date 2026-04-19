<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
    {{-- Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-zinc-900 dark:text-white tracking-tight mb-3">
            Kazi kwa <span class="text-transparent bg-clip-text bg-gradient-to-r from-winga-600 to-winga-400">Kategoria</span>
        </h1>
        <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            Tafuta kazi zilizopangwa kwa makundi mbalimbali
        </p>
    </div>

    {{-- Category Quick Navigation --}}
    <div class="flex flex-wrap justify-center gap-2 mb-10">
        @foreach($categories as $category)
            @if(isset($jobsByCategory[$category->id]))
            <a href="#category-{{ $category->id }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:border-winga-400 hover:text-winga-600 dark:hover:text-winga-400 transition-all duration-200 hover:-translate-y-0.5 shadow-sm">
                @if($category->icon)
                    <span>{{ $category->icon }}</span>
                @endif
                {{ $category->name }}
                <span class="text-xs text-zinc-400 dark:text-zinc-500">({{ $category->jobs_count }})</span>
            </a>
            @endif
        @endforeach
    </div>

    {{-- Categories with Jobs --}}
    <div class="space-y-12">
        @foreach($categories as $category)
            @if(isset($jobsByCategory[$category->id]))
            <section id="category-{{ $category->id }}">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        @if($category->icon)
                            <span class="text-2xl">{{ $category->icon }}</span>
                        @endif
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white">{{ $category->name }}</h2>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $category->jobs_count }} kazi zinapatikana</p>
                        </div>
                    </div>
                    <a href="{{ route('tafuta-kazi') }}?category={{ $category->slug }}" wire:navigate
                       class="inline-flex items-center gap-1.5 text-sm font-semibold text-winga-600 dark:text-winga-400 hover:text-winga-700 dark:hover:text-winga-300 transition-colors">
                        Angalia zote
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($jobsByCategory[$category->id] as $job)
                    <a href="{{ route('kazi.show', $job->slug) }}" wire:navigate
                       class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 hover:border-winga-300 dark:hover:border-winga-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 flex flex-col">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <h3 class="font-bold text-zinc-900 dark:text-white text-base group-hover:text-winga-600 dark:group-hover:text-winga-400 transition-colors line-clamp-2 leading-tight">
                                {{ $job->getLocalizedTitle() }}
                            </h3>
                            @if($job->urgency !== 'normal')
                            <span class="flex-shrink-0 text-[10px] font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded-full">
                                {{ $job->urgency === 'very_urgent' ? 'Haraka Sana' : 'Haraka' }}
                            </span>
                            @endif
                        </div>

                        <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2 mb-4 flex-1">
                            {{ Str::limit($job->getLocalizedDescription(), 120) }}
                        </p>

                        <div class="flex items-center justify-between pt-3 border-t border-zinc-100 dark:border-zinc-800">
                            <span class="text-sm font-bold text-winga-600 dark:text-winga-400">
                                TZS {{ number_format($job->budget_min ?? 0) }}
                                @if($job->budget_max && $job->budget_max > $job->budget_min)
                                    - {{ number_format($job->budget_max) }}
                                @endif
                            </span>
                            <div class="flex items-center gap-2 text-xs text-zinc-400">
                                <span>{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif
        @endforeach

        @if(empty($jobsByCategory))
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Hakuna kazi kwa sasa</h3>
            <p class="text-zinc-500 dark:text-zinc-400">Hakuna kazi zilizo wazi kwa sasa. Rudi tena baadaye!</p>
        </div>
        @endif
    </div>
</div>
