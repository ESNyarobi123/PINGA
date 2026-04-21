<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.kazi') }}" wire:navigate class="p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $job->getLocalizedTitle() ?: 'Untitled Job' }}</h1>
                <p class="text-zinc-500 dark:text-zinc-400">
                    {{ __('messages.admin_job_detail.job') }} #{{ $job->id }} • {{ __('messages.admin_job_detail.submitted') }} {{ $job->created_at?->format('d M Y') ?? 'Unknown date' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @if(!$job->is_approved && $job->status !== 'cancelled')
            <button wire:click="approveJob"
                    wire:confirm="Approve this job?"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                ✅ {{ __('messages.admin_job_detail.approve') }}
            </button>
            <button wire:click="$set('showRejectionModal', true)" 
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                ❌ {{ __('messages.admin_job_detail.reject') }}
            </button>
            @endif
            @if($job->status !== 'cancelled' && $job->status !== 'completed')
            <button wire:click="cancelJob"
                    wire:confirm="{{ __('messages.admin_job_detail.confirm_cancel') }}"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                🚫 {{ __('messages.admin_job_detail.cancel_job') }}
            </button>
            @endif
            @if($job->status !== 'completed' && $job->status !== 'cancelled')
            <button wire:click="forceComplete"
                    wire:confirm="{{ __('messages.admin_job_detail.confirm_force_complete') }}"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                ⚡ {{ __('messages.admin_job_detail.force_complete') }}
            </button>
            @endif
            @if($job->is_approved)
            <button wire:click="resetApproval"
                    wire:confirm="{{ __('messages.admin_job_detail.confirm_reset_approval') }}"
                    class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition">
                🔄 {{ __('messages.admin_job_detail.reset_approval') }}
            </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Job Info Section --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_job_detail.job_info') }}</h2>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-sm font-bold rounded-lg">
                            {{ $job->category?->name ?? 'Uncategorized' }}
                        </span>
                        @if($job->urgency)
                        <span class="px-3 py-1 text-sm font-bold rounded-lg
                            {{ $job->urgency === 'urgent' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                               ($job->urgency === 'high' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' :
                               ($job->urgency === 'medium' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' :
                               'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400')) }}">
                            {{ ucfirst($job->urgency) }}
                        </span>
                        @endif
                        <span class="px-3 py-1 text-sm font-bold rounded-lg
                            {{ $job->status === 'open' ? 'bg-green-100 text-green-700' :
                               ($job->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                               ($job->status === 'completed' ? 'bg-purple-100 text-purple-700' :
                               ($job->status === 'cancelled' ? 'bg-red-100 text-red-700' :
                               'bg-zinc-100 text-zinc-700'))) }}">
                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </span>
                        <span class="px-3 py-1 text-sm font-bold rounded-lg
                            {{ $job->is_approved ? 'bg-green-100 text-green-700' :
                               'bg-amber-100 text-amber-700' }}">
                            {{ $job->is_approved ? __('messages.admin_jobs.approved') : __('messages.admin_jobs.pending') }}
                        </span>
                        @if($job->code_hold_until && $job->code_hold_until->isFuture())
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-bold rounded-lg">
                            🟡 {{ __('messages.admin_job_detail.held') }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_job_detail.job_title') }}</label>
                        <p class="text-zinc-900 dark:text-white font-medium">{{ $job->getLocalizedTitle() ?: 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_job_detail.description') }}</label>
                        <div class="prose prose-sm max-w-none text-zinc-700 dark:text-zinc-300">
                            @if($job->getLocalizedDescription())
                                {!! nl2br(e($job->getLocalizedDescription())) !!}
                            @else
                                <p class="text-zinc-400 italic">No description provided</p>
                            @endif
                        </div>
                    </div>

                    @if($job->getLocalizedRequirements())
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Requirements</label>
                        <div class="prose prose-sm max-w-none text-zinc-700 dark:text-zinc-300">
                            {!! nl2br(e($job->getLocalizedRequirements())) !!}
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_job_detail.budget') }}</label>
                            <p class="text-zinc-900 dark:text-white font-bold">
                                @if($job->budget_min > 0 || $job->budget_max > 0)
                                    TZS {{ number_format($job->budget_min) }}
                                    @if($job->budget_max > $job->budget_min)
                                    — {{ number_format($job->budget_max) }}
                                    @endif
                                @else
                                    <span class="text-zinc-400">Not set</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_job_detail.budget_type') }}</label>
                            <p class="text-zinc-900 dark:text-white">{{ $job->budget_type ? ucfirst($job->budget_type) : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Duration</label>
                            <p class="text-zinc-900 dark:text-white">{{ $job->duration ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_job_detail.location') }}</label>
                            <p class="text-zinc-900 dark:text-white">
                                @if($job->location)
                                    {{ $job->location }}@if($job->wilaya), {{ $job->wilaya }}@endif
                                @else
                                    <span class="text-zinc-400">Not specified</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Remote Allowed</label>
                            <p class="text-zinc-900 dark:text-white">
                                @if($job->remote_allowed)
                                    <span class="text-green-600">✓ Yes</span>
                                @else
                                    <span class="text-zinc-400">No</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_job_detail.submitted') }}</label>
                            <p class="text-zinc-900 dark:text-white">{{ $job->created_at?->format('d M Y, H:i') ?? 'Unknown' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 pt-2 border-t border-zinc-200 dark:border-zinc-700">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $job->views_count ?? 0 }}</p>
                            <p class="text-xs text-zinc-500">Views</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $job->applications->count() }}</p>
                            <p class="text-xs text-zinc-500">Applications</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $job->hired_worker_id ? '1' : '0' }}</p>
                            <p class="text-xs text-zinc-500">Hired</p>
                        </div>
                    </div>

                    @if($job->rejection_reason)
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                        <label class="block text-sm font-medium text-red-700 dark:text-red-400 mb-1">{{ __('messages.admin_job_detail.rejection_reason') }}</label>
                        <p class="text-red-700 dark:text-red-400">{{ $job->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Applicants Table --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">
                    {{ __('messages.admin_job_detail.applications') }} ({{ $job->applications->count() }})
                </h2>

                @if($job->applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_job_detail.worker') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_job_detail.rating') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_job_detail.price') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_job_detail.duration') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_job_detail.status') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_job_detail.date') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_job_detail.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($job->applications as $application)
                            <tr class="{{ $job->hired_worker_id === $application->user_id ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $application->user?->avatar ? asset('storage/'.$application->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($application->user?->name ?? 'Unknown').'&background=0d9488&color=fff&size=32' }}"
                                             alt="{{ $application->user?->name ?? 'Unknown' }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                        <div>
                                            <p class="font-medium text-zinc-900 dark:text-white">{{ $application->user?->name ?? 'Unknown' }}</p>
                                            @if($application->user?->activeSubscription)
                                            <span class="text-xs px-1 py-0.5 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 rounded">
                                                {{ $application->user->activeSubscription->subscriptionPlan?->name ?? 'Unknown Plan' }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        ⭐ {{ $application->user->rating ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                    TZS {{ number_format($application->bid_amount) }}
                                </td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">
                                    {{ $application->proposed_duration ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-bold rounded-lg
                                        {{ $application->status === 'hired' ? 'bg-green-100 text-green-700' :
                                           ($application->status === 'rejected' ? 'bg-red-100 text-red-700' :
                                           ($application->status === 'shortlisted' ? 'bg-blue-100 text-blue-700' :
                                           'bg-zinc-100 text-zinc-700')) }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $application->created_at?->format('d M Y') ?? 'Unknown date' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="selectApplicant({{ $application->id }})"
                                                class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                                            {{ __('messages.admin_job_detail.view') }}
                                        </button>
                                        @if($job->status === 'open' && !$job->hired_worker_id)
                                        <button wire:click="hireWorker({{ $application->id }})"
                                                wire:confirm="{{ __('messages.admin_job_detail.confirm_hire') }}"
                                                class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded transition">
                                            {{ __('messages.admin_job_detail.hire') }}
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-zinc-500">
                    <div class="text-4xl mb-3">📋</div>
                    <p>{{ __('messages.admin_job_detail.no_applications') }}</p>
                </div>
                @endif
            </div>

            {{-- Payment Section --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_job_detail.payments') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Escrow</span>
                                <span class="text-sm text-zinc-500">TZS</span>
                            </div>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                                {{ number_format($this->getEscrowAmount()) }}
                            </p>
                        </div>

                        <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_job_detail.platform_fee') }}</span>
                                <span class="text-sm text-zinc-500">{{ $commissionRate }}%</span>
                            </div>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                                {{ number_format($this->getPlatformFee()) }}
                            </p>
                        </div>

                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-green-700 dark:text-green-400">{{ __('messages.admin_job_detail.worker_gets') }}</span>
                                <span class="text-sm text-green-500">TZS</span>
                            </div>
                            <p class="text-2xl font-bold text-green-700 dark:text-green-400">
                                {{ number_format($this->getWorkerAmount()) }}
                            </p>
                        </div>
                    </div>

                    @if($job->payment)
                    <div class="space-y-4">
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Escrow</span>
                                <span class="px-2 py-1 text-xs font-bold rounded-lg
                                    {{ $job->payment->status === 'released' ? 'bg-green-100 text-green-700' :
                                       ($job->payment->status === 'refunded' ? 'bg-red-100 text-red-700' :
                                       ($job->payment->status === 'escrowed' ? 'bg-blue-100 text-blue-700' :
                                       'bg-zinc-100 text-zinc-700')) }}">
                                    {{ ucfirst($job->payment->status) }}
                                </span>
                            </div>
                            <p class="text-lg font-bold text-zinc-900 dark:text-white">
                                TZS {{ number_format($job->payment->amount) }}
                            </p>
                            @if($job->payment->payment_reference)
                            <p class="text-xs text-zinc-500 mt-1">Ref: {{ $job->payment->payment_reference }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                @if($job->hired_worker_id && $this->getEscrowAmount() > 0)
                <div class="mt-6 flex items-center gap-3">
                    <button wire:click="releasePayment"
                            wire:confirm="{{ __('messages.admin_job_detail.confirm_release') }}"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                        💸 {{ __('messages.admin_job_detail.release_payment') }}
                    </button>
                    <button wire:click="refundPayment"
                            wire:confirm="{{ __('messages.admin_job_detail.confirm_refund') }}"
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                        ↩️ {{ __('messages.admin_job_detail.refund_client') }}
                    </button>
                    <button wire:click="$set('showSplitModal', true)"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                        ✂️ {{ __('messages.admin_job_detail.split_payment') }}
                    </button>
                </div>
                @endif
            </div>

            {{-- Completion Code Section --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_job_detail.completion_code') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_job_detail.code_status') }}</label>
                        <p class="text-lg font-bold
                            {{ $job->completion_code_used ? 'text-green-600' : 'text-zinc-600' }}">
                            {{ $job->completion_code_used ? __('messages.admin_job_detail.code_used') : ($job->hold_status === 'active' ? __('messages.admin_job_detail.held') : __('messages.admin_job_detail.code_waiting')) }}
                        </p>
                    </div>

                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Code</label>
                        <p class="text-lg font-mono font-bold text-zinc-900 dark:text-white">
                            {{ $job->completion_code ?: '—' }}
                        </p>
                    </div>

                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_job_detail.attempts') }}</label>
                        <p class="text-lg font-bold
                            {{ $job->code_attempts >= 3 ? 'text-red-600' : 'text-zinc-600' }}">
                            {{ $job->code_attempts }}/3
                        </p>
                    </div>
                </div>

                @if($job->hold_started_at)
                <div class="mt-4 p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                    <p class="text-sm text-orange-700 dark:text-orange-400">
                        ⏸️ {{ __('messages.admin_job_detail.held_since') }} {{ $job->hold_started_at->diffForHumans() }}
                    </p>
                </div>
                @endif

                <div class="mt-4">
                    <button wire:click="resetCompletionCode"
                            wire:confirm="Reset completion code?"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                        🔄 {{ __('messages.admin_job_detail.reset_code') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-6">
            {{-- Mteja Card --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_job_detail.client') }}</h3>
                
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ $job->employer?->avatar ? asset('storage/'.$job->employer->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($job->employer?->name ?? 'Unknown').'&background=0d9488&color=fff&size=48' }}"
                         alt="{{ $job->employer?->name ?? 'Unknown' }}"
                         class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $job->employer?->name ?? 'Unknown' }}</p>
                        <div class="flex items-center gap-1 text-sm text-zinc-500">
                            ⭐ {{ $job->employer?->rating ?? '—' }}
                        </div>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_job_detail.account_since') }}</span>
                        <span class="text-zinc-900 dark:text-white">{{ $job->employer?->created_at?->format('M Y') ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_job_detail.jobs_posted') }}</span>
                        <span class="text-zinc-900 dark:text-white">{{ $job->employer?->jobs()->where('employer_id', $job->employer?->id)->count() ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_job_detail.total_spent') }}</span>
                        <span class="text-zinc-900 dark:text-white">TZS {{ number_format($totalAmountSpent) }}</span>
                    </div>
                </div>

                @if($job->employer)
                <a href="{{ route('admin.watumiaji.detail', $job->employer->id) }}" wire:navigate
                   class="block w-full mt-4 px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition text-center">
                    👁️ {{ __('messages.admin_job_detail.view_profile') }}
                </a>
                @endif
            </div>

            {{-- Winga Card (if hired) --}}
            @if($job->hiredWorker)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_job_detail.worker') }}</h3>
                
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ $job->hiredWorker?->avatar ? asset('storage/'.$job->hiredWorker->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($job->hiredWorker?->name ?? 'Unknown').'&background=0d9488&color=fff&size=48' }}"
                         alt="{{ $job->hiredWorker?->name ?? 'Unknown' }}"
                         class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $job->hiredWorker?->name ?? 'Unknown' }}</p>
                        <div class="flex items-center gap-1 text-sm text-zinc-500">
                            ⭐ {{ $job->hiredWorker?->rating ?? '—' }}
                        </div>
                        @if($job->hiredWorker?->activeSubscription)
                        <span class="text-xs px-1 py-0.5 bg-indigo-100 text-indigo-700 rounded">
                            {{ $job->hiredWorker->activeSubscription->subscriptionPlan?->name ?? 'Unknown Plan' }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_job_detail.completed_jobs') }}</span>
                        <span class="text-zinc-900 dark:text-white">{{ $job->hiredWorker->applications()->where('status', 'hired')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_job_detail.total_paid') }}</span>
                        <span class="text-zinc-900 dark:text-white">TZS {{ number_format($job->hiredWorker->wallet_balance ?? 0) }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.watumiaji.detail', $job->hiredWorker->id) }}" wire:navigate
                   class="block w-full mt-4 px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition text-center">
                    👁️ {{ __('messages.admin_job_detail.view_profile') }}
                </a>
            </div>
            @endif

            {{-- Timeline --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_job_detail.history') }}</h3>
                
                <div class="space-y-3">
                    @foreach($this->getTimeline() as $event)
                    <div class="flex items-start gap-3">
                        <div class="text-lg">{{ $event['icon'] }}</div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $event['title'] }}</p>
                            <p class="text-xs text-zinc-500">{{ $event['description'] }}</p>
                            <p class="text-xs text-zinc-400 mt-1">{{ $event['time'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Rejection Modal --}}
    <div x-data="{ show: @entangle('showRejectionModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">Reject Job</h3>
                <p class="text-sm text-zinc-500 mb-4">Provide a reason for rejecting this job.</p>
                <form wire:submit="rejectJob">
                    <textarea wire:model="rejectionReason"
                              rows="4"
                              placeholder="Enter rejection reason..."
                              class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                    @error('rejectionReason')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex items-center gap-3 mt-4">
                        <button type="button" @click="$wire.set('showRejectionModal', false)"
                                class="flex-1 px-4 py-2 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 rounded-lg font-medium transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                            Reject Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Payment Split Modal --}}
    <div x-data="{ show: @entangle('showSplitModal') }" 
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_job_detail.split_payment') }}</h3>
                
                <div class="mb-6">
                    <div class="text-center mb-4">
                        <p class="text-sm text-zinc-500 mb-2">{{ __('messages.admin_job_detail.amount_to_split') }}:</p>
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                            TZS {{ number_format($this->getEscrowAmount() - $this->getPlatformFee()) }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_job_detail.worker') }}</label>
                                <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $workerPercentage }}%</span>
                            </div>
                            <input type="range" 
                                   wire:model.live="workerPercentage"
                                   min="0" max="100" step="5"
                                   class="w-full">
                            <p class="text-xs text-zinc-500 mt-1">
                                TZS {{ number_format(($this->getEscrowAmount() - $this->getPlatformFee()) * ($this->workerPercentage / 100)) }}
                            </p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_job_detail.client') }}</label>
                                <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $clientPercentage }}%</span>
                            </div>
                            <input type="range" 
                                   wire:model.live="clientPercentage"
                                   min="0" max="100" step="5"
                                   class="w-full">
                            <p class="text-xs text-zinc-500 mt-1">
                                TZS {{ number_format(($this->getEscrowAmount() - $this->getPlatformFee()) * ($this->clientPercentage / 100)) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="$wire.set('showSplitModal', false)"
                            class="flex-1 px-4 py-2 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 rounded-lg font-medium transition">
                        {{ __('messages.admin_job_detail.cancel') }}
                    </button>
                    <button wire:click="splitPayment"
                            class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                        {{ __('messages.admin_job_detail.split_payment') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
