<div class="space-y-6" wire:poll.60s>
    {{-- Hero --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-winga-500">{{ __('messages.weka_code.verification') }}</p>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.weka_code.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.weka_code.subtitle') }}</p>
            </div>
            <div class="rounded-2xl bg-winga-50 dark:bg-winga-900/20 border border-winga-200 dark:border-winga-800 px-5 py-3 text-sm text-winga-700 dark:text-winga-200">
                <p class="font-semibold">{{ __('messages.weka_code.steps_title') }}</p>
                <ol class="list-decimal ml-5 space-y-1 text-xs text-winga-600 dark:text-winga-300">
                    <li>{{ __('messages.weka_code.step1') }}</li>
                    <li>{{ __('messages.weka_code.step2') }}</li>
                    <li>{{ __('messages.weka_code.step3') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-8">
            {{-- Jobs --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('messages.weka_code.payment_stage') }}</h2>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ count($myActiveJobs) }}</span>
                </div>

                @if(count($myActiveJobs) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($myActiveJobs as $jobOption)
                            <button type="button" wire:click="selectJob({{ $jobOption['id'] }})"
                                class="text-left rounded-2xl border {{ $job?->id === $jobOption['id'] ? 'border-winga-500 bg-winga-50 dark:bg-winga-900/20' : 'border-zinc-200 dark:border-zinc-800' }} p-4 transition shadow-sm hover:shadow">
                                <p class="text-xs text-zinc-500">{{ __('messages.weka_code.employer') }} {{ $jobOption['employer']['name'] ?? '—' }}</p>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $jobOption['title'] }}</h3>
                                <p class="text-xs text-winga-600 dark:text-winga-400 mt-2">{{ __('messages.weka_code.kind_job') }}</p>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 p-6 text-center text-zinc-500 dark:text-zinc-400 text-sm">
                        {{ __('messages.weka_code.no_jobs') }}
                    </div>
                @endif
            </div>

            {{-- Service requests: mteja amelipa (escrow) — hapa ndipo code inawekwa --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('messages.weka_code.huduma_stage') }}</h2>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-500">{{ count($myActiveServiceRequests) }}</span>
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 -mt-2">{{ __('messages.weka_code.huduma_stage_hint') }}</p>

                @if(count($myActiveServiceRequests) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($myActiveServiceRequests as $srOption)
                            <button type="button" wire:click="selectServiceRequest({{ $srOption['id'] }})"
                                class="text-left rounded-2xl border {{ $serviceRequest?->id === $srOption['id'] ? 'border-winga-500 bg-winga-50 dark:bg-winga-900/20' : 'border-zinc-200 dark:border-zinc-800' }} p-4 transition shadow-sm hover:shadow">
                                <p class="text-xs text-zinc-500">{{ __('messages.weka_code.client') }} {{ $srOption['client']['name'] ?? '—' }}</p>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $srOption['service']['title'] ?? '—' }}</h3>
                                <p class="text-xs text-winga-600 dark:text-winga-400 mt-2">{{ __('messages.weka_code.kind_huduma_ready') }}</p>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 p-6 text-center space-y-2">
                        <p class="text-zinc-600 dark:text-zinc-400 text-sm">{{ __('messages.weka_code.no_huduma') }}</p>
                        @if(count($awaitingPaymentServiceRequests) > 0)
                            <p class="text-xs text-amber-700 dark:text-amber-300">{{ __('messages.weka_code.no_huduma_see_below') }}</p>
                        @else
                            <p class="text-xs text-zinc-500">{{ __('messages.weka_code.no_huduma_help') }}</p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Yaliyokubaliwa bado hayajalipiwa — haionekani hapa mpaka mteja alipe --}}
            @if(count($awaitingPaymentServiceRequests) > 0)
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('messages.weka_code.huduma_awaiting_pay_title') }}</h2>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-200">{{ count($awaitingPaymentServiceRequests) }}</span>
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 -mt-2">{{ __('messages.weka_code.huduma_awaiting_pay_hint') }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($awaitingPaymentServiceRequests as $srWait)
                        <div class="rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/50 dark:bg-amber-950/20 p-4 text-left">
                            <p class="text-xs font-medium text-amber-800 dark:text-amber-200">{{ __('messages.weka_code.awaiting_pay_badge') }}</p>
                            <p class="text-xs text-zinc-500 mt-1">{{ __('messages.weka_code.client') }} {{ $srWait['client']['name'] ?? '—' }}</p>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mt-0.5">{{ $srWait['service']['title'] ?? '—' }}</h3>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Verification card --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 space-y-4">
            @if($job)
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-zinc-400">{{ __('messages.weka_code.selected_job') }}</p>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $job->getLocalizedTitle() }}</h3>
                    <p class="text-sm text-zinc-500">{{ __('messages.weka_code.by') }} {{ $job->employer?->name ?? __('messages.weka_code.employer') }}</p>
                </div>
            @elseif($serviceRequest)
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-zinc-400">{{ __('messages.weka_code.selected_huduma') }}</p>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $serviceRequest->service->title ?? '—' }}</h3>
                    <p class="text-sm text-zinc-500">{{ __('messages.weka_code.by') }} {{ $serviceRequest->client?->name ?? __('messages.weka_code.client') }}</p>
                </div>
            @endif

            @if($job || $serviceRequest)
                <div class="space-y-3">
                    <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('messages.weka_code.enter_code') }}</label>
                    <input type="text" wire:model.defer="code" maxlength="6" inputmode="numeric"
                        class="w-full rounded-2xl border border-zinc-200 dark:border-zinc-700 px-4 py-3 text-center text-xl tracking-[0.5em] font-semibold" placeholder="000000">

                    @if($error)
                        <div class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 text-red-600 text-sm px-3 py-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4m0 4h.01"/><path d="M12 5a7 7 0 1 1 0 14a7 7 0 0 1 0-14z"/></svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endif

                    @if($verified)
                        <div class="rounded-2xl border border-green-200 bg-green-50 text-green-700 px-4 py-3 text-sm">
                            {{ __('messages.weka_code.code_verified') }}
                        </div>
                    @endif

                    <button type="button" wire:click="verify" wire:loading.attr="disabled"
                        class="w-full rounded-2xl bg-winga-600 hover:bg-winga-700 text-white font-semibold py-3 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Z"/></svg>
                        <span wire:loading.remove>{{ __('messages.weka_code.verify') }}</span>
                        <span wire:loading>{{ __('messages.weka_code.verifying') }}</span>
                    </button>
                </div>
            @else
                <div class="text-center text-zinc-500">
                    <p>{{ __('messages.weka_code.select_prompt') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
