<div wire:init="$dispatch('refresh-pending-jobs')" wire:poll.30s>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_pending.title') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">
                {{ $pendingCount }} {{ __('messages.admin_pending.needs_approval') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="approveAll"
                    wire:confirm="{{ __('messages.admin_pending.confirm_approve_all') }}"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                ✅ {{ __('messages.admin_pending.approve_all') }}
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="{{ __('messages.admin_pending.search_placeholder') }}"
                   class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">

            <select wire:model.live="filterCategory" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_pending.all_categories') }}</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterUrgency" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_pending.all_urgency') }}</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
            </select>

            <select wire:model.live="filterLocation" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_pending.all_regions') }}</option>
                @foreach($regions as $region)
                <option value="{{ $region }}">{{ $region }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterFlag" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_pending.all_flags') }}</option>
                <option value="phone">🚨 {{ __('messages.admin_pending.phone_number') }}</option>
                <option value="url">🔗 URL</option>
                <option value="new_user">⚠️ {{ __('messages.admin_pending.new_user') }}</option>
            </select>

            <button wire:click="clearFilters"
                    class="px-3 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm transition">
                {{ __('messages.admin_pending.reset') }}
            </button>
        </div>
    </div>

    {{-- Jobs Grid --}}
    @if($jobs->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($jobs as $job)
        @php $flags = $this->checkFlags($job); @endphp
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden hover:shadow-lg transition-shadow">
            {{-- Header --}}
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-800">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-bold text-zinc-900 dark:text-white text-lg mb-1">{{ $job->getLocalizedTitle() }}</h3>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold rounded-lg">
                                {{ $job->category->name }}
                            </span>
                            @if($job->urgency)
                            <span class="px-2 py-1 text-xs font-bold rounded-lg
                                {{ $job->urgency === 'urgent' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                   ($job->urgency === 'high' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' :
                                   ($job->urgency === 'medium' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' :
                                   'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400')) }}">
                                {{ ucfirst($job->urgency) }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-2xl">📋</div>
                </div>

                {{-- Description --}}
                <p class="text-zinc-600 dark:text-zinc-400 text-sm mb-3 line-clamp-3">
                    {{ Str::limit($job->getLocalizedDescription(), 150) }}
                </p>

                {{-- Posted By --}}
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $job->employer?->avatar ? asset('storage/'.$job->employer->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($job->employer?->name ?? 'U').'&background=0d9488&color=fff&size=32' }}"
                         alt="{{ $job->employer?->name ?? 'Unknown' }}"
                         class="w-8 h-8 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-zinc-900 dark:text-white text-sm truncate">{{ $job->employer?->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-zinc-500">
                            {{ __('messages.admin_pending.account_age') }} {{ $job->employer?->created_at?->diffForHumans() ?? 'Unknown' }}
                        </p>
                    </div>
                </div>

                {{-- Budget & Location --}}
                <div class="flex items-center justify-between text-sm">
                    <div class="font-bold text-zinc-900 dark:text-white">
                        TZS {{ number_format($job->budget_min) }}
                        @if($job->budget_max > $job->budget_min)
                        — {{ number_format($job->budget_max) }}
                        @endif
                    </div>
                    <div class="text-zinc-500">
                        📍 {{ $job->location ?? 'N/A' }}
                    </div>
                </div>
            </div>

            {{-- Auto Flags --}}
            @if(!empty($flags))
            <div class="px-4 py-3 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800">
                <div class="space-y-1">
                    @foreach($flags as $flag)
                    <div class="flex items-center gap-2 text-sm">
                        <span>{{ $flag['type'] === 'phone' ? '🚨' : ($flag['type'] === 'url' ? '🔗' : '⚠️') }}</span>
                        <span class="text-amber-700 dark:text-amber-400">{{ $flag['message'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="p-4 bg-zinc-50 dark:bg-zinc-800 flex items-center gap-3">
                <button wire:click="approveJob({{ $job->id }})"
                        class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                    ✅ {{ __('messages.admin_pending.approve') }}
                </button>
                <button wire:click="$dispatch('openRejectionModal', jobId: {{ $job->id }})"
                        class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                    ❌ {{ __('messages.admin_pending.reject') }}
                </button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($jobs->hasPages())
    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-16">
        <div class="text-6xl mb-4">📋</div>
        <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.admin_pending.no_pending') }}</h3>
        <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_pending.all_approved') }}</p>
    </div>
    @endif

    {{-- Rejection Modal --}}
<div x-data="{ show: false, jobId: null, reason: '' }" 
     x-on:open-rejection-modal.window="show = true; jobId = $event.detail.jobId"
     x-show="show" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_pending.reject_job') }}</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.admin_pending.rejection_reason') }}</label>
                    <select x-model="reason" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                        <option value="">{{ __('messages.admin_pending.select_reason') }}</option>
                        <option value="Ina namba ya simu">{{ __('messages.admin_pending.has_phone') }}</option>
                        <option value="Ina URL/website">{{ __('messages.admin_pending.has_url') }}</option>
                        <option value="Maelezo yasiyofaa">{{ __('messages.admin_pending.inappropriate') }}</option>
                        <option value="Bajeti isiyo ya kweli">{{ __('messages.admin_pending.unrealistic_budget') }}</option>
                        <option value="Nakala ya kazi iliyopo">{{ __('messages.admin_pending.duplicate') }}</option>
                        <option value="Nyingine">{{ __('messages.admin_pending.other') }}</option>
                    </select>
                </div>

                <div x-show="reason === 'Nyingine'" x-cloak>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.admin_pending.custom_reason') }}</label>
                    <textarea x-model="customReason" 
                              class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg"
                              rows="3"
                              placeholder="{{ __('messages.admin_pending.write_reason') }}"></textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6">
                <button @click="show = false"
                        class="flex-1 px-4 py-2 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 rounded-lg font-medium transition">
                    {{ __('messages.admin_pending.cancel') }}
                </button>
                <button @click="if(reason) { Livewire.dispatch('rejectJob', { jobId: jobId, reason: reason }); show = false; } else { alert('{{ __('messages.admin_pending.select_reason_alert') }}'); }"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                    {{ __('messages.admin_pending.reject_job') }}
                </button>
            </div>
        </div>
    </div>
</div>
</div>
