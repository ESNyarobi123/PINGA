<div>
    {{-- Compact Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.wallet.title') }}</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.wallet.subtitle') }}</p>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success_message'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl mb-4 flex items-center gap-3 animate-in fade-in slide-in-from-top-2 duration-300">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm">{{ session('success_message') }}</span>
        </div>
        @endif

        @if(session('error_message'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl mb-4 flex items-center gap-3 animate-in fade-in slide-in-from-top-2 duration-300">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm">{{ session('error_message') }}</span>
        </div>
        @endif
    </div>

    {{-- Compact Balance Card --}}
    <div class="group bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl p-6 mb-6 text-white shadow-lg hover:shadow-2xl hover:scale-[1.02] transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-purple-100 text-xs mb-1">{{ __('messages.wallet.balance') }}</p>
                <p class="text-3xl font-bold mb-3">TZS {{ number_format($balance) }}</p>
                <div class="flex items-center gap-3">
                    <button wire:click="openDepositModal" class="group/btn px-4 py-2 bg-white text-purple-600 font-bold rounded-lg hover:bg-purple-50 hover:scale-105 transition-all duration-200 shadow-lg">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 group-hover/btn:rotate-90 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('messages.wallet.deposit') }}
                        </span>
                    </button>
                    <button wire:click="openWithdrawModal" class="group/btn px-4 py-2 bg-purple-800/50 text-white font-bold rounded-lg hover:bg-purple-800/70 hover:scale-105 transition-all duration-200 border border-white/20">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Toa Pesa
                        </span>
                    </button>
                </div>
            </div>
            <div class="hidden md:block">
                <svg class="w-20 h-20 opacity-20 group-hover:opacity-30 transition-opacity" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Transaction History - Compact Cards --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="p-4 border-b border-zinc-200 dark:border-zinc-800">
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.wallet.history') }}</h2>
        </div>
        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($transactions as $transaction)
            <div class="group p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 hover:border-l-4 hover:border-l-{{ $transaction->type === 'credit' ? 'emerald' : 'red' }}-500 transition-all duration-200">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            {{ $transaction->type === 'credit' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' }}
                            group-hover:scale-110 transition-transform duration-200">
                            @if($transaction->type === 'credit')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            @else
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-zinc-900 dark:text-white text-sm truncate">{{ $transaction->description }}</p>
                            <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                                <span>{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                                @if($transaction->reference)
                                <span class="truncate">• Ref: {{ $transaction->reference }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-base font-bold {{ $transaction->type === 'credit' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $transaction->type === 'credit' ? '+' : '-' }} {{ number_format($transaction->amount) }}
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ number_format($transaction->balance_after) }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                    <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.wallet.no_transactions') }}</h3>
                <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.wallet.no_transactions_desc') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Deposit Modal --}}
    @if($showDepositModal)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="$set('showDepositModal', false)">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-md w-full" wire:click.stop>
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-emerald-600 to-teal-500 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-black">{{ __('messages.wallet.deposit_title') }}</h2>
                    <button wire:click="$set('showDepositModal', false)" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Content --}}
            <div class="p-6">
                @if($depositStep === 1)
                {{-- Step 1: Choose Payment Method --}}
                <div class="space-y-3">
                    <p class="text-zinc-600 dark:text-zinc-400 mb-4">{{ __('messages.wallet.choose_method') }}</p>
                    
                    <button wire:click="setPaymentMethod('mobile')" class="w-full flex items-center gap-4 p-4 border-2 border-zinc-200 dark:border-zinc-700 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 text-left">
                            <p class="font-bold text-zinc-900 dark:text-white">{{ __('messages.wallet.mobile_money') }}</p>
                            <p class="text-sm text-zinc-500">M-Pesa, TigoPesa, Airtel Money</p>
                        </div>
                    </button>

                    <button wire:click="setPaymentMethod('card')" class="w-full flex items-center gap-4 p-4 border-2 border-zinc-200 dark:border-zinc-700 rounded-xl hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors">
                        <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1 text-left">
                            <p class="font-bold text-zinc-900 dark:text-white">{{ __('messages.wallet.card_payment') }}</p>
                            <p class="text-sm text-zinc-500">Visa, Mastercard</p>
                        </div>
                    </button>
                </div>

                @elseif($depositStep === 2)
                {{-- Step 2: Enter Amount --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.wallet.amount') }}</label>
                        <input type="number" wire:model="amount" placeholder="10000" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white">
                        @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if($paymentMethod === 'mobile')
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.wallet.phone') }}</label>
                        <input type="text" wire:model="phone" placeholder="0712345678" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white">
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div class="flex gap-3 pt-4">
                        <button wire:click="$set('depositStep', 1)" class="flex-1 px-4 py-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                            {{ __('messages.wallet.back') }}
                        </button>
                        <button wire:click="processPayment" class="flex-1 px-4 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                            {{ __('messages.wallet.continue') }}
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Withdrawal Modal (Task 8) --}}
    @if($showWithdrawModal)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="closeWithdrawModal">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-md w-full" wire:click.stop>
            <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Toa Pesa kwenye Wallet</h2>
                    <button wire:click="closeWithdrawModal" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3">
                    <p class="text-xs text-amber-700 dark:text-amber-400">
                        <span class="font-semibold">Ada ya kutoa:</span> {{ $withdrawalChargePercent }}% ya kiasi unachotoa itakatwa kama ada ya huduma.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Kiasi (TZS)</label>
                    <input type="number" wire:model.live="withdrawAmount" placeholder="50000" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-zinc-800 dark:text-white">
                    @error('withdrawAmount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                    @if($withdrawAmount > 0)
                    @php
                        $wCharge = round($withdrawAmount * ($withdrawalChargePercent / 100), 2);
                        $wTotal = $withdrawAmount + $wCharge;
                    @endphp
                    <div class="mt-2 p-2.5 bg-zinc-50 dark:bg-zinc-800 rounded-lg text-xs space-y-1">
                        <div class="flex justify-between"><span class="text-zinc-500">Kiasi cha kutoa:</span><span class="font-semibold text-zinc-900 dark:text-white">TZS {{ number_format($withdrawAmount) }}</span></div>
                        <div class="flex justify-between"><span class="text-zinc-500">Ada ({{ $withdrawalChargePercent }}%):</span><span class="font-semibold text-red-600">- TZS {{ number_format($wCharge) }}</span></div>
                        <div class="flex justify-between pt-1 border-t border-zinc-200 dark:border-zinc-700"><span class="text-zinc-700 dark:text-zinc-300 font-semibold">Jumla itakayokatwa:</span><span class="font-bold text-zinc-900 dark:text-white">TZS {{ number_format($wTotal) }}</span></div>
                    </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Namba ya Simu</label>
                    <input type="text" wire:model="withdrawPhone" placeholder="0712345678" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-zinc-800 dark:text-white">
                    @error('withdrawPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Mtandao</label>
                    <select wire:model="withdrawNetwork" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-zinc-800 dark:text-white">
                        <option value="vodacom">Vodacom (M-Pesa)</option>
                        <option value="tigopesa">TigoPesa</option>
                        <option value="airtel">Airtel Money</option>
                        <option value="halotel">Halotel</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <button wire:click="closeWithdrawModal" class="flex-1 px-4 py-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                        Ghairi
                    </button>
                    <button wire:click="submitWithdrawal" class="flex-1 px-4 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <span wire:loading.remove wire:target="submitWithdrawal">Toa Pesa</span>
                        <span wire:loading wire:target="submitWithdrawal">Inaendelea...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
