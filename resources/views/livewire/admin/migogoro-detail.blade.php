<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Dispute #{{ $dispute->id }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">{{ $job->getLocalizedTitle() }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($dispute->status === 'resolved' || $dispute->status === 'closed')
            <button wire:click="reopenDispute"
                    wire:confirm="{{ __('messages.admin_dispute_detail.confirm_reopen') }}"
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                🔄 {{ __('messages.admin_dispute_detail.reopen') }}
            </button>
            @endif
            @if($dispute->priority !== 'high' && $dispute->status === 'open')
            <button wire:click="escalateDispute"
                    wire:confirm="{{ __('messages.admin_dispute_detail.confirm_escalate') }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                ⚠️ {{ __('messages.admin_dispute_detail.escalate') }}
            </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Dispute Status --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_dispute_detail.dispute_status') }}</h2>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-{{ $this->getPriorityColor() }}-100 text-{{ $this->getPriorityColor() }}-700 dark:bg-{{ $this->getPriorityColor() }}-900/30 dark:text-{{ $this->getPriorityColor() }}-400 text-sm font-bold rounded-lg">
                            {{ ucfirst($dispute->priority) }} {{ __('messages.admin_dispute_detail.priority') }}
                        </span>
                        <span class="px-3 py-1 bg-{{ $this->getStatusColor() }}-100 text-{{ $this->getStatusColor() }}-700 dark:bg-{{ $this->getStatusColor() }}-900/30 dark:text-{{ $this->getStatusColor() }}-400 text-sm font-bold rounded-lg">
                            {{ ucfirst($dispute->status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_dispute_detail.escrow_amount') }}</label>
                        <p class="text-xl font-bold text-zinc-900 dark:text-white">TZS {{ number_format($escrowAmount) }}</p>
                    </div>
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_dispute_detail.days_open') }}</label>
                        <p class="text-xl font-bold {{ $daysOpen >= 3 ? 'text-red-600' : ($daysOpen >= 1 ? 'text-amber-600' : 'text-zinc-600') }}">
                            {{ $daysOpen }} {{ __('messages.admin_dispute_detail.days') }}
                        </p>
                    </div>
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_dispute_detail.opened') }}</label>
                        <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ $dispute->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_dispute_detail.last_updated') }}</label>
                        <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ $dispute->updated_at->format('d M Y') }}</p>
                    </div>
                </div>

                @if($dispute->description)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.admin_dispute_detail.description') }}</label>
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <p class="text-zinc-700 dark:text-zinc-300">{{ $dispute->description }}</p>
                    </div>
                </div>
                @endif

                @if($dispute->resolution_reason)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.admin_dispute_detail.resolution_reason') }}</label>
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-green-700 dark:text-green-400">{{ $dispute->resolution_reason }}</p>
                    </div>
                </div>
                @endif

                @if($dispute->admin_notes)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.admin_dispute_detail.admin_notes') }}</label>
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-blue-700 dark:text-blue-400">{{ $dispute->admin_notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Parties Involved --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_dispute_detail.parties') }}</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    {{-- Client --}}
                    <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ $job->employer?->avatar ? asset('storage/'.$job->employer->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($job->employer?->name ?? 'U').'&background=0d9488&color=fff&size=40' }}"
                                 alt="{{ $job->employer?->name ?? 'Unknown' }}"
                                 class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $job->employer?->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-zinc-500">{{ __('messages.admin_dispute_detail.client') }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.phone') }}:</span>
                                <span class="text-zinc-900 dark:text-white">{{ $job->employer?->phone ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.email') }}:</span>
                                <span class="text-zinc-900 dark:text-white">{{ $job->employer?->email ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.member_since') }}:</span>
                                <span class="text-zinc-900 dark:text-white">{{ $job->employer?->created_at?->format('M Y') ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Worker --}}
                    <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        @if($job->hiredWorker)
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ $job->hiredWorker->avatar ? asset('storage/'.$job->hiredWorker->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($job->hiredWorker->name).'&background=0d9488&color=fff&size=40' }}"
                                 alt="{{ $job->hiredWorker->name }}"
                                 class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $job->hiredWorker->name }}</p>
                                <p class="text-xs text-zinc-500">{{ __('messages.admin_dispute_detail.worker') }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.phone') }}:</span>
                                <span class="text-zinc-900 dark:text-white">{{ $job->hiredWorker->phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.email') }}:</span>
                                <span class="text-zinc-900 dark:text-white">{{ $job->hiredWorker->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.rating') }}:</span>
                                <span class="text-zinc-900 dark:text-white">⭐ {{ $job->hiredWorker->rating ?? '—' }}</span>
                            </div>
                        </div>
                        @else
                        <div class="text-center text-zinc-500">
                            <p>{{ __('messages.admin_dispute_detail.no_worker') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Chat History --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_dispute_detail.chat_history') }}</h2>
                
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @foreach($chatMessages as $message)
                    <div class="flex items-start gap-3 {{ $message['sender_type'] === 'client' ? 'flex-row-reverse' : '' }}">
                        <img src="{{ $message['avatar'] ? asset('storage/'.$message['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($message['sender']).'&background=0d9488&color=fff&size=32' }}"
                             alt="{{ $message['sender'] }}"
                             class="w-8 h-8 rounded-full object-cover">
                        <div class="flex-1 max-w-md">
                            <div class="flex items-center gap-2 mb-1 {{ $message['sender_type'] === 'client' ? 'flex-row-reverse' : '' }}">
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $message['sender'] }}</span>
                                <span class="text-xs text-zinc-500">{{ $message['timestamp'] }}</span>
                                <span class="text-xs px-1 py-0.5 bg-zinc-100 text-zinc-700 rounded">
                                    {{ $message['sender_type'] === 'client' ? __('messages.admin_dispute_detail.client') : __('messages.admin_dispute_detail.worker') }}
                                </span>
                            </div>
                            <div class="p-3 bg-zinc-100 dark:bg-zinc-800 rounded-lg">
                                <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ $message['message'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Evidence --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_dispute_detail.evidence') }}</h2>
                
                @if(count($evidenceFiles) > 0)
                <div class="space-y-3">
                    @foreach($evidenceFiles as $evidence)
                    <div class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $evidence['description'] }}</p>
                                <p class="text-xs text-zinc-500">{{ __('messages.admin_dispute_detail.added') }} {{ $evidence['created_at'] }} {{ __('messages.admin_dispute_detail.by') }} {{ $evidence['uploaded_by'] }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs bg-zinc-100 text-zinc-700 rounded">
                            {{ ucfirst($evidence['type']) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-zinc-500">
                    <div class="text-4xl mb-3">📄</div>
                    <p>{{ __('messages.admin_dispute_detail.no_evidence') }}</p>
                </div>
                @endif

                {{-- Add Evidence Form --}}
                <div class="mt-6 p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                    <h3 class="text-sm font-medium text-zinc-900 dark:text-white mb-3">{{ __('messages.admin_dispute_detail.add_evidence') }}</h3>
                    <div class="space-y-3">
                        <textarea wire:model.live="evidenceDescription"
                                  placeholder="{{ __('messages.admin_dispute_detail.evidence_placeholder') }}"
                                  class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                  rows="3"></textarea>
                        <button wire:click="uploadEvidence"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                            📎 {{ __('messages.admin_dispute_detail.add_evidence') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-6">
            {{-- Resolution Actions --}}
            @if($dispute->status === 'open')
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_dispute_detail.resolution_actions') }}</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_dispute_detail.resolution_reason') }}</label>
                        <textarea wire:model.live="resolutionReason"
                                  placeholder="{{ __('messages.admin_dispute_detail.decision_placeholder') }}"
                                  class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                  rows="2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_dispute_detail.admin_notes') }}</label>
                        <textarea wire:model.live="adminNotes"
                                  placeholder="{{ __('messages.admin_dispute_detail.notes_placeholder') }}"
                                  class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                  rows="2"></textarea>
                    </div>

                    <div class="space-y-2">
                        <button wire:click="releaseToWorker"
                                wire:confirm="{{ __('messages.admin_dispute_detail.confirm_release') }}"
                                class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                            💸 {{ __('messages.admin_dispute_detail.release_to_worker') }}
                        </button>
                        <button wire:click="refundToClient"
                                wire:confirm="{{ __('messages.admin_dispute_detail.confirm_refund') }}"
                                class="w-full px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                            ↩️ {{ __('messages.admin_dispute_detail.refund_to_client') }}
                        </button>
                        <button wire:click="splitEscrow"
                                wire:confirm="{{ __('messages.admin_dispute_detail.confirm_split') }}"
                                class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                            ✂️ {{ __('messages.admin_dispute_detail.split_50_50') }}
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- Penalty Actions --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_dispute_detail.penalty_actions') }}</h2>
                
                @if($dispute->penalty_applied)
                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <p class="text-sm text-red-700 dark:text-red-400">{{ __('messages.admin_dispute_detail.penalty_applied') }}</p>
                </div>
                @else
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_dispute_detail.penalty_type') }}</label>
                        <select wire:model.live="penaltyType" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                            <option value="">{{ __('messages.admin_dispute_detail.select_penalty') }}</option>
                            <option value="warning">{{ __('messages.admin_dispute_detail.warning') }}</option>
                            <option value="temporary_suspension">{{ __('messages.admin_dispute_detail.temp_suspension') }}</option>
                            <option value="permanent_ban">{{ __('messages.admin_dispute_detail.permanent_ban') }}</option>
                            <option value="financial_penalty">{{ __('messages.admin_dispute_detail.financial_penalty') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_dispute_detail.penalty_reason') }}</label>
                        <textarea wire:model.live="penaltyReason"
                                  placeholder="{{ __('messages.admin_dispute_detail.penalty_reason_placeholder') }}"
                                  class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                  rows="2"></textarea>
                    </div>

                    @if($penaltyType === 'financial_penalty')
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_dispute_detail.penalty_amount') }}</label>
                        <input wire:model.live="penaltyAmount" type="number" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                    </div>
                    @endif

                    <button wire:click="applyPenalty"
                            wire:confirm="{{ __('messages.admin_dispute_detail.confirm_penalty') }}"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                        ⚠️ {{ __('messages.admin_dispute_detail.apply_penalty') }}
                    </button>
                </div>
                @endif
            </div>

            {{-- Job Details --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_dispute_detail.job_details') }}</h2>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.category') }}:</span>
                        <span class="text-zinc-900 dark:text-white">{{ $job->category?->name ?? 'Uncategorized' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.budget') }}:</span>
                        <span class="text-zinc-900 dark:text-white">TZS {{ number_format($job->budget_min) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.location') }}:</span>
                        <span class="text-zinc-900 dark:text-white">{{ $job->location ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.posted') }}:</span>
                        <span class="text-zinc-900 dark:text-white">{{ $job->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">{{ __('messages.admin_dispute_detail.status') }}:</span>
                        <span class="text-zinc-900 dark:text-white">{{ ucfirst($job->status) }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.kazi.detail', $job->id) }}" 
                       class="w-full px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition text-center block">
                        👁️ {{ __('messages.admin_dispute_detail.view_full_job') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
