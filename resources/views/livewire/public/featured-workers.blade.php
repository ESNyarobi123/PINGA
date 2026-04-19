@if($hasFeatured)
<div class="mb-8">
    {{-- Section Header --}}
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-zinc-900 dark:text-white">{{ __('messages.featured_workers.title') }}</h3>
            <p class="text-xs text-zinc-500">{{ __('messages.featured_workers.subtitle') }}</p>
        </div>
    </div>

    {{-- Featured Workers Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($featured as $worker)
        <a href="{{ route('wafanyakazi.show', $worker->id) }}" wire:navigate
           class="group relative bg-white dark:bg-zinc-900 rounded-2xl border-2 border-amber-200 dark:border-amber-800/50 overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            {{-- Premium Badge --}}
            <div class="absolute top-3 left-3 z-10">
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-[10px] font-black uppercase tracking-wider rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    {{ __('messages.featured_workers.premium') }}
                </span>
            </div>

            <div class="p-5">
                {{-- Avatar & Name --}}
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $worker->avatar ? asset('storage/'.$worker->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($worker->name).'&background=0d9488&color=fff&size=128' }}"
                         alt="{{ $worker->name }}"
                         class="w-14 h-14 rounded-xl object-cover ring-2 ring-amber-100 dark:ring-amber-900/30">
                    <div>
                        <h4 class="font-bold text-zinc-900 dark:text-white text-sm group-hover:text-winga-600 transition">{{ $worker->name }}</h4>
                        @if($worker->reviews_received_avg_rating)
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-xs font-bold text-zinc-600 dark:text-zinc-400">{{ round($worker->reviews_received_avg_rating, 1) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Skills --}}
                @if($worker->skills->isNotEmpty())
                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach($worker->skills->take(3) as $skill)
                    <span class="px-2 py-0.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 text-[10px] font-medium rounded">{{ $skill->name }}</span>
                    @endforeach
                    @if($worker->skills->count() > 3)
                    <span class="px-2 py-0.5 text-zinc-400 text-[10px]">+{{ $worker->skills->count() - 3 }}</span>
                    @endif
                </div>
                @endif

                {{-- Location & Rate --}}
                <div class="flex items-center justify-between text-xs">
                    <span class="text-zinc-500 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $worker->mkoa ?? '—' }}
                    </span>
                    @if($worker->bei_wastani)
                    <span class="font-bold text-winga-600">TZS {{ number_format($worker->bei_wastani) }}</span>
                    @endif
                </div>
            </div>

            {{-- CTA --}}
            <div class="px-5 py-3 bg-amber-50 dark:bg-amber-950/20 border-t border-amber-100 dark:border-amber-900/30">
                <span class="text-xs font-bold text-amber-700 dark:text-amber-400 flex items-center gap-1 group-hover:gap-2 transition-all">
                    {{ __('messages.featured_workers.view_profile') }}
                </span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif
