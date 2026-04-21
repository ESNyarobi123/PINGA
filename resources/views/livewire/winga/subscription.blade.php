<div>
    {{-- Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.subscription.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ __('messages.subscription.subtitle') }}</p>
            </div>
            @if($activeSub)
                <div class="rounded-2xl border-2 {{ $planUi['border_class'] ?? 'border-green-300 dark:border-green-700' }} bg-gradient-to-br from-white to-zinc-50 dark:from-zinc-900 dark:to-zinc-950 px-5 py-4 shadow-sm min-w-0 lg:max-w-md w-full">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide text-emerald-800 dark:text-emerald-200 ring-1 ring-emerald-200/80 dark:ring-emerald-800">
                            <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse" aria-hidden="true"></span>
                            {{ __('messages.subscription.status_active') }}
                        </span>
                        @if(!empty($planUi['badge']))
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ $planUi['badge_class'] ?? 'bg-zinc-100 text-zinc-800' }} ring-1 ring-black/5 dark:ring-white/10">
                                {{ $planUi['badge'] }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('messages.subscription.your_subscription') }}</p>
                    <p class="text-lg font-bold text-zinc-900 dark:text-white mt-0.5">{{ $planUi['name'] ?? $activeSub->subscriptionPlan?->name ?? '—' }}</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-2">
                        {{ __('messages.subscription.expires') }}
                        <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $activeSub->expires_at?->format('d M Y') ?? __('messages.subscription.expires_soon') }}</span>
                    </p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-2">{{ __('messages.subscription.renew_or_change') }}</p>
                </div>
            @else
                <div class="rounded-2xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 px-4 py-3 text-sm text-amber-700 dark:text-amber-300">
                    <p class="font-semibold">{{ __('messages.subscription.not_subscribed') }}</p>
                    <p class="text-xs">{{ __('messages.subscription.not_subscribed_desc') }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            @php
                $isCurrentPlan = $activeSub && (
                    ($activeSub->subscription_plan_id && (int) $activeSub->subscription_plan_id === (int) $plan->id)
                    || (! $activeSub->subscription_plan_id && $activeSub->plan_slug === $plan->slug)
                );
            @endphp
            <div class="relative rounded-2xl border {{ $isCurrentPlan ? 'border-winga-500 dark:border-winga-500 ring-2 ring-winga-400/50 dark:ring-winga-600/40 shadow-lg' : ($plan->is_recommended ? 'border-purple-400 dark:border-purple-500 shadow-xl' : 'border-zinc-200 dark:border-zinc-800 shadow-sm') }} bg-white dark:bg-zinc-900 p-6 flex flex-col gap-4">
                @if($isCurrentPlan)
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-winga-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md whitespace-nowrap">{{ __('messages.subscription.current_plan_card') }}</span>
                @elseif($plan->is_recommended)
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-purple-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">{{ __('messages.subscription.recommended') }}</span>
                @endif

                <div>
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $plan->name }}</h2>
                        <span class="text-sm text-zinc-500">{{ $plan->durationLabel() }}</span>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="inline-flex items-center shrink-0" aria-hidden="true">
                            @if($plan->slug === 'winga-complex')
                                <x-fluent-icon name="wrench-screwdriver-24" :size="28" />
                            @elseif($plan->slug === 'winga-karume')
                                <x-fluent-icon name="star-24" :size="28" />
                            @else
                                <x-fluent-icon name="trophy-24" :size="28" />
                            @endif
                        </span>
                        <p class="text-3xl font-extrabold text-zinc-900 dark:text-white">TZS {{ number_format($plan->price) }}</p>
                    </div>
                </div>

                <ul class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                    @foreach($plan->features ?? [] as $feature)
                        <li class="flex items-center gap-2">
                            <span class="inline-flex size-5 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 text-green-600">
                                <x-fluent-icon name="checkmark-circle-16" :size="16" />
                            </span>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                @if($isCurrentPlan)
                    <div class="mt-auto w-full rounded-xl px-4 py-3 text-sm font-semibold text-center bg-winga-100 dark:bg-winga-900/40 text-winga-800 dark:text-winga-200 border border-winga-200 dark:border-winga-800">
                        {{ __('messages.subscription.current_plan_card') }}
                    </div>
                @else
                    <button
                        type="button"
                        class="mt-auto w-full rounded-xl px-4 py-3 text-sm font-semibold transition-colors {{ $selectedPlanId === $plan->id ? 'bg-green-600 text-white' : 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' }}"
                        wire:click="selectPlan({{ $plan->id }})"
                    >
                        {{ $selectedPlanId === $plan->id ? __('messages.subscription.selected') : __('messages.subscription.select_plan') }}
                    </button>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Confirmation Drawer --}}
    @if($showConfirm && $selectedPlanId)
        @php($selectedPlan = $plans->firstWhere('id', $selectedPlanId))
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" wire:transition.fade>
            <div class="relative w-full max-w-xl rounded-3xl border border-winga-200/60 dark:border-winga-800/60 bg-white dark:bg-zinc-900 shadow-[0_25px_80px_rgba(34,197,94,0.25)]" wire:click.away="cancelConfirm">
                <div class="absolute inset-x-0 -top-1 h-1 rounded-full bg-gradient-to-r from-winga-500 via-emerald-400 to-amber-300"></div>
                <div class="p-6 space-y-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-winga-500">{{ __('messages.subscription.confirm_plan') }}</p>
                            <h3 class="text-2xl font-black text-zinc-900 dark:text-white">{{ $selectedPlan->name ?? __('messages.subscription.plan_label') }}</h3>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $selectedPlan->durationLabel() ?? __('messages.subscription.plan_duration') }}</p>
                        </div>
                        <button type="button" class="text-zinc-400 hover:text-zinc-600 inline-flex p-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800" wire:click="cancelConfirm" aria-label="{{ __('messages.subscription.cancel') }}">
                            <x-fluent-icon name="dismiss-circle-20" :size="22" />
                        </button>
                    </div>

                    <div class="rounded-2xl bg-winga-50 dark:bg-winga-900/20 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-winga-600 uppercase">{{ __('messages.subscription.plan_fee') }}</p>
                            <p class="text-4xl font-extrabold text-zinc-900 dark:text-white">TZS {{ number_format($selectedPlan->price ?? 0) }}</p>
                        </div>
                        <span class="rounded-full border border-winga-200/60 px-4 py-1 text-xs font-semibold text-winga-600">{{ ucfirst($paymentMethod) }}</span>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-200 mb-3">{{ __('messages.subscription.choose_payment') }}</p>
                        <div class="grid grid-cols-3 gap-3 text-sm">
                            @foreach(['wallet' => 'Wallet', 'mobile' => 'Mobile', 'card' => 'Kadi'] as $method => $label)
                                <button type="button" wire:click="$set('paymentMethod','{{ $method }}')"
                                    class="rounded-2xl border px-4 py-3 font-semibold transition {{ $paymentMethod === $method ? 'border-winga-500 bg-winga-500/10 text-winga-700 dark:text-winga-300 ring-2 ring-winga-200' : 'border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if($paymentMethod === 'mobile')
                        <div>
                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('messages.subscription.phone_label') }}</label>
                            <input type="tel" wire:model.defer="phone" class="mt-2 w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white/70 dark:bg-zinc-900/70 px-3 py-2" placeholder="07xxxxxxxx" />
                        </div>
                    @endif

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="button" wire:click="pay" wire:loading.attr="disabled"
                            class="flex-1 rounded-2xl bg-gradient-to-r from-winga-600 via-emerald-500 to-amber-400 hover:brightness-105 text-white py-3 font-semibold flex items-center justify-center gap-2 shadow-lg shadow-winga-500/40 text-base">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span wire:loading.remove>{{ __('messages.subscription.pay_now') }} &mdash; {{ $selectedPlan->name ?? '' }}</span>
                            <span wire:loading>{{ __('messages.subscription.processing') }}</span>
                        </button>
                        <button type="button" wire:click="cancelConfirm" class="rounded-2xl border border-zinc-300 dark:border-zinc-700 px-4 py-3 text-zinc-600 dark:text-zinc-300 font-semibold">{{ __('messages.subscription.cancel') }}</button>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 text-center">{{ __('messages.subscription.payment_note') }}</p>
                </div>
            </div>
        </div>
    @endif
</div>
