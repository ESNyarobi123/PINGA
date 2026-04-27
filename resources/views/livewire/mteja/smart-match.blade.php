<div>
    {{-- Compact Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.smart_match.title') }}</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.smart_match.subtitle') }}</p>
            </div>
        </div>
    </div>

    {{-- Check if jobs exist --}}
    @if(count($jobs) > 0)
        {{-- Compact Job Selector --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-4 mb-6">
            <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.smart_match.select_job') }}</label>
            <select wire:model.live="jobId" class="w-full px-4 py-2.5 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white text-sm">
                @foreach($jobs as $job)
                <option value="{{ $job['id'] }}">{{ $job['title'] }}</option>
                @endforeach
            </select>
        </div>

        {{-- Matches List - Compact Modern Cards --}}
        @if($ready)
        @if(count($matches) > 0)
        <div class="space-y-3">
            @foreach($matches as $match)
            <div class="group bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        {{-- Worker Avatar --}}
                        <div class="flex-shrink-0">
                            <img src="{{ $match['avatar_url'] }}" alt="{{ $match['name'] }}" class="w-14 h-14 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700 group-hover:border-emerald-400 transition-colors">
                        </div>

                        {{-- Worker Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-bold text-zinc-900 dark:text-white truncate group-hover:text-emerald-600 transition-colors">{{ $match['name'] }}</h3>
                                    <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                                        @if($match['rating'] > 0)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            {{ $match['rating'] }}
                                        </span>
                                        @endif
                                        <span class="flex items-center gap-1 truncate">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            {{ $match['location'] ?: 'N/A' }}
                                        </span>
                                        @if($match['distance_label'] !== '—')
                                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ $match['distance_label'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                {{-- Match Score Badge --}}
                                <div class="flex-shrink-0 text-center">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white font-bold text-base shadow-lg group-hover:scale-110 transition-transform duration-200">
                                        {{ $match['score'] }}
                                    </div>
                                    <p class="text-xs text-zinc-500 mt-0.5">Score</p>
                                </div>
                            </div>

                            {{-- Match Reasons --}}
                            @if(count($match['reasons']) > 0)
                            <div class="mb-2">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($match['reasons'] as $reason)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        <x-fluent-icon name="checkmark-circle-16" :size="14" class="shrink-0" />
                                        {{ $reason }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Matched Skills --}}
                            @if(count($match['matched_skills']) > 0)
                            <div class="mb-2">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($match['matched_skills'], 0, 4) as $skill)
                                    <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-xs px-2 py-0.5">
                                        {{ $skill }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Compact Stats Row --}}
                            <div class="flex flex-wrap gap-3 text-xs mb-3">
                                @if($match['bei_wastani'] > 0)
                                <span class="flex items-center gap-1 font-semibold text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ number_format($match['bei_wastani']) }}/{{ $match['bei_aina'] }}
                                </span>
                                @endif
                                @if($match['uzoefu_miaka'] > 0)
                                <span class="flex items-center gap-1 text-zinc-500 dark:text-zinc-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $match['uzoefu_miaka'] }} {{ __('messages.smart_match.years') }}
                                </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-wrap gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                                <button wire:click="viewProfile({{ $match['id'] }})" class="px-3 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 hover:scale-105 transition-all duration-200">
                                    {{ __('messages.smart_match.profile') }}
                                </button>
                                <a href="{{ route('wafanyakazi.show', $match['id']) }}" target="_blank" class="group/btn px-3 py-1.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-xs font-medium rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 group-hover/btn:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        {{ __('messages.smart_match.send_request') }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.smart_match.no_matches') }}</h3>
            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.smart_match.no_matches_desc') }}</p>
        </div>
        @endif
        @else
        {{-- Loading State --}}
        <div class="flex items-center justify-center py-20">
            <div class="text-center">
                <div class="w-16 h-16 border-4 border-winga-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.smart_match.loading') }}</p>
            </div>
        </div>
        @endif
    @else
    {{-- No Jobs State --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
            <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.smart_match.no_jobs') }}</h3>
        <p class="text-zinc-500 dark:text-zinc-400 mb-4">{{ __('messages.smart_match.no_jobs_desc') }}</p>
        <a href="{{ route('mteja.post-kazi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('messages.smart_match.post_new') }}
        </a>
    </div>
    @endif

    {{-- Worker Profile Modal (Reused from Maombi) --}}
    @if($viewingWorkerId && $selectedWorker)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="closeProfile">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            {{-- Modal Header --}}
            <div class="sticky top-0 bg-gradient-to-r from-emerald-600 to-teal-500 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <img src="{{ $selectedWorker['avatar_url'] }}" alt="{{ $selectedWorker['name'] }}" class="w-16 h-16 rounded-full border-4 border-white/30">
                        <div>
                            <h2 class="text-2xl font-bold text-black">{{ $selectedWorker['name'] }}</h2>
                            <p class="text-zinc-800">{{ $selectedWorker['location'] }}</p>
                        </div>
                    </div>
                    <button wire:click="closeProfile" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Content --}}
            <div class="p-6 space-y-6">
                {{-- Rating & Stats --}}
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $selectedWorker['rating'] }}</span>
                        <span class="text-zinc-500">({{ $selectedWorker['review_count'] }} reviews)</span>
                    </div>
                    <div class="text-zinc-600 dark:text-zinc-400">
                        <span class="font-semibold">{{ __('messages.smart_match.price') }}</span> TZS {{ $selectedWorker['bei'] }}
                    </div>
                    <div class="text-zinc-600 dark:text-zinc-400">
                        <span class="font-semibold">{{ __('messages.smart_match.experience') }}</span> {{ $selectedWorker['uzoefu'] }}
                    </div>
                </div>

                {{-- Bio --}}
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.smart_match.about') }}</h3>
                    <p class="text-zinc-600 dark:text-zinc-400">{{ $selectedWorker['bio'] }}</p>
                </div>

                {{-- Skills --}}
                @if(count($selectedWorker['skills']) > 0)
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.smart_match.skills') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($selectedWorker['skills'] as $skill)
                        <span class="px-3 py-1 bg-winga-100 text-winga-700 rounded-full text-sm font-medium">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Portfolio --}}
                @if(count($selectedWorker['portfolio']) > 0)
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Portfolio</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($selectedWorker['portfolio'] as $item)
                        <div class="border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}" class="w-full h-32 object-cover">
                            <div class="p-3">
                                <p class="font-semibold text-zinc-900 dark:text-white text-sm">{{ $item['title'] }}</p>
                                <p class="text-xs text-zinc-500 mt-1">{{ $item['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Reviews --}}
                @if(count($selectedWorker['reviews']) > 0)
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">{{ __('messages.smart_match.reviews') }}</h3>
                    <div class="space-y-3">
                        @foreach($selectedWorker['reviews'] as $review)
                        <div class="border border-zinc-200 dark:border-zinc-800 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <img src="{{ $review['reviewer_avatar'] }}" alt="{{ $review['reviewer_name'] }}" class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $review['reviewer_name'] }}</p>
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review['rating'] ? 'text-amber-500' : 'text-zinc-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $review['comment'] }}</p>
                                    <p class="text-xs text-zinc-500 mt-1">{{ $review['date'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
