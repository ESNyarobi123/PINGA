<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.huduma_malipo.title') }}</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.huduma_malipo.subtitle') }}</p>
        <p class="text-xs text-sky-800 dark:text-sky-200 mt-3 rounded-lg border border-sky-200 dark:border-sky-800 bg-sky-50 dark:bg-sky-950/25 px-3 py-2 max-w-2xl">{{ __('messages.huduma_malipo.codes_hint') }}</p>
    </div>

    <section class="space-y-3">
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('messages.huduma_malipo.awaiting_payment') }}</h2>
        @forelse($awaitingPayment as $req)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-semibold text-zinc-900 dark:text-white">{{ $req->service->title }}</p>
                    @if($usesServicePackages && $req->package)
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $req->package->title }} · TZS {{ number_format($req->package->price) }}</p>
                    @else
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">TZS {{ number_format($req->agreedAmount()) }}</p>
                    @endif
                    <p class="text-xs text-zinc-500 mt-1">{{ __('messages.huduma_malipo.worker') }} {{ $req->service->user->name ?? '—' }}</p>
                </div>
                <flux:button variant="primary" wire:click="openPaymentModal({{ $req->id }})" wire:loading.attr="disabled">
                    {{ __('messages.huduma_malipo.pay_cta') }}
                </flux:button>
            </div>
        @empty
            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.huduma_malipo.none_awaiting') }}</p>
        @endforelse
    </section>

    <section class="space-y-3">
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('messages.huduma_malipo.in_progress') }}</h2>
        @forelse($inProgress as $req)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-amber-200/80 dark:border-amber-900/40 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-semibold text-zinc-900 dark:text-white">{{ $req->service->title }}</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('messages.huduma_malipo.escrowed') }} TZS {{ number_format($req->payment?->amount ?? 0) }}
                    </p>
                </div>
                <flux:button :href="route('mteja.codes')" variant="outline" wire:navigate>
                    {{ __('messages.huduma_malipo.open_codes') }}
                </flux:button>
            </div>
        @empty
            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.huduma_malipo.none_in_progress') }}</p>
        @endforelse
    </section>

    @if($showPaymentModal)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="closePaymentModal">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-md w-full" wire:click.stop>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.huduma_malipo.modal_title') }}</h3>
                    <button type="button" wire:click="closePaymentModal" class="text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 p-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-3 mb-5 space-y-1.5">
                    @php $feePercent = \App\Models\Payment::getPlatformFeePercent(); @endphp
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-zinc-600 dark:text-zinc-400">{{ __('messages.huduma_malipo.modal_price_label') }}</span>
                        <span class="font-semibold text-zinc-900 dark:text-white">TZS {{ number_format($servicePriceAmount ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-zinc-400 dark:text-zinc-500">
                        <span>{{ __('messages.huduma_malipo.modal_fee_note', ['percent' => $feePercent]) }}</span>
                        <span>- TZS {{ number_format($platformFeeAmount ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm pt-1.5 border-t border-emerald-200 dark:border-emerald-700">
                        <span class="text-emerald-700 dark:text-emerald-400 font-bold">{{ __('messages.huduma_malipo.modal_you_pay') }}</span>
                        <span class="text-lg font-bold text-emerald-700 dark:text-emerald-400">TZS {{ number_format($paymentAmount ?? 0) }}</span>
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ $paymentMethod === 'wallet' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="wallet" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span class="font-semibold text-zinc-900 dark:text-white text-sm">Wallet</span>
                            </div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.huduma_malipo.modal_wallet_balance', ['amount' => number_format(auth()->user()->wallet_balance)]) }}</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ $paymentMethod === 'mobile' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="mobile" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span class="font-semibold text-zinc-900 dark:text-white text-sm">Mobile Money</span>
                            </div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">M-Pesa, TigoPesa, AirtelMoney</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ $paymentMethod === 'card' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="card" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span class="font-semibold text-zinc-900 dark:text-white text-sm">{{ __('messages.huduma_malipo.modal_card') }}</span>
                            </div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Visa, Mastercard</p>
                        </div>
                    </label>
                </div>

                @if($paymentMethod !== 'wallet')
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3 mb-4">
                    <p class="text-xs text-amber-700 dark:text-amber-400">
                        <span class="font-semibold">{{ __('messages.huduma_malipo.modal_redirect_note_title') }}</span>
                        {{ __('messages.huduma_malipo.modal_redirect_note_body') }}
                    </p>
                </div>
                @endif

                <div class="mb-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg">
                    <p class="text-xs text-red-700 dark:text-red-400">
                        <span class="font-bold">{{ __('messages.huduma_malipo.modal_escrow_warning_title') }}</span>
                        {{ __('messages.huduma_malipo.modal_escrow_warning_body') }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <button type="button" wire:click="closePaymentModal" class="flex-1 px-4 py-2.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-sm font-medium rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                        {{ __('messages.huduma_malipo.modal_cancel') }}
                    </button>
                    <button type="button" wire:click="confirmPayment" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium rounded-xl hover:shadow-lg transition-all duration-200">
                        <span wire:loading.remove wire:target="confirmPayment">{{ __('messages.huduma_malipo.modal_continue') }}</span>
                        <span wire:loading wire:target="confirmPayment">{{ __('messages.huduma_malipo.modal_loading') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
