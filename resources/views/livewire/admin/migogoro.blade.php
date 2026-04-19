<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_disputes.title') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_disputes.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($openCount > 0)
            <div class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                ⚠️ {{ $openCount }} {{ __('messages.admin_disputes.open_disputes') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="{{ __('messages.admin_disputes.search_placeholder') }}"
                   class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">

            <select wire:model.live="filterPriority" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_disputes.all_priorities') }}</option>
                <option value="high">🔴 {{ __('messages.admin_disputes.high') }}</option>
                <option value="medium">🟡 {{ __('messages.admin_disputes.medium') }}</option>
                <option value="low">🟢 {{ __('messages.admin_disputes.low') }}</option>
            </select>

            <select wire:model.live="filterStatus" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_disputes.all_status') }}</option>
                <option value="open">{{ __('messages.admin_disputes.open') }}</option>
                <option value="investigating">{{ __('messages.admin_disputes.investigating') }}</option>
                <option value="resolved">{{ __('messages.admin_disputes.resolved') }}</option>
                <option value="closed">{{ __('messages.admin_disputes.closed') }}</option>
            </select>

            <div class="flex gap-2">
                <input wire:model.live="dateFrom" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <input wire:model.live="dateTo" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
            </div>

            <div class="flex gap-2">
                <input wire:model.live="amountMin" type="number" placeholder="{{ __('messages.admin_disputes.min_amount') }}" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <input wire:model.live="amountMax" type="number" placeholder="{{ __('messages.admin_disputes.max_amount') }}" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
            </div>

            <button wire:click="clearFilters"
                    class="px-3 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm transition">
                {{ __('messages.admin_disputes.reset') }}
            </button>
        </div>
    </div>

    {{-- Disputes Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            {{ __('messages.admin_disputes.dispute') }}
                            @if($sortField === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_disputes.priority') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_disputes.amount_at_stake') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_disputes.days_open') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_disputes.status') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('title')">
                            {{ __('messages.admin_disputes.job_title') }}
                            @if($sortField === 'title')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_disputes.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($disputes as $dispute)
                    @php $escrowAmount = $this->getEscrowAmount($dispute); @endphp
                    @php $daysOpen = $this->getDaysOpen($dispute); @endphp
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white">#{{ $dispute->id }}</p>
                                <p class="text-xs text-zinc-500">{{ $dispute->created_at->format('d M Y, H:i') }}</p>
                                @if($dispute->auto_resolve_at && $dispute->status === 'open')
                                <p class="text-xs text-amber-600 mt-1">{{ __('messages.admin_disputes.auto_resolves') }} {{ $this->getAutoResolveAt($dispute) }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-bold rounded-lg
                                {{ $dispute->priority === 'high' ? 'bg-red-100 text-red-700' :
                                   ($dispute->priority === 'medium' ? 'bg-amber-100 text-amber-700' :
                                   'bg-green-100 text-green-700') }}">
                                {{ $dispute->priority === 'high' ? '🔴 '.__('messages.admin_disputes.high') : ($dispute->priority === 'medium' ? '🟡 '.__('messages.admin_disputes.medium') : '🟢 '.__('messages.admin_disputes.low')) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-zinc-900 dark:text-white">TZS {{ number_format($escrowAmount) }}</p>
                            <p class="text-xs text-zinc-500">{{ __('messages.admin_disputes.escrow_held') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-sm font-medium 
                                {{ $daysOpen >= 3 ? 'text-red-600' : ($daysOpen >= 1 ? 'text-amber-600' : 'text-zinc-600') }}">
                                {{ $daysOpen }} {{ __('messages.admin_disputes.days') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-bold rounded-lg
                                {{ $dispute->status === 'open' ? 'bg-red-100 text-red-700' :
                                   ($dispute->status === 'investigating' ? 'bg-amber-100 text-amber-700' :
                                   ($dispute->status === 'resolved' ? 'bg-green-100 text-green-700' :
                                   'bg-zinc-100 text-zinc-700')) }}">
                                {{ ucfirst($dispute->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white text-sm">{{ $dispute->job->title }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-zinc-500">{{ __('messages.admin_disputes.client') }}:</span>
                                    <span class="text-xs text-zinc-600 dark:text-zinc-400">{{ $dispute->job->employer?->name ?? 'Unknown' }}</span>
                                </div>
                                @if($dispute->job->hiredWorker)
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-zinc-500">{{ __('messages.admin_disputes.worker') }}:</span>
                                    <span class="text-xs text-zinc-600 dark:text-zinc-400">{{ $dispute->job->hiredWorker->name }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.migogoro.detail', $dispute->id) }}" 
                                   class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                                    {{ __('messages.admin_disputes.view_details') }}
                                </a>
                                @if($dispute->status === 'open')
                                <button wire:click="$dispatch('quickResolve', disputeId: {{ $dispute->id }})"
                                        class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition">
                                    {{ __('messages.admin_disputes.quick_resolve') }}
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center text-zinc-400">
                            <div class="text-4xl mb-3">⚖️</div>
                            <p class="font-medium">{{ __('messages.admin_disputes.no_disputes') }}</p>
                            <p class="text-sm text-zinc-500 mt-1">{{ __('messages.admin_disputes.all_smooth') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($disputes->hasPages())
        <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
            {{ $disputes->links() }}
        </div>
        @endif
    </div>

    {{-- Auto-Resolution Rules Info --}}
    <div class="mt-6 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
        <h3 class="font-medium text-zinc-900 dark:text-white mb-2">{{ __('messages.admin_disputes.auto_rules_title') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-zinc-600 dark:text-zinc-400">
                    🔴 <strong>{{ __('messages.admin_disputes.high_priority') }}:</strong> {{ __('messages.admin_disputes.high_rule') }}
                </p>
                <p class="text-zinc-600 dark:text-zinc-400 mt-1">
                    🟡 <strong>{{ __('messages.admin_disputes.medium_priority') }}:</strong> {{ __('messages.admin_disputes.medium_rule') }}
                </p>
                <p class="text-zinc-600 dark:text-zinc-400 mt-1">
                    🟢 <strong>{{ __('messages.admin_disputes.low_priority') }}:</strong> {{ __('messages.admin_disputes.low_rule') }}
                </p>
            </div>
            <div>
                <p class="text-zinc-600 dark:text-zinc-400">
                    ⏰ {{ __('messages.admin_disputes.auto_resolve_client') }}
                </p>
                <p class="text-zinc-600 dark:text-zinc-400 mt-1">
                    ⏰ {{ __('messages.admin_disputes.auto_refund_worker') }}
                </p>
            </div>
        </div>
    </div>
</div>
