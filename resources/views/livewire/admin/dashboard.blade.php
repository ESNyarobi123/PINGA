<div wire:init="loadData" wire:poll.15s="loadData">
    {{-- Real-Time Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8">
        {{-- Users Stats --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['total_users'] ?? 0 }}</p>
                    <p class="text-xs text-zinc-500">{{ __('messages.admin_dash.total_users') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="text-zinc-500">👷 {{ $stats['wingas_count'] ?? 0 }}</span>
                <span class="text-zinc-500">👤 {{ $stats['wateja_count'] ?? 0 }}</span>
                <span class="text-green-600 font-bold">+{{ $stats['today_signups'] ?? 0 }}</span>
            </div>
        </div>

        {{-- Jobs Stats --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['total_jobs'] ?? 0 }}</p>
                    <p class="text-xs text-zinc-500">{{ __('messages.admin_dash.total_jobs') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="text-green-600">📋 {{ $stats['open_jobs'] ?? 0 }}</span>
                <span class="text-blue-600">⚡ {{ $stats['in_progress_jobs'] ?? 0 }}</span>
                <span class="text-zinc-500">✅ {{ $stats['completed_jobs'] ?? 0 }}</span>
            </div>
        </div>

        {{-- Revenue Today --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['revenue_today'] ?? 0) }}</p>
                    <p class="text-xs text-zinc-500">{{ __('messages.admin_dash.revenue_today') }}</p>
                </div>
            </div>
        </div>

        {{-- Escrow Balance --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['escrow_balance'] ?? 0) }}</p>
                    <p class="text-xs text-zinc-500">{{ __('messages.admin_dash.escrow_balance') }}</p>
                </div>
            </div>
        </div>

        {{-- Subscriptions --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['subscriptions_active'] ?? 0 }}</p>
                    <p class="text-xs text-zinc-500">{{ __('messages.admin_dash.subscriptions') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="text-amber-600">⭐ {{ $stats['bora_active'] ?? 0 }}</span>
                <span class="text-blue-600">🔷 {{ $stats['kawaida_active'] ?? 0 }}</span>
                <span class="text-zinc-500">🌱 {{ $stats['msingi_active'] ?? 0 }}</span>
            </div>
        </div>

        {{-- Pending Actions --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ ($stats['pending_approvals'] ?? 0) + ($stats['disputes_open'] ?? 0) + ($stats['failed_payouts'] ?? 0) }}
                    </p>
                    <p class="text-xs text-zinc-500">{{ __('messages.admin_dash.pending_actions') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="text-amber-600">📋 {{ $stats['pending_approvals'] ?? 0 }}</span>
                <span class="text-red-600">⚠️ {{ $stats['disputes_open'] ?? 0 }}</span>
                <span class="text-red-600">❌ {{ $stats['failed_payouts'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Charts Section --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Date Range Filter --}}
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_dash.stats_charts') }}</h2>
                <select wire:model.live="dateRange" class="px-4 py-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                    <option value="7">{{ __('messages.admin_dash.days_7') }}</option>
                    <option value="30">{{ __('messages.admin_dash.days_30') }}</option>
                    <option value="90">{{ __('messages.admin_dash.days_90') }}</option>
                </select>
            </div>

            {{-- Revenue Chart --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-4">💰 {{ __('messages.admin_dash.revenue_chart') }} ({{ $dateRange }})</h3>
                @if(!empty($charts['revenue']))
                <div class="h-48 flex items-end gap-2">
                    @php $maxRevenue = max(array_column($charts['revenue'], 'revenue')) ?: 1; @endphp
                    @foreach($charts['revenue'] as $data)
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-green-500 hover:bg-green-600 rounded-t transition-colors relative group"
                             style="height: {{ ($data['revenue'] / $maxRevenue) * 100 }}%; min-height: 4px;">
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-1 bg-zinc-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                TZS {{ number_format($data['revenue']) }}
                            </div>
                        </div>
                        <span class="text-[10px] text-zinc-500">{{ $data['date'] }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="h-48 flex items-center justify-center text-zinc-400">{{ __('messages.admin_dash.no_data') }}</div>
                @endif
            </div>

            {{-- Users Growth Chart --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-4">📈 {{ __('messages.admin_dash.users_growth') }}</h3>
                @if(!empty($charts['users']))
                <div class="h-48 flex items-center gap-2">
                    @php $maxUsers = max(max(array_column($charts['users'], 'wingas')), max(array_column($charts['users'], 'wateja'))) ?: 1; @endphp
                    @foreach($charts['users'] as $data)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full flex gap-1">
                            <div class="flex-1 bg-blue-500 rounded-t" style="height: {{ ($data['wingas'] / $maxUsers) * 100 }}%; min-height: 4px;"></div>
                            <div class="flex-1 bg-amber-500 rounded-t" style="height: {{ ($data['wateja'] / $maxUsers) * 100 }}%; min-height: 4px;"></div>
                        </div>
                        <span class="text-[10px] text-zinc-500">{{ $data['date'] }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-center gap-4 mt-4 text-xs">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-blue-500 rounded"></span> Wingas</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-amber-500 rounded"></span> Wateja</span>
                </div>
                @else
                <div class="h-48 flex items-center justify-center text-zinc-400">{{ __('messages.admin_dash.no_data') }}</div>
                @endif
            </div>

            {{-- Jobs Overview --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="font-bold text-zinc-900 dark:text-white mb-4">📊 {{ __('messages.admin_dash.jobs_overview') }}</h3>
                @if(!empty($charts['jobs']))
                <div class="h-48 flex items-end gap-2">
                    @php $maxJobs = max(max(array_column($charts['jobs'], 'posted')), max(array_column($charts['jobs'], 'completed'))) ?: 1; @endphp
                    @foreach($charts['jobs'] as $data)
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full flex gap-1">
                            <div class="flex-1 bg-indigo-500 rounded-t" style="height: {{ ($data['posted'] / $maxJobs) * 100 }}%; min-height: 4px;"></div>
                            <div class="flex-1 bg-green-500 rounded-t" style="height: {{ ($data['completed'] / $maxJobs) * 100 }}%; min-height: 4px;"></div>
                        </div>
                        <span class="text-[10px] text-zinc-500">{{ $data['date'] }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-center gap-4 mt-4 text-xs">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-indigo-500 rounded"></span> {{ __('messages.admin_dash.posted') }}</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-green-500 rounded"></span> {{ __('messages.admin_dash.completed') }}</span>
                </div>
                @else
                <div class="h-48 flex items-center justify-center text-zinc-400">{{ __('messages.admin_dash.no_data') }}</div>
                @endif
            </div>
        </div>

        {{-- Live Activity Feed --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 sticky top-6 max-h-[800px] overflow-hidden flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-zinc-900 dark:text-white">🔴 {{ __('messages.admin_dash.live_activity') }}</h3>
                    <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                </div>
                
                <div class="flex-1 overflow-y-auto space-y-3 pr-2">
                    @forelse($activityFeed as $activity)
                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-100 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                        <div class="flex items-start gap-3">
                            <span class="text-lg">{{ $activity['icon'] }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $activity['title'] }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $activity['description'] }}</p>
                                <p class="text-[10px] text-zinc-400 mt-1">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-zinc-400 py-8">
                        <svg class="w-12 h-12 mx-auto mb-3 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm">{{ __('messages.admin_dash.no_activity') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        {{-- Subscription Revenue --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="font-bold text-zinc-900 dark:text-white mb-4">⭐ {{ __('messages.admin_dash.subscription_revenue') }}</h3>
            @if(!empty($charts['subscriptions']))
            <div class="space-y-3">
                @foreach($charts['subscriptions'] as $data)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $data['name'] }}</span>
                    <span class="text-sm font-bold text-zinc-900 dark:text-white">TZS {{ number_format($data['value']) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="h-32 flex items-center justify-center text-zinc-400">{{ __('messages.admin_dash.no_data') }}</div>
            @endif
        </div>

        {{-- Payment Methods --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="font-bold text-zinc-900 dark:text-white mb-4">💳 {{ __('messages.admin_dash.payment_methods') }}</h3>
            @if(!empty($charts['payment_methods']))
            <div class="space-y-3">
                @foreach($charts['payment_methods'] as $data)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $data['name'] }}</span>
                    <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $data['value'] }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="h-32 flex items-center justify-center text-zinc-400">{{ __('messages.admin_dash.no_data') }}</div>
            @endif
        </div>

        {{-- Top Categories --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="font-bold text-zinc-900 dark:text-white mb-4">🏷️ {{ __('messages.admin_dash.top_categories') }}</h3>
            @if(!empty($charts['categories']))
            <div class="space-y-2">
                @foreach($charts['categories'] as $index => $data)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $index + 1 }}. {{ $data['name'] }}</span>
                    <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $data['count'] }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="h-32 flex items-center justify-center text-zinc-400">{{ __('messages.admin_dash.no_data') }}</div>
            @endif
        </div>
    </div>

    {{-- Email Export Section --}}
    <div class="mt-8">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-zinc-900 dark:to-zinc-800 rounded-xl border border-blue-200 dark:border-zinc-700 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-1">📧 Pakua Email Za Watumiaji</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Pakua orodha ya email zote za watumiaji kwa format ya CSV (Excel).</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.export-emails') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Pakua Emails Only
                    </a>
                    <a href="{{ route('admin.export-emails-details') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Pakua na Maelezo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
