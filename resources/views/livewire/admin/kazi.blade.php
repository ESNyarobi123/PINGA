<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_jobs.title') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_jobs.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($pendingCount > 0)
            <a href="{{ route('admin.kazi.pending') }}" 
               class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                📋 {{ $pendingCount }} {{ __('messages.admin_jobs.pending') }}
            </a>
            @endif
            <button wire:click="executeBulkAction" 
                    wire:confirm="{{ __('messages.admin_jobs.confirm_bulk') }}"
                    class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition">
                {{ __('messages.admin_jobs.execute') }} {{ ucfirst($bulkAction) }}
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="{{ __('messages.admin_jobs.search_placeholder') }}"
                   class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">

            <select wire:model.live="filterApproval" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_jobs.approval_status') }}</option>
                <option value="pending">🟠 {{ __('messages.admin_jobs.pending') }}</option>
                <option value="approved">✅ {{ __('messages.admin_jobs.approved') }}</option>
                <option value="rejected">❌ {{ __('messages.admin_jobs.rejected') }}</option>
            </select>

            <select wire:model.live="filterStatus" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_jobs.job_status') }}</option>
                <option value="draft">{{ __('messages.admin_jobs.draft') }}</option>
                <option value="open">{{ __('messages.admin_jobs.open') }}</option>
                <option value="in_progress">{{ __('messages.admin_jobs.in_progress') }}</option>
                <option value="completed">{{ __('messages.admin_jobs.completed') }}</option>
                <option value="cancelled">{{ __('messages.admin_jobs.cancelled') }}</option>
                <option value="disputed">{{ __('messages.admin_jobs.disputed') }}</option>
            </select>

            <select wire:model.live="filterHold" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_jobs.hold_status') }}</option>
                <option value="active">🟡 {{ __('messages.admin_jobs.active_hold') }}</option>
                <option value="none">— {{ __('messages.admin_jobs.no_hold') }}</option>
            </select>

            <select wire:model.live="filterDispute" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_jobs.dispute_status') }}</option>
                <option value="yes">⚠️ {{ __('messages.admin_jobs.has_dispute') }}</option>
                <option value="no">— {{ __('messages.admin_jobs.no_dispute') }}</option>
            </select>

            <select wire:model.live="filterCategory" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_jobs.all_categories') }}</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            <select wire:model.live="filterLocation" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_jobs.all_regions') }}</option>
                @foreach($regions as $region)
                <option value="{{ $region }}">{{ $region }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <input wire:model.live="dateFrom" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <input wire:model.live="dateTo" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
            </div>

            <div class="flex gap-2">
                <input wire:model.live="budgetMin" type="number" placeholder="{{ __('messages.admin_jobs.min_budget') }}" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <input wire:model.live="budgetMax" type="number" placeholder="{{ __('messages.admin_jobs.max_budget') }}" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
            </div>

            <div class="flex items-center gap-2">
                <select wire:model.live="bulkAction" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                    <option value="">{{ __('messages.admin_jobs.bulk_actions') }}</option>
                    <option value="approve">✅ {{ __('messages.admin_jobs.approve') }}</option>
                    <option value="reject">❌ {{ __('messages.admin_jobs.reject') }}</option>
                    <option value="delete">🗑️ {{ __('messages.admin_jobs.delete') }}</option>
                    <option value="export">📤 {{ __('messages.admin_jobs.export_csv') }}</option>
                </select>
                
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model.live="selectAll" class="rounded">
                    <span>{{ __('messages.admin_jobs.select_all') }}</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Jobs Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('title')">
                            {{ __('messages.admin_jobs.job_details') }}
                            @if($sortField === 'title')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_jobs.client') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_jobs.hired_worker') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_jobs.budget') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_jobs.escrow') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_jobs.hold_status') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_jobs.approval') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            {{ __('messages.admin_jobs.posted') }}
                            @if($sortField === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_jobs.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($jobs as $job)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" 
                                   wire:model.live="selectedJobs" 
                                   value="{{ $job->id }}"
                                   class="rounded">
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white text-sm">{{ $job->getLocalizedTitle() }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs rounded">
                                        {{ $job->category?->name ?? 'Uncategorized' }}
                                    </span>
                                    @if($job->urgency)
                                    <span class="px-2 py-0.5 text-xs rounded
                                        {{ $job->urgency === 'urgent' ? 'bg-red-100 text-red-700' :
                                           ($job->urgency === 'high' ? 'bg-orange-100 text-orange-700' :
                                           'bg-amber-100 text-amber-700') }}">
                                        {{ ucfirst($job->urgency) }}
                                    </span>
                                    @endif
                                </div>
                                <p class="text-xs text-zinc-500 mt-1">📍 {{ $job->location }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $job->employer?->avatar ? asset('storage/'.$job->employer->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($job->employer?->name ?? 'U').'&background=0d9488&color=fff&size=24' }}"
                                     alt="{{ $job->employer?->name ?? 'Unknown' }}"
                                     class="w-6 h-6 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $job->employer?->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-zinc-500">{{ $job->employer?->created_at?->format('M Y') ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($job->hiredWorker)
                            <div class="flex items-center gap-2">
                                <img src="{{ $job->hiredWorker->avatar ? asset('storage/'.$job->hiredWorker->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($job->hiredWorker->name).'&background=0d9488&color=fff&size=24' }}"
                                     alt="{{ $job->hiredWorker->name }}"
                                     class="w-6 h-6 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $job->hiredWorker->name }}</p>
                                    <p class="text-xs text-zinc-500">⭐ {{ $job->hiredWorker->rating ?? '—' }}</p>
                                </div>
                            </div>
                            @else
                            <span class="text-zinc-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm">
                                <p class="font-medium text-zinc-900 dark:text-white">
                                    TZS {{ number_format($job->budget_min) }}
                                    @if($job->budget_max > $job->budget_min)
                                    — {{ number_format($job->budget_max) }}
                                    @endif
                                </p>
                                <p class="text-xs text-zinc-500">{{ ucfirst($job->budget_type) }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @php $escrowAmount = $this->getEscrowAmount($job); @endphp
                            @if($escrowAmount > 0)
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                TZS {{ number_format($escrowAmount) }}
                            </p>
                            @else
                            <span class="text-zinc-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($job->code_hold_until && $job->code_hold_until->isFuture())
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-lg">
                                🟡 Held
                            </span>
                            @else
                            <span class="text-zinc-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-bold rounded-lg
                                {{ $job->is_approved ? 'bg-green-100 text-green-700' :
                                   'bg-amber-100 text-amber-700' }}">
                                {{ $job->is_approved ? __('messages.admin_jobs.approved') : __('messages.admin_jobs.pending') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $job->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.kazi.detail', $job->id) }}" 
                                   class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                                    {{ __('messages.admin_jobs.view') }}
                                </a>
                                @if(!$job->is_approved)
                                <button wire:click="approveJob({{ $job->id }})"
                                        class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition">
                                    ✓
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-16 text-center text-zinc-400">
                            <div class="text-4xl mb-3">📋</div>
                            <p class="font-medium">{{ __('messages.admin_jobs.no_jobs') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobs->hasPages())
        <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
            {{ $jobs->links() }}
        </div>
        @endif
    </div>
</div>
