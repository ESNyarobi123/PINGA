<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_withdrawals.title') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_withdrawals.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($failedRequests > 0)
            <div class="px-4 py-2 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-xl">
                <span class="text-red-600 dark:text-red-400 font-bold text-sm">🚨 {{ $failedRequests }} {{ __('messages.admin_withdrawals.failed') }}</span>
            </div>
            @endif
            @if($processingRequests > 0)
            <div class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 border border-blue-300 dark:border-blue-700 rounded-xl">
                <span class="text-blue-600 dark:text-blue-400 font-bold text-sm">⚡ {{ $processingRequests }} {{ __('messages.admin_withdrawals.processing') }}</span>
            </div>
            @endif
            @if($failedPayouts > 0)
            <div class="px-4 py-2 bg-amber-100 dark:bg-amber-900/30 border border-amber-300 dark:border-amber-700 rounded-xl">
                <span class="text-amber-600 dark:text-amber-400 font-bold text-sm">💼 {{ $failedPayouts }} {{ __('messages.admin_withdrawals.failed_payouts') }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_withdrawals.total_requests') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($totalRequests) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_withdrawals.all_requests') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_withdrawals.pending') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($pendingRequests) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_withdrawals.awaiting_approval') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_withdrawals.total_amount') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($totalAmount) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_withdrawals.all_amounts') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_withdrawals.pending_amount') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($pendingAmount) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_withdrawals.awaiting_processing') }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-6">
        <div class="flex flex-wrap gap-4">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="{{ __('messages.admin_withdrawals.search_placeholder') }}"
                   class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">

            <select wire:model.live="filterStatus" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_withdrawals.all_status') }}</option>
                <option value="pending">{{ __('messages.admin_withdrawals.pending') }}</option>
                <option value="approved">{{ __('messages.admin_withdrawals.approved') }}</option>
                <option value="rejected">{{ __('messages.admin_withdrawals.rejected') }}</option>
                <option value="completed">{{ __('messages.admin_withdrawals.completed') }}</option>
            </select>

            <select wire:model.live="filterPayoutStatus" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_withdrawals.all_payout_status') }}</option>
                <option value="pending">{{ __('messages.admin_withdrawals.pending') }}</option>
                <option value="processing">{{ __('messages.admin_withdrawals.processing') }}</option>
                <option value="completed">{{ __('messages.admin_withdrawals.completed') }}</option>
                <option value="failed">{{ __('messages.admin_withdrawals.failed') }}</option>
            </select>

            <select wire:model.live="filterNetwork" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_withdrawals.all_networks') }}</option>
                <option value="TIGO">Tigo</option>
                <option value="VODACOM">Vodacom</option>
                <option value="AIRTEL">Airtel</option>
                <option value="HALOTEL">Halotel</option>
            </select>

            <select wire:model.live="sortBy" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="created_at">{{ __('messages.admin_withdrawals.sort_created') }}</option>
                <option value="amount">{{ __('messages.admin_withdrawals.sort_amount') }}</option>
                <option value="processed_at">{{ __('messages.admin_withdrawals.sort_processed') }}</option>
                <option value="status">{{ __('messages.admin_withdrawals.sort_status') }}</option>
            </select>

            <div class="flex gap-2">
                <input wire:model.live="dateFrom" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <input wire:model.live="dateTo" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
            </div>

            <button wire:click="exportWithdrawals"
                    class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition">
                📤 {{ __('messages.admin_withdrawals.export_csv') }}
            </button>
        </div>

        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-4">
                <select wire:model.live="bulkAction" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                    <option value="">{{ __('messages.admin_withdrawals.bulk_actions') }}</option>
                    <option value="approve">✅ {{ __('messages.admin_withdrawals.approve') }}</option>
                    <option value="reject">❌ {{ __('messages.admin_withdrawals.reject') }}</option>
                    <option value="retry">🔄 {{ __('messages.admin_withdrawals.retry') }}</option>
                </select>
                
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model.live="selectAll" class="rounded">
                    <span>{{ __('messages.admin_withdrawals.select_all') }} ({{ $requests->total() }})</span>
                </label>
            </div>

            <div class="text-sm text-zinc-500">
                {{ __('messages.admin_withdrawals.showing') }} {{ $requests->firstItem() }}-{{ $requests->lastItem() }} {{ __('messages.admin_withdrawals.of') }} {{ $requests->total() }}
            </div>
        </div>
    </div>

    {{-- Withdrawal Requests Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            User
                            @if($sortBy === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('amount')">
                            Amount
                            @if($sortBy === 'amount')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Payment Details</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Processing</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($requests as $request)
                    @php 
                        $stats = $this->getWithdrawalStats($request);
                        $isPaid = $request->status === 'completed' || $request->payout_status === 'completed';
                        $isFailed = $request->payout_status === 'failed' || $request->status === 'rejected';
                        $isProcessing = $request->payout_status === 'processing';
                        $isPending = $request->status === 'pending';
                    @endphp
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition {{ $isFailed ? 'bg-red-50/30 dark:bg-red-900/5' : '' }}">
                        <td class="px-4 py-3">
                            <input type="checkbox" 
                                   wire:model.live="selectedRequests" 
                                   value="{{ $request->id }}"
                                   class="rounded">
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $request->user->avatar ? asset('storage/'.$request->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($request->user->name).'&background=0d9488&color=fff&size=40' }}"
                                     alt="{{ $request->user->name }}"
                                     class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">{{ $request->user->name }}</p>
                                    <p class="text-xs text-zinc-500">{{ $request->user->phone }}</p>
                                    <p class="text-xs text-zinc-400">{{ $request->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="space-y-1">
                                <p class="text-lg font-bold text-zinc-900 dark:text-white">TZS {{ number_format($request->amount) }}</p>
                                <div class="text-xs text-zinc-500">
                                    <span>Wallet: TZS {{ number_format($stats['user_wallet_balance']) }}</span>
                                    <span class="text-zinc-400">• {{ $stats['user_withdrawal_count'] }} withdrawals</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 text-xs font-bold rounded-lg bg-zinc-100 text-zinc-700">
                                        {{ $request->network ?? 'Unknown' }}
                                    </span>
                                    <span class="text-sm font-mono text-zinc-600 dark:text-zinc-400">{{ $request->account_number }}</span>
                                </div>
                                @if($request->payout_reference)
                                <p class="text-xs text-zinc-500 font-mono">Ref: {{ $request->payout_reference }}</p>
                                @endif
                                @if($stats['retry_count'] > 0)
                                <p class="text-xs text-amber-600">Retries: {{ $stats['retry_count'] }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="space-y-1">
                                <span class="px-2 py-1 text-xs font-bold rounded-lg
                                    {{ $request->status === 'completed' ? 'bg-green-100 text-green-700' :
                                       ($request->status === 'approved' ? 'bg-blue-100 text-blue-700' :
                                       ($request->status === 'rejected' ? 'bg-red-100 text-red-700' :
                                       'bg-amber-100 text-amber-700')) }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                                @if($request->admin_note)
                                <p class="text-xs text-zinc-500">{{ Str::limit($request->admin_note, 30) }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="space-y-1">
                                <span class="px-2 py-1 text-xs font-bold rounded-lg
                                    {{ $isPaid ? 'bg-green-100 text-green-700' :
                                       ($isFailed ? 'bg-red-100 text-red-700' :
                                       ($isProcessing ? 'bg-blue-100 text-blue-700' :
                                       'bg-amber-100 text-amber-700')) }}">
                                    {{ ucfirst($request->payout_status) }}
                                </span>
                                @if($request->processed_at)
                                <p class="text-xs text-zinc-500">{{ $request->processed_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                @if($isFailed)
                                <button wire:click="retryWithdrawal({{ $request->id }})"
                                        wire:loading.attr="disabled"
                                        class="px-2 py-1 text-xs bg-amber-600 hover:bg-amber-700 text-white rounded transition">
                                    🔄 {{ __('messages.admin_withdrawals.retry') }}
                                </button>
                                @elseif($isPending)
                                <button wire:click="approveWithdrawal({{ $request->id }})"
                                        wire:confirm="{{ __('messages.admin_withdrawals.confirm_approve') }}"
                                        class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition">
                                    ✅ {{ __('messages.admin_withdrawals.approve') }}
                                </button>
                                <button wire:click="$set('selectedRequestId', {{ $request->id }})"
                                        class="px-2 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded transition">
                                    ❌ {{ __('messages.admin_withdrawals.reject') }}
                                </button>
                                @elseif($isProcessing)
                                <button wire:click="markAsCompleted({{ $request->id }})"
                                        wire:confirm="{{ __('messages.admin_withdrawals.confirm_complete') }}"
                                        class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                                    ✅ {{ __('messages.admin_withdrawals.complete') }}
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center text-zinc-400">
                            <div class="text-4xl mb-3">💸</div>
                            <p class="font-medium">{{ __('messages.admin_withdrawals.no_requests') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
        <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

    {{-- Rejection Modal --}}
    @if($selectedRequestId)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_withdrawals.reject_title') }}</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_withdrawals.reject_desc') }}</p>
            </div>
            <div class="p-6">
                <form wire:submit="rejectWithdrawal({{ $selectedRequestId }}, '{{ $rejectionReason }}')">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_withdrawals.rejection_reason') }}</label>
                            <textarea wire:model.live="rejectionReason"
                                      rows="3"
                                      placeholder="{{ __('messages.admin_withdrawals.rejection_placeholder') }}"
                                      class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                      required></textarea>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="$set('selectedRequestId', null)"
                                    class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 rounded-lg text-sm font-medium transition">
                                {{ __('messages.admin_withdrawals.cancel') }}
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                                {{ __('messages.admin_withdrawals.reject_request') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
