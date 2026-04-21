<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('messages.admin_subs.title') }}</flux:heading>
            <flux:subheading>{{ __('messages.admin_subs.subtitle') }}</flux:subheading>
        </div>
        <flux:button wire:click="$set('showManualForm', true)" icon="plus" variant="filled" size="sm">
            {{ __('messages.admin_subs.add_manually') }}
        </flux:button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['total_active'] }}</p>
            <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider mt-1">{{ __('messages.admin_subs.active') }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <p class="text-2xl font-bold text-zinc-500 dark:text-zinc-400">{{ $stats['total_expired'] }}</p>
            <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider mt-1">{{ __('messages.admin_subs.expired') }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <p class="text-2xl font-bold text-winga-600 dark:text-winga-400">TZS {{ number_format($stats['revenue_month']/1000, 1) }}K</p>
            <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider mt-1">{{ __('messages.admin_subs.revenue_month') }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
            <p class="text-2xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($stats['revenue_total']/1000, 1) }}K</p>
            <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider mt-1">{{ __('messages.admin_subs.revenue_total') }}</p>
        </div>
    </div>

    {{-- Analytics Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Revenue by Plan --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <h4 class="font-bold text-zinc-900 dark:text-white mb-4">💰 {{ __('messages.admin_subs.revenue_by_plan') }}</h4>
            <div class="space-y-3">
                @foreach($chartData['revenue_by_plan'] as $plan => $amount)
                <div class="flex items-center gap-3">
                    <span class="w-20 text-sm font-medium text-zinc-600 dark:text-zinc-400 capitalize">{{ $plan }}</span>
                    <div class="flex-1 h-8 bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden">
                        @php
                            $maxRevenue = max($chartData['revenue_by_plan']);
                            $percentage = $maxRevenue > 0 ? ($amount / $maxRevenue) * 100 : 0;
                            $color = $plan === 'bora' ? 'bg-gradient-to-r from-amber-500 to-orange-500' : ($plan === 'kawaida' ? 'bg-gradient-to-r from-sky-500 to-cyan-500' : 'bg-gradient-to-r from-zinc-400 to-zinc-500');
                        @endphp
                        <div class="h-full {{ $color }} flex items-center justify-end px-2" style="width: {{ $percentage }}%">
                            @if($percentage > 20)
                            <span class="text-xs font-bold text-white">TZS {{ number_format($amount/1000, 1) }}K</span>
                            @endif
                        </div>
                    </div>
                    @if($percentage <= 20)
                    <span class="text-xs font-bold text-zinc-500">TZS {{ number_format($amount/1000, 1) }}K</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Subscriptions by Plan --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <h4 class="font-bold text-zinc-900 dark:text-white mb-4">📊 {{ __('messages.admin_subs.subs_count') }}</h4>
            <div class="flex items-end justify-around h-40 gap-4">
                @foreach($chartData['subs_by_plan'] as $plan => $count)
                @php
                    $maxCount = max($chartData['subs_by_plan']);
                    $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                    $color = $plan === 'bora' ? 'bg-amber-500' : ($plan === 'kawaida' ? 'bg-sky-500' : 'bg-zinc-400');
                @endphp
                <div class="flex flex-col items-center gap-2 flex-1">
                    <span class="text-lg font-bold text-zinc-700 dark:text-zinc-300">{{ $count }}</span>
                    <div class="w-full {{ $color }} rounded-t-lg transition-all" style="height: {{ max($height, 4) }}px"></div>
                    <span class="text-xs font-medium text-zinc-500 capitalize">{{ $plan }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Monthly Revenue Trend --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <h4 class="font-bold text-zinc-900 dark:text-white mb-4">📈 {{ __('messages.admin_subs.monthly_revenue') }}</h4>
            <div class="h-40 flex items-end gap-2">
                @php $maxMonthly = max(array_column($chartData['monthly_revenue'], 'revenue')) ?: 1; @endphp
                @foreach($chartData['monthly_revenue'] as $monthData)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full bg-winga-500 hover:bg-winga-600 rounded-t transition-colors relative group"
                         style="height: {{ ($monthData['revenue'] / $maxMonthly) * 100 }}%; min-height: 4px;">
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-1 bg-zinc-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                            TZS {{ number_format($monthData['revenue']) }}
                        </div>
                    </div>
                    <span class="text-[10px] text-zinc-500 rotate-45 origin-left">{{ $monthData['month'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Daily New Subscriptions --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 shadow-sm">
            <h4 class="font-bold text-zinc-900 dark:text-white mb-4">📅 {{ __('messages.admin_subs.new_subs_30days') }}</h4>
            <div class="h-40 flex items-end gap-0.5">
                @php $maxDaily = max(array_column($chartData['daily_subs'], 'count')) ?: 1; @endphp
                @foreach($chartData['daily_subs'] as $dayData)
                <div class="flex-1 bg-emerald-500 hover:bg-emerald-600 rounded-sm transition-colors relative group"
                     style="height: {{ ($dayData['count'] / $maxDaily) * 100 }}%; min-height: 2px;">
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-1.5 py-0.5 bg-zinc-900 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                        {{ $dayData['date'] }}: {{ $dayData['count'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-5 flex flex-wrap gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('messages.admin_subs.search_placeholder') }}"
            class="flex-1 min-w-48 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-winga-500 text-zinc-900 dark:text-white" />

        <select wire:model.live="filterStatus"
            class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm text-zinc-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-winga-500">
            <option value="all">{{ __('messages.admin_subs.all_status') }}</option>
            <option value="active">{{ __('messages.admin_subs.active') }}</option>
            <option value="expired">{{ __('messages.admin_subs.expired') }}</option>
            <option value="cancelled">{{ __('messages.admin_subs.cancelled') }}</option>
        </select>

        <select wire:model.live="filterPlan"
            class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm text-zinc-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-winga-500">
            <option value="">{{ __('messages.admin_subs.all_plans') }}</option>
            @foreach($plans as $plan)
            <option value="{{ $plan->slug }}">{{ $plan->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-900/50 border-b border-zinc-200 dark:border-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_subs.worker') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_subs.plan') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_subs.amount') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_subs.starts') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_subs.expires') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_subs.status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_subs.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($subscriptions as $sub)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-zinc-900 dark:text-white">{{ $sub->user?->name ?? 'N/A' }}</div>
                        <div class="text-xs text-zinc-500">{{ $sub->user?->email }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-md text-xs font-bold
                            {{ $sub->plan_slug === 'bora' ? 'bg-winga-100 dark:bg-winga-900/30 text-winga-700 dark:text-winga-400' :
                               ($sub->plan_slug === 'kawaida' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' :
                                'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400') }}">
                            {{ $sub->subscriptionPlan?->name ?? ucfirst($sub->plan_slug ?? $sub->plan) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300 font-medium">TZS {{ number_format($sub->amount_paid) }}</td>
                    <td class="px-4 py-3 text-zinc-500 text-xs">{{ $sub->starts_at?->format('d M Y') ?? '—' }}</td>
                    <td class="px-4 py-3 text-zinc-500 text-xs">{{ $sub->expires_at?->format('d M Y') ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if($sub->isActive())
                            <flux:badge color="green" size="sm">{{ __('messages.admin_subs.active') }}</flux:badge>
                        @elseif($sub->status === 'expired')
                            <flux:badge color="zinc" size="sm">{{ __('messages.admin_subs.expired') }}</flux:badge>
                        @elseif($sub->payment_status === 'pending')
                            <flux:badge color="amber" size="sm">{{ __('messages.admin_subs.pending') }}</flux:badge>
                        @else
                            <flux:badge color="red" size="sm">{{ __('messages.admin_subs.suspended') }}</flux:badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            @if(!$sub->isActive())
                            <button wire:click="activate({{ $sub->id }})"
                                wire:confirm="{{ __('messages.admin_subs.confirm_activate') }}"
                                class="px-2.5 py-1 text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-200 dark:hover:bg-green-800/40 transition">
                                {{ __('messages.admin_subs.activate') }}
                            </button>
                            @else
                            <button wire:click="deactivate({{ $sub->id }})"
                                wire:confirm="{{ __('messages.admin_subs.confirm_deactivate') }}"
                                class="px-2.5 py-1 text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-800/40 transition">
                                {{ __('messages.admin_subs.deactivate') }}
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-16 text-center text-zinc-400 dark:text-zinc-600">
                        <div class="text-4xl mb-3">📋</div>
                        <p class="font-medium">{{ __('messages.admin_subs.no_subscriptions') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subscriptions->hasPages())
    <div class="mt-4">{{ $subscriptions->links() }}</div>
    @endif

    {{-- Manual activation modal --}}
    @if($showManualForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_subs.add_manually') }}</h3>
                <button wire:click="$set('showManualForm', false)" class="text-zinc-400 hover:text-zinc-600 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">User ID</label>
                    <input wire:model="manualUserId" type="number" placeholder="{{ __('messages.admin_subs.user_id_placeholder') }}"
                        class="w-full px-3 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-winga-500 text-zinc-900 dark:text-white" />
                    @error('manualUserId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('messages.admin_subs.select_plan') }}</label>
                    <select wire:model="manualPlanSlug"
                        class="w-full px-3 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-winga-500">
                        <option value="">-- {{ __('messages.admin_subs.select_plan') }} --</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->slug }}">{{ $plan->name }} — TZS {{ number_format($plan->price) }} / {{ $plan->durationLabel() }}</option>
                        @endforeach
                    </select>
                    @error('manualPlanSlug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button wire:click="$set('showManualForm', false)"
                    class="flex-1 py-2.5 bg-zinc-100 dark:bg-zinc-800 rounded-xl text-sm font-bold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition">
                    {{ __('messages.admin_subs.cancel') }}
                </button>
                <button wire:click="submitManual"
                    class="flex-1 py-2.5 bg-winga-600 hover:bg-winga-700 rounded-xl text-sm font-bold text-white transition shadow-lg shadow-winga-600/30">
                    {{ __('messages.admin_subs.activate_subscription') }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
