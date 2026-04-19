<div>
    {{-- Page Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.mapato.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.mapato.subtitle') }}</p>
            </div>
            <a href="{{ route('winga.tomba-ombi') }}" class="px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors" wire:navigate>
                {{ __('messages.mapato.request_withdrawal') }}
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($totalEarned) }}</p>
                    <p class="text-sm text-zinc-500">{{ __('messages.mapato.total_earned') }}</p>
                </div>
            </div>
            <div class="text-xs text-green-600 font-medium">{{ __('messages.mapato.so_far') }}</div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($walletBalance) }}</p>
                    <p class="text-sm text-zinc-500">{{ __('messages.mapato.wallet_balance') }}</p>
                </div>
            </div>
            <div class="text-xs text-purple-600 font-medium">{{ __('messages.mapato.loaded') }}</div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($thisMonth) }}</p>
                    <p class="text-sm text-zinc-500">{{ __('messages.mapato.this_month') }}</p>
                </div>
            </div>
            <div class="text-xs text-amber-600 font-medium">{{ __('messages.mapato.this_month_label') }}</div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($totalWithdrawn) }}</p>
                    <p class="text-sm text-zinc-500">{{ __('messages.mapato.total_withdrawn') }}</p>
                </div>
            </div>
            <div class="text-xs text-red-600 font-medium">{{ __('messages.mapato.withdrawn_label') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent Transactions --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-800">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.mapato.recent_transactions') }}</h2>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @if($transactions->count() > 0)
                    @foreach($transactions as $transaction)
                    <div class="p-6 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg @if($transaction->type === 'credit') bg-green-100 dark:bg-green-900/30 @else bg-red-100 dark:bg-red-900/30 @endif flex items-center justify-center">
                                    <svg class="w-5 h-5 @if($transaction->type === 'credit') text-green-600 @else text-red-600 @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($transaction->type === 'credit')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">{{ $transaction->description ?? 'Transaction' }}</p>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $transaction->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold @if($transaction->type === 'credit') text-green-600 @else text-red-600 @endif">
                                    @if($transaction->type === 'credit')+@else-@endif TZS {{ number_format($transaction->amount) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                        <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.mapato.no_transactions') }}</h3>
                    <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.mapato.no_transactions_desc') }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Recent Payments --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-800">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.mapato.recent_payments') }}</h2>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @if($recentPayments->count() > 0)
                    @foreach($recentPayments as $payment)
                    <div class="p-6 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">{{ $payment->job->title }}</p>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $payment->escrow_released_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">+ TZS {{ number_format($payment->worker_amount) }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('messages.mapato.paid') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                        <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.mapato.no_payments') }}</h3>
                    <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.mapato.no_payments_desc') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
