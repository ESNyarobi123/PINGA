<div>
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.codes.title') }}</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.codes.subtitle') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full text-sm font-medium">
                    {{ $jobs->total() }} {{ __('messages.codes.jobs_in_progress') }}
                </span>
                <span class="px-3 py-1 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded-full text-sm font-medium">
                    {{ $serviceRequests->total() }} {{ __('messages.codes.huduma_in_progress') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Jobs List --}}
    <div class="space-y-4">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-zinc-500">{{ __('messages.codes.section_jobs') }}</h2>
        @foreach($jobs as $job)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border-b border-zinc-200 dark:border-zinc-800">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $job->getLocalizedTitle() }}</h3>
                        <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $job->hiredWorker?->name ?? __('messages.codes.not_assigned') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                TZS {{ number_format($job->payment?->amount ?? $job->budget_min ?? 0) }}
                            </span>
                            <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full text-xs font-medium">
                                {{ __('messages.codes.in_progress') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4">
                @if($job->completion_code)
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 border border-emerald-200 dark:border-emerald-800">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm text-emerald-700 dark:text-emerald-400 font-medium mb-1">{{ __('messages.codes.code_generated') }}</p>
                                <div class="flex items-center gap-3">
                                    <div class="px-4 py-2 bg-white dark:bg-zinc-800 rounded-lg font-mono font-bold text-xl text-emerald-600 dark:text-emerald-400 tracking-widest shadow-sm">
                                        {{ $job->completion_code }}
                                    </div>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $job->completion_code }}')" class="p-2 text-zinc-500 hover:text-emerald-600 transition-colors" title="{{ __('messages.codes.copy') }}">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                                @if($job->code_generated_at)
                                    <p class="text-xs text-zinc-500 mt-2">{{ __('messages.codes.code_issued') }} {{ $job->code_generated_at->diffForHumans() }}</p>
                                @endif
                            </div>
                            <div class="flex flex-col gap-2">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $job->hiredWorker?->phone ?? '') }}?text=Code%20ya%20kazi%20%22{{ urlencode($job->getLocalizedTitle()) }}%22%20ni%3A%20{{ $job->completion_code }}"
                                   target="_blank"
                                   class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.292-1.634-1.61-1.88-1.852-.296-.292-.74-.392-1.132-.17-.28.152-.966.56-1.226.68-.254.116-.518.17-.784.17-.266 0-.53-.054-.784-.17-.26-.12-.946-.528-1.226-.68-.392-.222-.836-.122-1.132.17-.246.242-1.583 1.56-1.88 1.852-.296.292-.344.76-.116 1.118.228.358.74.952 1.132 1.136.392.184.784.276 1.176.276.392 0 .784-.092 1.176-.276.392-.184.904-.778 1.132-1.136.228-.358.18-.826-.116-1.118zM12 2C6.48 2 2 6.48 2 12c0 1.82.49 3.52 1.34 4.98L2 22l5.02-1.34A9.93 9.93 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm0 18c-1.66 0-3.22-.52-4.52-1.42l-.32-.22-2.98.8.8-2.98-.22-.32A7.94 7.94 0 014 12c0-4.42 3.58-8 8-8s8 3.58 8 8-3.58 8-8 8z"/>
                                    </svg>
                                    {{ __('messages.codes.send_whatsapp') }}
                                </a>
                                @if($job->isOnCodeHold())
                                    <div class="px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-sm rounded-lg text-center">
                                        {{ __('messages.codes.hold_until') }} {{ $job->code_hold_until->format('H:i') }}
                                    </div>
                                    @if(!$job->hasExtendedHold())
                                        <button type="button" wire:click="openExtendForm('job', {{ $job->id }})"
                                                class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                            Ongeza Masaa 3
                                        </button>
                                    @else
                                        <div class="px-3 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 text-xs rounded-lg text-center">
                                            Muda umeongezwa tayari
                                        </div>
                                    @endif
                                @else
                                    <button type="button" wire:click="holdCode({{ $job->id }})"
                                            wire:confirm="{{ __('messages.codes.hold_confirm_3h') }}"
                                            class="px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                                        Shikilia Masaa 3
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm text-zinc-700 dark:text-zinc-300 font-medium mb-1">{{ __('messages.codes.no_code') }}</p>
                                <p class="text-xs text-zinc-500">{{ __('messages.codes.no_code_desc') }}</p>
                            </div>
                            <button type="button" wire:click="generateCode({{ $job->id }})"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 hover:scale-105 transition-all duration-200 shadow-lg shadow-emerald-500/20">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                {{ __('messages.codes.generate_code') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="px-4 py-3 bg-zinc-50 dark:bg-zinc-800/30 border-t border-zinc-200 dark:border-zinc-800">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-zinc-500">
                        {{ __('messages.codes.job_started') }} {{ $job->updated_at?->diffForHumans() ?? __('messages.codes.recently') }}
                    </span>
                    <a href="{{ route('messages') }}?user={{ $job->hiredWorker?->id }}" class="text-emerald-600 hover:text-emerald-700 font-medium" wire:navigate>
                        {{ __('messages.codes.chat_with') }} {{ $job->hiredWorker?->name }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Service requests (huduma) --}}
    <div class="space-y-4 mt-10">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-zinc-500">{{ __('messages.codes.section_huduma') }}</h2>
        @foreach($serviceRequests as $sr)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-sky-200/80 dark:border-sky-900/40 shadow-sm overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-sky-50 to-cyan-50 dark:from-sky-900/20 dark:to-cyan-900/20 border-b border-zinc-200 dark:border-zinc-800">
                <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white w-full">{{ $sr->service->title }}</h3>
                    @if($usesServicePackages && $sr->package)
                        <span class="text-xs text-zinc-500">{{ $sr->package->title }}</span>
                    @endif
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $sr->service->user->name ?? __('messages.codes.not_assigned') }}
                    </span>
                    <span class="flex items-center gap-1">
                        TZS {{ number_format($sr->payment?->amount ?? 0) }}
                    </span>
                    <span class="px-2 py-0.5 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded-full text-xs font-medium">
                        {{ __('messages.codes.in_progress') }}
                    </span>
                </div>
            </div>

            <div class="p-4">
                @if($sr->completion_code)
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 border border-emerald-200 dark:border-emerald-800">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm text-emerald-700 dark:text-emerald-400 font-medium mb-1">{{ __('messages.codes.code_generated') }}</p>
                                <div class="flex items-center gap-3">
                                    <div class="px-4 py-2 bg-white dark:bg-zinc-800 rounded-lg font-mono font-bold text-xl text-emerald-600 dark:text-emerald-400 tracking-widest shadow-sm">
                                        {{ $sr->completion_code }}
                                    </div>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $sr->completion_code }}')" class="p-2 text-zinc-500 hover:text-emerald-600 transition-colors" title="{{ __('messages.codes.copy') }}">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                                @if($sr->code_generated_at)
                                    <p class="text-xs text-zinc-500 mt-2">{{ __('messages.codes.code_issued') }} {{ $sr->code_generated_at->diffForHumans() }}</p>
                                @endif
                            </div>
                            <div class="flex flex-col gap-2">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sr->service->user->phone ?? '') }}?text=Code%20ya%20huduma%20%22{{ urlencode($sr->service->title) }}%22%20ni%3A%20{{ $sr->completion_code }}"
                                   target="_blank"
                                   class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    {{ __('messages.codes.send_whatsapp') }}
                                </a>
                                @if($sr->isOnCodeHold())
                                    <div class="px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-sm rounded-lg text-center">
                                        {{ __('messages.codes.hold_until') }} {{ $sr->code_hold_until->format('H:i') }}
                                    </div>
                                    @if(!$sr->hasExtendedHold())
                                        <button type="button" wire:click="openExtendForm('service_request', {{ $sr->id }})"
                                                class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                            Ongeza Masaa 3
                                        </button>
                                    @endif
                                @else
                                    <button type="button" wire:click="holdServiceRequestCode({{ $sr->id }})"
                                            wire:confirm="{{ __('messages.codes.hold_confirm_3h') }}"
                                            class="px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                                        Shikilia Masaa 3
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm text-zinc-700 dark:text-zinc-300 font-medium mb-1">{{ __('messages.codes.no_code') }}</p>
                                <p class="text-xs text-zinc-500">{{ __('messages.codes.no_code_huduma_desc') }}</p>
                            </div>
                            <button type="button" wire:click="generateServiceRequestCode({{ $sr->id }})"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-all duration-200 shadow-lg shadow-emerald-500/20">
                                {{ __('messages.codes.generate_code') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="px-4 py-3 bg-zinc-50 dark:bg-zinc-800/30 border-t border-zinc-200 dark:border-zinc-800">
                <a href="{{ route('messages') }}?user={{ $sr->service->user_id }}" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm" wire:navigate>
                    {{ __('messages.codes.chat_with') }} {{ $sr->service->user->name }}
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if($jobs->isEmpty() && $serviceRequests->isEmpty())
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center mt-8">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.codes.all_empty_title') }}</h3>
            <p class="text-zinc-500 dark:text-zinc-400 mb-4 max-w-md mx-auto">
                {{ __('messages.codes.all_empty_desc') }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('mteja.maombi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors" wire:navigate>
                    {{ __('messages.codes.view_applications') }}
                </a>
                <a href="{{ route('mteja.huduma-malipo') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 text-white font-medium rounded-lg hover:bg-sky-700 transition-colors" wire:navigate>
                    {{ __('messages.codes.huduma_pay_link') }}
                </a>
                <a href="{{ route('mteja.post-kazi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-zinc-200 transition-colors" wire:navigate>
                    {{ __('messages.codes.post_job') }}
                </a>
            </div>
        </div>
    @endif

    @if($extendingHoldKind)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="closeExtendForm">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-md w-full p-6" wire:click.stop>
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.codes.extend_modal_title') }}</h3>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">{{ __('messages.codes.extend_modal_body') }}</p>
            <form wire:submit="extendHold">
                <textarea wire:model="holdComment" rows="3" placeholder="{{ __('messages.codes.extend_placeholder') }}" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 mb-2"></textarea>
                @error('holdComment') <p class="text-red-500 text-xs mb-2">{{ $message }}</p> @enderror
                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="closeExtendForm" class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-sm font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                        {{ __('messages.codes.extend_cancel') }}
                    </button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                        {{ __('messages.codes.extend_submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($jobs->hasPages())
    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
    @endif
    @if($serviceRequests->hasPages())
    <div class="mt-6">
        {{ $serviceRequests->links() }}
    </div>
    @endif
</div>
