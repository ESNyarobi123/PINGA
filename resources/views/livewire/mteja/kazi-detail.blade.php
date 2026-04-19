<div>
    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('mteja.kazi-zangu') }}" class="inline-flex items-center gap-2 text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" wire:navigate>
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ __('messages.mteja_job_detail.back') }}
        </a>
    </div>

    {{-- Job Header --}}
    <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-2xl p-6 mb-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="relative z-10">
            <div class="flex items-start justify-between gap-4 mb-3">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold mb-2 text-black">{{ $job->getLocalizedTitle() }}</h1>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $job->location }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $job->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                    {{ $job->status === 'open' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $job->status === 'in_progress' ? 'bg-amber-100 text-amber-700' : '' }}
                    {{ $job->status === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $job->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                    {{ $job->status === 'draft' ? 'bg-zinc-100 text-zinc-700' : '' }}">
                    {{ ucfirst($job->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Description --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.mteja_job_detail.description') }}</h2>
                <p class="text-zinc-600 dark:text-zinc-400 whitespace-pre-line">{{ $job->getLocalizedDescription() }}</p>
            </div>

            {{-- Requirements --}}
            @if($job->requirements)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.mteja_job_detail.requirements') }}</h2>
                <p class="text-zinc-600 dark:text-zinc-400 whitespace-pre-line">{{ $job->getLocalizedRequirements() }}</p>
            </div>
            @endif

            {{-- Skills Required --}}
            @if($job->skills && $job->skills->count() > 0)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.mteja_job_detail.skills') }}</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($job->skills as $skill)
                    <span class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-lg text-sm font-medium">
                        {{ $skill->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Applications --}}
            @if($job->applications && $job->applications->count() > 0)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">
                    {{ __('messages.mteja_job_detail.applications_title') }} ({{ $job->applications_count }})
                </h2>
                <div class="space-y-3">
                    @foreach($job->applications->take(5) as $application)
                    <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <img src="{{ $application->worker->avatar ? asset('storage/' . $application->worker->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($application->worker->name) }}" 
                                 alt="{{ $application->worker->name }}" 
                                 class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-semibold text-zinc-900 dark:text-white">{{ $application->worker->name }}</p>
                                <p class="text-xs text-zinc-500">{{ $application->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $application->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $application->status === 'accepted' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('mteja.maombi', ['job_id' => $job->id]) }}" class="mt-4 inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium" wire:navigate>
                    {{ __('messages.mteja_job_detail.view_all_applications') }}
                </a>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Budget Info --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.mteja_job_detail.budget') }}</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.mteja_job_detail.budget_type') }}</p>
                        <p class="text-lg font-bold text-zinc-900 dark:text-white capitalize">{{ $job->budget_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.mteja_job_detail.budget_price') }}</p>
                        <p class="text-2xl font-bold text-emerald-600">
                            TZS {{ number_format($job->budget_min) }}
                            @if($job->budget_max && $job->budget_max != $job->budget_min)
                                - {{ number_format($job->budget_max) }}
                            @endif
                        </p>
                    </div>
                    @if($job->duration)
                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.mteja_job_detail.budget_duration') }}</p>
                        <p class="text-lg font-bold text-zinc-900 dark:text-white">{{ $job->duration }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Category --}}
            @if($job->category)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.mteja_job_detail.category') }}</h3>
                <p class="text-zinc-600 dark:text-zinc-400">{{ $job->category->name }}</p>
            </div>
            @endif

            {{-- Stats --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.mteja_job_detail.stats') }}</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.mteja_job_detail.stats_applications') }}</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $job->applications_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.mteja_job_detail.stats_priority') }}</span>
                        <span class="font-bold text-zinc-900 dark:text-white capitalize">{{ $job->urgency }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.mteja_job_detail.stats_remote') }}</span>
                        <span class="font-bold text-zinc-900 dark:text-white">{{ $job->remote_allowed ? __('messages.mteja_job_detail.stats_yes') : __('messages.mteja_job_detail.stats_no') }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.mteja_job_detail.actions') }}</h3>
                <div class="space-y-2">
                    @if($job->status === 'open')
                    <a href="{{ route('mteja.maombi', ['job_id' => $job->id]) }}" class="w-full px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2" wire:navigate>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ __('messages.mteja_job_detail.view_applications') }}
                    </a>
                    @endif

                    <a href="{{ route('mteja.kazi-zangu') }}" class="w-full px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors flex items-center justify-center gap-2" wire:navigate>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ __('messages.mteja_job_detail.go_back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
