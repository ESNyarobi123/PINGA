<div>
    {{-- Page Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.tomba_ombi.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.tomba_ombi.subtitle') }}</p>
            </div>
            <a href="{{ route('winga.mapato') }}" class="px-4 py-2 border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors" wire:navigate>
                {{ __('messages.tomba_ombi.back_to_earnings') }}
            </a>
        </div>
    </div>

    {{-- Wallet Balance Card --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl p-6 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-white/80 uppercase tracking-wide">{{ __('messages.tomba_ombi.wallet_balance') }}</p>
                <p class="text-3xl font-bold mt-1">TZS {{ number_format($balance) }}</p>
            </div>
            <div class="flex flex-col items-end gap-3">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                @if(! $showForm && $balance >= 1000)
                <button wire:click="openForm"
                    class="px-4 py-2 bg-white text-purple-700 font-semibold rounded-lg hover:bg-purple-50 transition-colors text-sm shadow">
                    {{ __('messages.tomba_ombi.request_withdrawal') }}
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Withdrawal Form (shown when showForm=true) --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
            @if($showForm)
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.tomba_ombi.new_request') }}</h2>
                <button wire:click="closeForm" class="p-2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit="submit" class="space-y-5">
                {{-- Amount --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                        {{ __('messages.tomba_ombi.amount_label') }}
                    </label>
                    <input
                        wire:model="amount"
                        type="number"
                        min="1000"
                        max="{{ $balance }}"
                        step="100"
                        placeholder="{{ __('messages.tomba_ombi.amount_placeholder') }}"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-winga-500 focus:border-winga-500"
                    />
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.tomba_ombi.amount_hint') }} {{ number_format($balance) }}</p>
                    @error('amount')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                        {{ __('messages.tomba_ombi.phone_label') }}
                    </label>
                    <input
                        wire:model.live="phone"
                        type="tel"
                        placeholder="{{ __('messages.tomba_ombi.phone_placeholder') }}"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-winga-500 focus:border-winga-500"
                    />
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Network (auto-detected) --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        {{ __('messages.tomba_ombi.network_label') }}
                    </label>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach([
                            'vodacom'  => ['label' => 'M-Pesa', 'icon' => 'mobile-networks/vodacom.jpeg'],
                            'airtel'   => ['label' => 'Airtel Money', 'icon' => 'mobile-networks/airtel.png'],
                            'tigo'     => ['label' => 'TigoPesa', 'icon' => 'mobile-networks/yas.png'],
                            'halopesa' => ['label' => 'HaloPesa', 'icon' => 'mobile-networks/halopesa.jpeg'],
                        ] as $value => $network)
                        <label class="cursor-pointer flex flex-col items-center gap-2">
                            <input type="radio" wire:model="network" value="{{ $value }}" class="sr-only peer" />
                            <div class="w-14 h-14 rounded-full border-2 overflow-hidden flex items-center justify-center bg-white dark:bg-zinc-800 transition-all duration-200
                                peer-checked:border-winga-500 peer-checked:ring-2 peer-checked:ring-winga-200 dark:peer-checked:ring-winga-800 peer-checked:shadow-lg peer-checked:shadow-winga-200/50 dark:peer-checked:shadow-winga-900/30
                                border-zinc-200 dark:border-zinc-700 hover:border-zinc-400 dark:hover:border-zinc-500">
                                <img src="{{ asset($network['icon']) }}" alt="{{ $network['label'] }}" class="w-10 h-10 object-contain">
                            </div>
                            <span class="text-[11px] font-medium text-zinc-600 dark:text-zinc-400 text-center leading-tight transition-colors
                                peer-checked:text-winga-600 dark:peer-checked:text-winga-400 peer-checked:font-semibold">
                                {{ $network['label'] }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @error('network')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-winga-600 text-white font-semibold rounded-lg hover:bg-winga-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submit">{{ __('messages.tomba_ombi.submit') }}</span>
                        <span wire:loading wire:target="submit">{{ __('messages.tomba_ombi.submitting') }}</span>
                    </button>
                    <button type="button" wire:click="closeForm"
                        class="px-4 py-3 border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        {{ __('messages.tomba_ombi.cancel') }}
                    </button>
                </div>
            </form>
            @else
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div class="w-16 h-16 rounded-full bg-winga-100 dark:bg-winga-900/20 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-winga-600 dark:text-winga-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                @if($balance < 1000)
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.tomba_ombi.insufficient_balance') }}</h3>
                <p class="text-zinc-500 dark:text-zinc-400 text-sm">{{ __('messages.tomba_ombi.insufficient_desc') }}</p>
                @else
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.tomba_ombi.request_title') }}</h3>
                <p class="text-zinc-500 dark:text-zinc-400 text-sm mb-4">{{ __('messages.tomba_ombi.request_desc') }}</p>
                <button wire:click="openForm"
                    class="px-6 py-3 bg-winga-600 text-white font-semibold rounded-lg hover:bg-winga-700 transition-colors">
                    {{ __('messages.tomba_ombi.request_btn') }}
                </button>
                @endif
            </div>
            @endif
        </div>

        {{-- Recent Requests --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-6">{{ __('messages.tomba_ombi.recent_requests') }}</h2>

            @if($requests->count() > 0)
            <div class="space-y-4">
                @foreach($requests as $request)
                <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-lg font-bold text-zinc-900 dark:text-white">TZS {{ number_format($request->amount) }}</span>
                                @switch($request->status)
                                    @case('pending')
                                        <span class="inline-flex items-center rounded-md bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-medium px-2.5 py-1">{{ __('messages.tomba_ombi.status_pending') }}</span>
                                        @break
                                    @case('approved')
                                        <span class="inline-flex items-center rounded-md bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium px-2.5 py-1">{{ __('messages.tomba_ombi.status_approved') }}</span>
                                        @break
                                    @case('paid')
                                        <span class="inline-flex items-center rounded-md bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium px-2.5 py-1">{{ __('messages.tomba_ombi.status_paid') }}</span>
                                        @break
                                    @case('rejected')
                                        <span class="inline-flex items-center rounded-md bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-medium px-2.5 py-1">{{ __('messages.tomba_ombi.status_rejected') }}</span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 text-xs font-medium px-2.5 py-1">{{ ucfirst($request->status) }}</span>
                                @endswitch
                            </div>

                            <div class="text-sm text-zinc-600 dark:text-zinc-400 space-y-1">
                                <p><strong>{{ __('messages.tomba_ombi.network') }}</strong> {{ $request->methodLabel() }}</p>
                                <p><strong>{{ __('messages.tomba_ombi.number') }}</strong> {{ $request->account_number }}</p>
                                @if($request->payout_reference)
                                <p class="font-mono text-xs text-zinc-400">Ref: {{ $request->payout_reference }}</p>
                                @endif
                                <p><strong>{{ __('messages.tomba_ombi.date') }}</strong> {{ $request->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                    <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.tomba_ombi.no_requests') }}</h3>
                <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.tomba_ombi.no_requests_desc') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
