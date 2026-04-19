<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_audit.title') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_audit.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportAuditLogs"
                    class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition">
                📤 {{ __('messages.admin_audit.export_csv') }}
            </button>
            <button wire:click="clearOldLogs"
                    wire:confirm="{{ __('messages.admin_audit.confirm_clear') }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                🗑️ {{ __('messages.admin_audit.clear_old') }}
            </button>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_audit.total_logs') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($totalLogs) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_audit.all_activities') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_audit.today') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($todayLogs) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_audit.activities_today') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_audit.active_admins') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($uniqueAdmins) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_audit.unique_admins') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_audit.top_action') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">
                {{ $actionStats->first()?->action ?? __('messages.admin_audit.none') }}
            </p>
            <p class="text-xs text-zinc-500">{{ $actionStats->first()?->count ?? 0 }} {{ __('messages.admin_audit.times') }}</p>
        </div>
    </div>

    {{-- Analytics Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Most Active Admins --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_audit.most_active_admins') }}</h3>
            <div class="space-y-3">
                @forelse($mostActiveAdmins as $admin)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ $admin->admin?->avatar ? asset('storage/'.$admin->admin->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($admin->admin?->name ?? 'A').'&background=0d9488&color=fff&size=32' }}"
                             alt="{{ $admin->admin?->name ?? 'Unknown' }}"
                             class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $admin->admin?->name ?? 'Deleted Admin' }}</p>
                            <p class="text-xs text-zinc-500">{{ $admin->admin?->email ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-zinc-900 dark:text-white">{{ $admin->count }}</p>
                        <p class="text-xs text-zinc-500">{{ __('messages.admin_audit.actions') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-zinc-500">{{ __('messages.admin_audit.no_activity_7days') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Action Statistics --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_audit.top_actions') }}</h3>
            <div class="space-y-3">
                @forelse($actionStats as $stat)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">{{ $this->getActionIcon($stat->action) }}</span>
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $stat->action)) }}</p>
                            <p class="text-xs text-zinc-500">{{ __('messages.admin_audit.most_performed') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-zinc-900 dark:text-white">{{ $stat->count }}</p>
                        <p class="text-xs text-zinc-500">{{ __('messages.admin_audit.times') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-zinc-500">{{ __('messages.admin_audit.no_actions_7days') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-6">
        <div class="flex flex-wrap gap-4">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="{{ __('messages.admin_audit.search_placeholder') }}"
                   class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">

            <select wire:model.live="filterAction" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_audit.all_actions') }}</option>
                @foreach($availableActions as $action)
                <option value="{{ $action }}">{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterModel" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_audit.all_models') }}</option>
                @foreach($availableModels as $model)
                <option value="{{ $model }}">{{ $model }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterAdmin" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_audit.all_admins') }}</option>
                @foreach($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="sortBy" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="created_at">{{ __('messages.admin_audit.sort_date') }}</option>
                <option value="action">{{ __('messages.admin_audit.sort_action') }}</option>
                <option value="admin_id">{{ __('messages.admin_audit.sort_admin') }}</option>
                <option value="model_type">{{ __('messages.admin_audit.sort_model') }}</option>
            </select>

            <div class="flex gap-2">
                <input wire:model.live="dateFrom" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <input wire:model.live="dateTo" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <div class="text-sm text-zinc-500">
                {{ __('messages.admin_audit.showing') }} {{ $logs->firstItem() }}-{{ $logs->lastItem() }} {{ __('messages.admin_audit.of') }} {{ $logs->total() }} {{ __('messages.admin_audit.logs') }}
            </div>
        </div>
    </div>

    {{-- Audit Logs Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            {{ __('messages.admin_audit.date') }}
                            @if($sortBy === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_audit.admin') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('action')">
                            {{ __('messages.admin_audit.action') }}
                            @if($sortBy === 'action')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_audit.target') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_audit.details') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_audit.ip_address') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_audit.user_agent') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($logs as $log)
                    @php $details = $this->getLogDetails($log); $color = $this->getActionColor($log->action); @endphp
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        <td class="px-4 py-3">
                            <div class="space-y-1">
                                <p class="text-sm text-zinc-900 dark:text-white">{{ $log->created_at->format('d M Y, H:i:s') }}</p>
                                <p class="text-xs text-zinc-500">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $log->admin?->avatar ? asset('storage/'.$log->admin->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($log->admin?->name ?? 'A').'&background=0d9488&color=fff&size=32' }}"
                                     alt="{{ $log->admin?->name ?? 'Unknown' }}"
                                     class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $log->admin?->name ?? 'Deleted Admin' }}</p>
                                    <p class="text-xs text-zinc-500">{{ $log->admin?->email ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">{{ $this->getActionIcon($log->action) }}</span>
                                <div>
                                    <span class="px-2 py-1 text-xs font-bold rounded-lg bg-{{ $color }}-100 text-{{ $color }}-700">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($log->model_type)
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                    {{ $details['model']['type'] ?? class_basename($log->model_type) }}
                                </p>
                                @if($log->model_id)
                                <p class="text-xs text-zinc-500">ID: {{ $log->model_id }}</p>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-zinc-400">System</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if(($details['old_values'] ?? null) || ($details['new_values'] ?? null))
                            <div class="space-y-2 max-w-xs">
                                @if($details['old_values'] ?? null)
                                <div>
                                    <p class="text-xs font-medium text-red-600 mb-1">{{ __('messages.admin_audit.old_values') }}:</p>
                                    <div class="text-xs text-zinc-600 bg-red-50 dark:bg-red-900/20 p-2 rounded overflow-x-auto">
                                        @if(is_array($details['old_values'] ?? null))
                                        @foreach(($details['old_values'] ?? []) as $key => $value)
                                        @if(!is_array($value))
                                        <div>{{ $key }}: {{ $value }}</div>
                                        @endif
                                        @endforeach
                                        @else
                                        {{ $details['old_values'] ?? '' }}
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($details['new_values'] ?? null)
                                <div>
                                    <p class="text-xs font-medium text-green-600 mb-1">{{ __('messages.admin_audit.new_values') }}:</p>
                                    <div class="text-xs text-zinc-600 bg-green-50 dark:bg-green-900/20 p-2 rounded overflow-x-auto">
                                        @if(is_array($details['new_values'] ?? null))
                                        @foreach(($details['new_values'] ?? []) as $key => $value)
                                        @if(!is_array($value))
                                        <div>{{ $key }}: {{ $value }}</div>
                                        @endif
                                        @endforeach
                                        @else
                                        {{ $details['new_values'] ?? '' }}
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-zinc-400">{{ __('messages.admin_audit.no_details') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="space-y-1">
                                <p class="text-sm font-mono text-zinc-900 dark:text-white">{{ $log->ip_address ?? '—' }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="max-w-xs truncate text-xs text-zinc-600 dark:text-zinc-400" 
                                 title="{{ $log->user_agent }}">
                                {{ $log->user_agent }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center text-zinc-400">
                            <div class="text-4xl mb-3">📝</div>
                            <p class="font-medium">{{ __('messages.admin_audit.no_logs') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
