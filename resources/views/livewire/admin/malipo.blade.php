<div>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_malipo.title') }}</h1>
        <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_malipo.subtitle') }}</p>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 gap-4 mb-6">
        {{-- Total Revenue --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_malipo.all_time') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['totalRevenue']) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_malipo.total_revenue') }}</p>
        </div>

        {{-- Monthly Revenue --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ now()->format('F') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['monthlyRevenue']) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_malipo.monthly_revenue') }}</p>
        </div>

        {{-- Today's Revenue --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_malipo.today') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <p class="text-xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['todayRevenue']) }}</p>
                @if($stats['todayRevenue'] > $stats['yesterdayRevenue'])
                <span class="text-xs text-green-600">↑ {{ number_format(($stats['todayRevenue'] - $stats['yesterdayRevenue']) / max($stats['yesterdayRevenue'], 1) * 100) }}%</span>
                @else
                <span class="text-xs text-red-600">↓ {{ number_format(($stats['yesterdayRevenue'] - $stats['todayRevenue']) / max($stats['yesterdayRevenue'], 1) * 100) }}%</span>
                @endif
            </div>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_malipo.vs_yesterday') }}</p>
        </div>

        {{-- Current Escrow --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_malipo.live') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['currentEscrowBalance']) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_malipo.escrow_balance') }}</p>
        </div>

        {{-- Total Paid to Workers --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_malipo.all_time') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['totalPaidToWorkers']) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_malipo.paid_to_workers') }}</p>
        </div>

        {{-- Total Refunds --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_malipo.all_time') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['totalRefunds']) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_malipo.total_refunds') }}</p>
        </div>

        {{-- Failed Withdrawals --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_malipo.failed') }}</span>
            </div>
            <p class="text-xl font-bold text-red-600">{{ $stats['failedWithdrawalsCount'] }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_malipo.withdrawals') }}</p>
            @if($stats['failedWithdrawalsAmount'] > 0)
            <p class="text-xs text-red-500">TZS {{ number_format($stats['failedWithdrawalsAmount']) }}</p>
            @endif
        </div>

        {{-- Commission Rate --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_malipo.rate') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ $commissionRate }}%</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_malipo.commission') }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
        <div class="border-b border-zinc-200 dark:border-zinc-800 overflow-x-auto">
            <nav class="flex gap-6 sm:gap-8 px-6 min-w-max sm:min-w-0" aria-label="Tabs">
                <button type="button" wire:click="setActiveTab('transactions')"
                        class="{{ $activeTab === 'transactions' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition shrink-0">
                    {{ __('messages.admin_malipo.tab_transactions') }}
                </button>
                <button type="button" wire:click="setActiveTab('escrow')"
                        class="{{ $activeTab === 'escrow' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition shrink-0">
                    {{ __('messages.admin_malipo.tab_escrow') }}
                </button>
                <button type="button" wire:click="setActiveTab('withdrawals')"
                        class="{{ $activeTab === 'withdrawals' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition shrink-0">
                    {{ __('messages.admin_malipo.tab_withdrawals') }}
                </button>
                <button type="button" wire:click="setActiveTab('subscriptions')"
                        class="{{ $activeTab === 'subscriptions' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition shrink-0">
                    {{ __('messages.admin_malipo.tab_subscriptions') }}
                </button>
                <button type="button" wire:click="setActiveTab('settings')"
                        class="{{ $activeTab === 'settings' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition shrink-0">
                    {{ __('messages.admin_malipo.tab_settings') }}
                </button>
            </nav>
        </div>

        <div class="p-6">
            {{-- Tab 1: All Transactions --}}
            @if($activeTab === 'transactions')
            <div class="space-y-4">
                {{-- Filters --}}
                <div class="flex flex-wrap gap-4">
                    <input wire:model.live.debounce.300ms="search" 
                           type="text" 
                           placeholder="{{ __('messages.admin_malipo.search_placeholder') }}"
                           class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">

                    <select wire:model.live="filterType" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                        <option value="">{{ __('messages.admin_malipo.all_types') }}</option>
                        <option value="escrow">Escrow</option>
                        <option value="payout">Payout</option>
                        <option value="refund">Refund</option>
                        <option value="platform_fee">Platform Fee</option>
                        <option value="deposit">Deposit</option>
                        <option value="withdrawal">Withdrawal</option>
                    </select>

                    <select wire:model.live="filterStatus" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                        <option value="">{{ __('messages.admin_malipo.all_status') }}</option>
                        <option value="pending">{{ __('messages.admin_malipo.pending') }}</option>
                        <option value="completed">{{ __('messages.admin_malipo.completed') }}</option>
                        <option value="failed">{{ __('messages.admin_malipo.failed') }}</option>
                        <option value="held">{{ __('messages.admin_malipo.held') }}</option>
                        <option value="released">{{ __('messages.admin_malipo.released') }}</option>
                        <option value="refunded">{{ __('messages.admin_malipo.refunded') }}</option>
                    </select>

                    <select wire:model.live="filterMethod" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                        <option value="">{{ __('messages.admin_malipo.all_methods') }}</option>
                        <option value="snippe">Snippe</option>
                        <option value="wallet">Wallet</option>
                        <option value="bank">Bank</option>
                        <option value="mobile">Mobile Money</option>
                    </select>

                    <div class="flex gap-2">
                        <input wire:model.live="dateFrom" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                        <input wire:model.live="dateTo" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                    </div>

                    <button type="button" wire:click="exportTransactions" 
                            class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition">
                        📤 {{ __('messages.admin_malipo.export_csv') }}
                    </button>
                </div>

                {{-- Transactions Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Ref ID</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Fee</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                                <td class="px-4 py-3 text-sm font-mono text-zinc-900 dark:text-white">{{ $transaction->reference }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-bold rounded-lg
                                        {{ $transaction->type === 'escrow' ? 'bg-blue-100 text-blue-700' :
                                           ($transaction->type === 'payout' ? 'bg-green-100 text-green-700' :
                                           ($transaction->type === 'refund' ? 'bg-orange-100 text-orange-700' :
                                           ($transaction->type === 'platform_fee' ? 'bg-purple-100 text-purple-700' :
                                           'bg-zinc-100 text-zinc-700'))) }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-white">{{ $transaction->employer?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm font-bold text-zinc-900 dark:text-white">TZS {{ number_format($transaction->amount) }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    @if($transaction->fee)TZS {{ number_format($transaction->fee) }}@else—@endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-bold rounded-lg
                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-700' :
                                           ($transaction->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                           ($transaction->status === 'failed' ? 'bg-red-100 text-red-700' :
                                           ($transaction->status === 'held' ? 'bg-blue-100 text-blue-700' :
                                           'bg-zinc-100 text-zinc-700'))) }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $transaction->payment_method }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-4 py-16 text-center text-zinc-400">
                                    <div class="text-4xl mb-3">💰</div>
                                    <p class="font-medium">{{ __('messages.admin_malipo.no_transactions') }}</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                <div class="mt-4">{{ $transactions->links() }}</div>
                @endif
            </div>
            @endif

            {{-- Tab 2: Escrow --}}
            @if($activeTab === 'escrow')
            <div class="space-y-4">
                <div class="text-sm text-zinc-500 mb-4">
                    {{ __('messages.admin_malipo.escrow_desc') }}
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Job</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Client</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Worker</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Days on Escrow</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Hold</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse($escrows as $escrow)
                            @php $daysOnEscrow = (int) $escrow->created_at->diffInDays(now()); @endphp
                            <tr class="{{ $daysOnEscrow >= 14 ? 'bg-red-50 dark:bg-red-900/20' : ($daysOnEscrow >= 7 ? 'bg-amber-50 dark:bg-amber-900/20' : '') }}">
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $escrow->job?->title ?? $escrow->escrowItemLabel() ?? '—' }}</p>
                                        <p class="text-xs text-zinc-500">@if($escrow->job_id)#{{ $escrow->job_id }}@else—@endif</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-white">{{ $escrow->employer?->name ?? 'Unknown' }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-white">
                                    {{ $escrow->worker?->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-zinc-900 dark:text-white">
                                    TZS {{ number_format($escrow->amount) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $daysOnEscrow }} {{ __('messages.admin_malipo.days') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($escrow->job?->hold_status === 'active')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-lg">
                                        🟡 Held
                                    </span>
                                    @else
                                    <span class="text-zinc-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="releaseEscrow({{ $escrow->id }})"
                                                wire:confirm="{{ __('messages.admin_malipo.confirm_release') }}"
                                                class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition">
                                            💸 {{ __('messages.admin_malipo.release') }}
                                        </button>
                                        <button wire:click="refundEscrow({{ $escrow->id }})"
                                                wire:confirm="{{ __('messages.admin_malipo.confirm_refund') }}"
                                                class="px-2 py-1 text-xs bg-amber-600 hover:bg-amber-700 text-white rounded transition">
                                            ↩️ {{ __('messages.admin_malipo.refund') }}
                                        </button>
                                        <a href="{{ $escrow->job ? route('admin.kazi.detail', $escrow->job->id) : '#' }}" 
                                           class="px-2 py-1 text-xs bg-zinc-600 hover:bg-zinc-700 text-white rounded transition">
                                            👁️ {{ __('messages.admin_malipo.view') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-16 text-center text-zinc-400">
                                    <div class="text-4xl mb-3">🏦</div>
                                    <p class="font-medium">{{ __('messages.admin_malipo.no_escrows') }}</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($escrows->hasPages())
                <div class="mt-4">{{ $escrows->links() }}</div>
                @endif
            </div>
            @endif

            {{-- Tab 3: Withdrawals --}}
            @if($activeTab === 'withdrawals')
            <div class="space-y-4">
                <div class="text-sm text-zinc-500 mb-4">
                    {{ __('messages.admin_malipo.withdrawals_desc') }}
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Worker</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Snippe Ref</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse($withdrawals as $withdrawal)
                            <tr class="{{ $withdrawal->status === 'failed' ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $withdrawal->user->avatar ? asset('storage/'.$withdrawal->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($withdrawal->user->name).'&background=0d9488&color=fff&size=24' }}"
                                             alt="{{ $withdrawal->user->name }}"
                                             class="w-6 h-6 rounded-full object-cover">
                                        <div>
                                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $withdrawal->user->name }}</p>
                                            <p class="text-xs text-zinc-500">{{ $withdrawal->user->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-zinc-900 dark:text-white">
                                    TZS {{ number_format($withdrawal->amount) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $withdrawal->payment_method }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-bold rounded-lg
                                        {{ $withdrawal->status === 'completed' ? 'bg-green-100 text-green-700' :
                                           ($withdrawal->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                           ($withdrawal->status === 'processing' ? 'bg-blue-100 text-blue-700' :
                                           ($withdrawal->status === 'failed' ? 'bg-red-100 text-red-700' :
                                           'bg-zinc-100 text-zinc-700'))) }}">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $withdrawal->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3 text-sm font-mono text-zinc-600 dark:text-zinc-400">
                                    {{ $withdrawal->reference }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if($withdrawal->status === 'failed')
                                        <button wire:click="retryWithdrawal({{ $withdrawal->id }})"
                                                class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                                            🔄 {{ __('messages.admin_malipo.retry') }}
                                        </button>
                                        <button wire:click="cancelWithdrawal({{ $withdrawal->id }})"
                                                wire:confirm="{{ __('messages.admin_malipo.confirm_cancel') }}"
                                                class="px-2 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded transition">
                                            ❌ {{ __('messages.admin_malipo.cancel') }}
                                        </button>
                                        @endif
                                        @if($withdrawal->status === 'pending')
                                        <button wire:click="markWithdrawalPaid({{ $withdrawal->id }})"
                                                wire:confirm="{{ __('messages.admin_malipo.confirm_mark_paid') }}"
                                                class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition">
                                            ✅ {{ __('messages.admin_malipo.mark_paid') }}
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-16 text-center text-zinc-400">
                                    <div class="text-4xl mb-3">💸</div>
                                    <p class="font-medium">{{ __('messages.admin_malipo.no_withdrawals') }}</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($withdrawals->hasPages())
                <div class="mt-4">{{ $withdrawals->links() }}</div>
                @endif
            </div>
            @endif

            {{-- Tab 4: Subscriptions --}}
            @if($activeTab === 'subscriptions')
            <div class="space-y-4">
                <div class="text-sm text-zinc-500 mb-4">
                    {{ __('messages.admin_malipo.subscriptions_desc') }}
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Plan</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse($subscriptions as $subscription)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $subscription->user->avatar ? asset('storage/'.$subscription->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($subscription->user->name).'&background=0d9488&color=fff&size=24' }}"
                                             alt="{{ $subscription->user->name }}"
                                             class="w-6 h-6 rounded-full object-cover">
                                        <div>
                                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $subscription->user->name }}</p>
                                            <p class="text-xs text-zinc-500">{{ $subscription->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-bold rounded-lg
                                        {{ $subscription->subscriptionPlan?->slug === 'bora' ? 'bg-amber-100 text-amber-700' :
                                           ($subscription->subscriptionPlan?->slug === 'kawaida' ? 'bg-sky-100 text-sky-700' :
                                           'bg-zinc-100 text-zinc-700') }}">
                                        {{ $subscription->subscriptionPlan?->name ?? $subscription->planDisplayName() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-zinc-900 dark:text-white">
                                    TZS {{ number_format((float) $subscription->amount_paid) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $subscription->payment_method }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $subscription->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-bold rounded-lg
                                        {{ $subscription->status === 'active' ? 'bg-green-100 text-green-700' :
                                           ($subscription->status === 'expired' ? 'bg-red-100 text-red-700' :
                                           'bg-amber-100 text-amber-700') }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center text-zinc-400">
                                    <div class="text-4xl mb-3">⭐</div>
                                    <p class="font-medium">{{ __('messages.admin_malipo.no_subscriptions') }}</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Monthly Totals --}}
                <div class="mt-6 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                    <h4 class="font-medium text-zinc-900 dark:text-white mb-3">{{ __('messages.admin_malipo.monthly_totals') }}</h4>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-zinc-500">Msingi</p>
                            <p class="font-bold text-zinc-900 dark:text-white">
                                TZS {{ number_format($this->subscriptionMonthlyTotalByPlan('msingi')) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-zinc-500">Kawaida</p>
                            <p class="font-bold text-zinc-900 dark:text-white">
                                TZS {{ number_format($this->subscriptionMonthlyTotalByPlan('kawaida')) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-zinc-500">Bora</p>
                            <p class="font-bold text-zinc-900 dark:text-white">
                                TZS {{ number_format($this->subscriptionMonthlyTotalByPlan('bora')) }}
                            </p>
                        </div>
                    </div>
                </div>

                @if($subscriptions->hasPages())
                <div class="mt-4">{{ $subscriptions->links() }}</div>
                @endif
            </div>
            @endif

            {{-- Tab 5: Settings --}}
            @if($activeTab === 'settings')
            <div class="space-y-6">
                <div class="text-sm text-zinc-500 mb-4">
                    {{ __('messages.admin_malipo.settings_desc') }}
                </div>

                {{-- Payment Settings --}}
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_malipo.payment_settings') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_malipo.commission_rate') }}</label>
                            <input wire:model.live="commissionRate" 
                                   type="number" 
                                   step="0.1"
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_malipo.min_withdrawal') }}</label>
                            <input wire:model.live="minWithdrawal" 
                                   type="number" 
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_malipo.max_withdrawal_daily') }}</label>
                            <input wire:model.live="maxWithdrawalDaily" 
                                   type="number" 
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_malipo.min_deposit') }}</label>
                            <input wire:model.live="minDeposit" 
                                   type="number" 
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_malipo.auto_release_days') }}</label>
                            <input wire:model.live="autoReleaseDays" 
                                   type="number" 
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_malipo.payout_delay') }}</label>
                            <input wire:model.live="payoutDelayHours" 
                                   type="number" 
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        </div>
                    </div>
                </div>

                {{-- Subscription Pricing --}}
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_malipo.subscription_pricing') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Msingi (TZS)</label>
                            <input wire:model.live="subscriptionPrices.msingi" 
                                   type="number" 
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Kawaida (TZS)</label>
                            <input wire:model.live="subscriptionPrices.kawaida" 
                                   type="number" 
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Bora (TZS)</label>
                            <input wire:model.live="subscriptionPrices.bora" 
                                   type="number" 
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" wire:click="saveSettings" wire:loading.attr="disabled"
                            class="px-6 py-2 bg-winga-600 hover:bg-winga-700 text-white rounded-lg font-medium transition disabled:opacity-50">
                        💾 {{ __('messages.admin_malipo.save_settings') }}
                    </button>
                    <button type="button" wire:click="testSnippeConnection" wire:loading.attr="disabled"
                            class="px-6 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg font-medium transition disabled:opacity-50">
                        🔧 {{ __('messages.admin_malipo.test_snippe') }}
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
