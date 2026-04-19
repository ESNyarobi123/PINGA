<div class="max-w-7xl mx-auto px-4 py-6" wire:init="loadData">
    {{-- Compact Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.mawinga.title') }}</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.mawinga.subtitle') }}</p>
    </div>

    {{-- Compact Filters Bar --}}
    <div class="bg-white dark:bg-zinc-900 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="{{ __('messages.mawinga.search_placeholder') }}" />
            <flux:select wire:model.live="skill" placeholder="{{ __('messages.mawinga.all_skills') }}">
                <option value="">{{ __('messages.mawinga.all_skills') }}</option>
                @foreach($skillsForFilter as $s)
                    <option value="{{ $s['slug'] ?? $s->slug ?? '' }}">{{ $s['name'] ?? $s->name ?? '' }}</option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="location" placeholder="{{ __('messages.mawinga.all_locations') }}">
                <option value="">{{ __('messages.mawinga.all_locations') }}</option>
                @foreach($locationsForFilter as $location)
                    <option value="{{ $location }}">{{ $location }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    {{-- Skeleton Loading --}}
    <div class="@if(!$showSkeleton) hidden @endif" wire:loading.class.remove="hidden">
        <div class="flex items-center justify-between mb-4">
            <flux:skeleton.line class="w-32 h-6" />
            <flux:skeleton.line class="w-24 h-5" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach(range(1, 6) as $i)
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
                    <flux:skeleton.group animate="shimmer">
                        <flux:skeleton class="w-16 h-16 rounded-full mb-3" />
                        <flux:skeleton.line class="w-3/4 h-5 mb-2" />
                        <flux:skeleton.line class="w-full h-4 mb-2" />
                        <flux:skeleton.line class="w-1/2 h-4" />
                    </flux:skeleton.group>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Real Content --}}
    <div class="@if($showSkeleton) hidden @endif" wire:loading.class="hidden">
        {{-- Results Count & Sort --}}
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                <span class="font-bold text-zinc-900 dark:text-white">{{ $total }}</span> {{ __('messages.mawinga.workers_count') }}
            </p>
            <flux:select wire:model.live="filter" size="sm" class="w-40">
                <option value="mpya">{{ __('messages.mawinga.sort_newest') }}</option>
                <option value="rating">{{ __('messages.mawinga.sort_rating') }}</option>
                <option value="karibu">{{ __('messages.mawinga.sort_nearest') }}</option>
            </flux:select>
        </div>

        {{-- Worker Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($wafanyakazi as $w)
            <article class="group bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden shadow-sm hover:shadow-lg hover:border-emerald-300 dark:hover:border-emerald-700 hover:-translate-y-1 transition-all duration-200">
                {{-- Subscription Badge --}}
                @if(!empty($w['subscription']))
                <div class="absolute top-3 left-3 z-10">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $w['subscription']['badge_class'] }}">
                        {{ $w['subscription']['name'] }}
                    </span>
                </div>
                @endif

                {{-- Avatar & Header --}}
                <div class="relative h-32 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20">
                    <div class="absolute -bottom-10 left-4">
                        <img src="{{ $w['avatar_url'] }}" class="w-20 h-20 rounded-full border-4 border-white dark:border-zinc-900 object-cover shadow-lg" alt="{{ $w['name'] }}">
                    </div>
                </div>

                <div class="pt-12 px-4 pb-4">
                    {{-- Name & Rating --}}
                    <div class="mb-3">
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-1 group-hover:text-emerald-600 transition-colors">
                            {{ $w['name'] }}
                        </h3>
                        <div class="flex items-center gap-2 text-xs">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="font-semibold text-zinc-900 dark:text-white">{{ $w['rating'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Location --}}
                    @if($w['location'])
                    <div class="flex items-center gap-1.5 text-xs text-zinc-600 dark:text-zinc-400 mb-3">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ $w['location'] }}
                    </div>
                    @endif

                    {{-- Skills --}}
                    @if(!empty($w['skills']))
                    <div class="flex flex-wrap gap-1 mb-4">
                        @foreach(array_slice($w['skills'], 0, 3) as $skill)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 text-xs font-medium">
                            {{ $skill }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Price --}}
                    @if(!empty($w['bei_wastani']) && $w['bei_wastani'] > 0)
                    <div class="flex items-center gap-2 mb-4 text-sm">
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">TZS {{ number_format($w['bei_wastani']) }}</span>
                        <span class="text-zinc-500 dark:text-zinc-400">/ {{ $w['bei_aina'] ?? 'siku' }}</span>
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <a href="{{ route('mteja.winga-profile', $w['id']) }}" class="flex-1 px-3 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-semibold rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 text-center" wire:navigate>
                            {{ __('messages.mawinga.view_profile') }}
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full py-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                    <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.mawinga.no_workers') }}</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.mawinga.no_workers_desc') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
