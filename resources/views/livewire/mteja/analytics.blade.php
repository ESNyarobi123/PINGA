<div wire:init="loadData">
    {{-- Page Header --}}
    <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div class="relative z-10">
            <h1 class="text-2xl font-bold mb-2 text-black inline-flex items-center gap-2">
                <x-fluent-icon name="chart-multiple-24" :size="28" />
                Analytics & Takwimu
            </h1>
            <p class="text-zinc-800">Angalia takwimu na uchambuzi wa kazi zako</p>
        </div>
    </div>

    {{-- Period Filter --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm mb-6 p-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Kipindi:</span>
            <button wire:click="$set('period', '7')" class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap {{ $period === '7' ? 'bg-winga-600 text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                Siku 7
            </button>
            <button wire:click="$set('period', '30')" class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap {{ $period === '30' ? 'bg-winga-600 text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                Siku 30
            </button>
            <button wire:click="$set('period', '90')" class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap {{ $period === '90' ? 'bg-winga-600 text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                Siku 90
            </button>
            <button wire:click="$set('period', 'all')" class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap {{ $period === 'all' ? 'bg-winga-600 text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                Muda Wote
            </button>
        </div>
    </div>

    @if($ready)
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Jobs --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['total_jobs'] ?? 0 }}</p>
                    <p class="text-sm text-zinc-500">Jumla ya Kazi</p>
                </div>
            </div>
        </div>

        {{-- Active Jobs --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['active_jobs'] ?? 0 }}</p>
                    <p class="text-sm text-zinc-500">Kazi Wazi</p>
                </div>
            </div>
        </div>

        {{-- Completed Jobs --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['completed_jobs'] ?? 0 }}</p>
                    <p class="text-sm text-zinc-500">Zimekamilika</p>
                </div>
            </div>
        </div>

        {{-- Completion Rate --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['completion_rate'] ?? 0 }}%</p>
                    <p class="text-sm text-zinc-500">Kiwango cha Kukamilika</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Financial Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl p-5 text-white shadow-lg">
            <p class="text-emerald-100 text-sm mb-2">Jumla Iliyotumika</p>
            <p class="text-2xl font-bold mb-1">TZS {{ number_format($stats['total_spent'] ?? 0) }}</p>
            <p class="text-emerald-100 text-xs">Malipo yote yaliyofanywa</p>
        </div>

        <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl p-5 text-white shadow-lg">
            <p class="text-purple-100 text-sm mb-2">Salio la Wallet</p>
            <p class="text-2xl font-bold mb-1">TZS {{ number_format($stats['wallet_balance'] ?? 0) }}</p>
            <p class="text-purple-100 text-xs">Pesa zilizobaki</p>
        </div>

        <div class="bg-gradient-to-br from-amber-600 to-orange-600 rounded-xl p-5 text-white shadow-lg">
            <p class="text-amber-100 text-sm mb-2">Wastani kwa Kazi</p>
            <p class="text-2xl font-bold mb-1">TZS {{ number_format($stats['avg_per_job'] ?? 0) }}</p>
            <p class="text-amber-100 text-xs">Kwa kazi iliyokamilika</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Applications Trend --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4 inline-flex items-center gap-2">
                <x-fluent-icon name="data-line-24" :size="22" />
                Mwenendo wa Maombi
            </h3>
            <div class="space-y-2">
                @foreach($applicationsTrend as $trend)
                <div class="flex items-center gap-3">
                    <span class="text-sm text-zinc-500 w-16">{{ $trend['date'] }}</span>
                    <div class="flex-1 bg-zinc-100 dark:bg-zinc-800 rounded-full h-8 relative overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-full rounded-full transition-all" style="width: {{ $trend['count'] > 0 ? min(($trend['count'] / max(array_column($applicationsTrend, 'count'))) * 100, 100) : 0 }}%"></div>
                        <span class="absolute inset-0 flex items-center justify-center text-sm font-medium text-zinc-900 dark:text-white">{{ $trend['count'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Job Status Distribution --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4 inline-flex items-center gap-2">
                <x-fluent-icon name="data-pie-24" :size="22" />
                Hali ya Kazi
            </h3>
            <div class="space-y-3">
                @foreach($jobStatusData as $status)
                @if($status['count'] > 0)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full
                            {{ $status['status'] === 'open' ? 'bg-emerald-500' : '' }}
                            {{ $status['status'] === 'in_progress' ? 'bg-amber-500' : '' }}
                            {{ $status['status'] === 'completed' ? 'bg-blue-500' : '' }}
                            {{ $status['status'] === 'cancelled' ? 'bg-red-500' : '' }}
                            {{ $status['status'] === 'disputed' ? 'bg-purple-500' : '' }}">
                        </div>
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300 capitalize">{{ ucfirst($status['status']) }}</span>
                    </div>
                    <span class="text-lg font-bold text-zinc-900 dark:text-white">{{ $status['count'] }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Budget Distribution --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
        <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4 inline-flex items-center gap-2">
            <x-fluent-icon name="coin-multiple-24" :size="22" />
            Usambazaji wa Bajeti
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($budgetData as $budget)
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white mb-1">{{ $budget['count'] }}</p>
                <p class="text-sm text-zinc-500">{{ $budget['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    @else
    {{-- Loading State --}}
    <div class="flex items-center justify-center py-20">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-winga-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-zinc-500 dark:text-zinc-400">Inapakia takwimu...</p>
        </div>
    </div>
    @endif
</div>
