<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_comms.title') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_comms.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($unreadCount > 0)
            <div class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition">
                📬 {{ $unreadCount }} {{ __('messages.admin_comms.unread') }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_comms.total') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ $activeCount }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_comms.active_conversations') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 4.5l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_comms.unread') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ $unreadCount }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_comms.unread_messages') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_comms.disputes') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ $disputeCount }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_comms.dispute_conversations') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6 0 3 3 0 000 6zM6.75 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_comms.broadcasts') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ $broadcasts->count() }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_comms.sent_messages') }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
        <div class="border-b border-zinc-200 dark:border-zinc-800">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button type="button" wire:click="$set('activeTab', 'conversations')"
                        class="{{ $activeTab === 'conversations' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                    {{ __('messages.admin_comms.tab_conversations') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'broadcasts')"
                        class="{{ $activeTab === 'broadcasts' ? 'border-winga-500 text-winga-600' : 'border-transparent text-zinc-500 hover:text-zinc-700' }} 
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                    {{ __('messages.admin_comms.tab_broadcasts') }}
                </button>
            </nav>
        </div>

        <div class="p-6">
            {{-- Tab 1: Conversations --}}
            @if($activeTab === 'conversations')
            <div class="space-y-4">
                {{-- Filters --}}
                <div class="flex flex-wrap gap-4">
                    <input wire:model.live.debounce.300ms="search" 
                           type="text" 
                           placeholder="{{ __('messages.admin_comms.search_placeholder') }}"
                           class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">

                    <select wire:model.live="filterStatus" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                        <option value="">{{ __('messages.admin_comms.all_status') }}</option>
                        <option value="active">{{ __('messages.admin_comms.active') }}</option>
                        <option value="ended">{{ __('messages.admin_comms.ended') }}</option>
                        <option value="unread">{{ __('messages.admin_comms.unread') }}</option>
                    </select>

                    <select wire:model.live="filterType" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                        <option value="">{{ __('messages.admin_comms.all_types') }}</option>
                        <option value="dispute">{{ __('messages.admin_comms.dispute') }}</option>
                        <option value="normal">{{ __('messages.admin_comms.normal') }}</option>
                    </select>

                    <div class="flex gap-2">
                        <input wire:model.live="dateFrom" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                        <input wire:model.live="dateTo" type="date" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Conversation List --}}
                    <div class="lg:col-span-1">
                        <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg overflow-hidden max-h-[600px] overflow-y-auto">
                            @forelse($conversations as $conv)
                            @php $unreadCount = $this->getUnreadMessagesCount($conv); @endphp
                            <button wire:click="viewConversation({{ $conv->id }})"
                                    class="w-full text-left p-4 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition
                                        {{ $activeConversationId === $conv->id ? 'bg-winga-100 dark:bg-winga-900/30 border-l-4 border-l-winga-500' : '' }}">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if($conv->job && $conv->job->disputes->count() > 0)
                                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5"/>
                                            </svg>
                                        </div>
                                        @else
                                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">
                                                {{ $conv->employer->name ?? '—' }} ↔ {{ $conv->worker->name ?? '—' }}
                                            </p>
                                            @if($unreadCount > 0)
                                            <span class="px-1.5 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">
                                                {{ $unreadCount }}
                                            </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">📋 {{ $conv->job->title ?? '—' }}</p>
                                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1 truncate">
                                            {{ $conv->latestMessage?->body ?? __('messages.admin_comms.no_messages_label') }}
                                        </p>
                                        <p class="text-xs text-zinc-500 mt-1">{{ $conv->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </button>
                            @empty
                            <div class="p-8 text-center text-zinc-500">
                                <div class="text-4xl mb-3">💬</div>
                                <p>{{ __('messages.admin_comms.no_conversations') }}</p>
                            </div>
                            @endforelse
                        </div>

                        @if($conversations->hasPages())
                        <div class="mt-4">{{ $conversations->links() }}</div>
                        @endif
                    </div>

                    {{-- Message View --}}
                    <div class="lg:col-span-2">
                        @if($activeConversationId && count($messages) > 0)
                        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
                            <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                                <h3 class="font-medium text-zinc-900 dark:text-white">{{ __('messages.admin_comms.conversation') }} #{{ $activeConversationId }}</h3>
                                <button wire:click="closeConversation" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                                    ✕
                                </button>
                            </div>
                            
                            <div class="p-4 max-h-[500px] overflow-y-auto space-y-4">
                                @foreach($messages as $msg)
                                <div class="flex items-start gap-3 {{ $msg['is_admin'] ? 'flex-row-reverse' : '' }}">
                                    <img src="{{ $msg['sender_avatar'] }}" 
                                         class="w-8 h-8 rounded-full flex-shrink-0 object-cover" 
                                         alt="{{ $msg['sender_name'] }}">
                                    <div class="flex-1 max-w-md">
                                        <div class="flex items-center gap-2 mb-1 {{ $msg['is_admin'] ? 'flex-row-reverse' : '' }}">
                                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $msg['sender_name'] }}</span>
                                            <span class="text-xs px-2 py-0.5 bg-zinc-100 text-zinc-700 rounded">
                                                {{ ucfirst($msg['sender_type']) }}
                                            </span>
                                            <span class="text-xs text-zinc-500">{{ $msg['time'] }}</span>
                                        </div>
                                        <div class="p-3 rounded-lg {{ $msg['is_admin'] ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-zinc-100 dark:bg-zinc-800' }}">
                                            <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ $msg['body'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Reply Form --}}
                            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                                <div class="flex gap-2">
                                    <input wire:model.live="replyMessage"
                                           type="text"
                                           placeholder="{{ __('messages.admin_comms.reply_placeholder') }}"
                                           class="flex-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                           @keydown.enter="sendReply">
                                    <button wire:click="sendReply"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                                        {{ __('messages.admin_comms.send') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @elseif($activeConversationId)
                        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-12 text-center">
                            <div class="text-4xl mb-3">📭</div>
                            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_comms.no_messages_conv') }}</p>
                        </div>
                        @else
                        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-16 text-center">
                            <div class="text-5xl mb-4 opacity-50">💬</div>
                            <p class="text-zinc-600 dark:text-zinc-300 font-semibold">{{ __('messages.admin_comms.select_conversation') }}</p>
                            <p class="text-zinc-400 dark:text-zinc-500 text-sm mt-1">{{ __('messages.admin_comms.click_left') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Tab 2: Broadcasts --}}
            @if($activeTab === 'broadcasts')
            <div class="space-y-6">
                {{-- Send New Broadcast --}}
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4">{{ __('messages.admin_comms.send_broadcast_title') }}</h3>

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-200">
                            <p class="font-semibold">{{ __('messages.admin_comms.broadcast_form_hint') }}</p>
                            <ul class="mt-2 list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_comms.broadcast_title_label') }}</label>
                            <input wire:model.blur="broadcastTitle"
                                   type="text"
                                   placeholder="{{ __('messages.admin_comms.broadcast_title_placeholder') }}"
                                   class="w-full px-3 py-2 bg-white dark:bg-zinc-900 border rounded-lg text-sm @error('broadcastTitle') border-red-500 @else border-zinc-200 dark:border-zinc-700 @enderror">
                            @error('broadcastTitle')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_comms.broadcast_message_label') }}</label>
                            <textarea wire:model.blur="broadcastMessage"
                                      placeholder="{{ __('messages.admin_comms.broadcast_message_placeholder') }}"
                                      class="w-full px-3 py-2 bg-white dark:bg-zinc-900 border rounded-lg text-sm @error('broadcastMessage') border-red-500 @else border-zinc-200 dark:border-zinc-700 @enderror"
                                      rows="4"></textarea>
                            @error('broadcastMessage')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_comms.broadcast_type_label') }}</label>
                            <select wire:model.live="broadcastType" class="w-full px-3 py-2 bg-white dark:bg-zinc-900 border rounded-lg text-sm @error('broadcastType') border-red-500 @else border-zinc-200 dark:border-zinc-700 @enderror">
                                <option value="announcement">📢 {{ __('messages.admin_comms.announcement') }}</option>
                                <option value="maintenance">🔧 {{ __('messages.admin_comms.maintenance') }}</option>
                                <option value="warning">⚠️ {{ __('messages.admin_comms.warning_type') }}</option>
                                <option value="info">ℹ️ {{ __('messages.admin_comms.information') }}</option>
                            </select>
                            @error('broadcastType')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_comms.target_audience') }}</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 @error('targetAudience') rounded-lg border border-red-500 p-2 @enderror">
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="checkbox" wire:model.live="targetAudience" value="all" class="rounded border-zinc-300 dark:border-zinc-600">
                                    <span>{{ __('messages.admin_comms.all_users') }}</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="checkbox" wire:model.live="targetAudience" value="clients" class="rounded border-zinc-300 dark:border-zinc-600">
                                    <span>{{ __('messages.admin_comms.clients') }}</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="checkbox" wire:model.live="targetAudience" value="workers" class="rounded border-zinc-300 dark:border-zinc-600">
                                    <span>{{ __('messages.admin_comms.workers') }}</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="checkbox" wire:model.live="targetAudience" value="premium" class="rounded border-zinc-300 dark:border-zinc-600">
                                    <span>{{ __('messages.admin_comms.premium_users') }}</span>
                                </label>
                            </div>
                            @error('targetAudience')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="button"
                                wire:click="sendBroadcast"
                                wire:confirm="{{ __('messages.admin_comms.confirm_broadcast') }}"
                                wire:loading.attr="disabled"
                                wire:target="sendBroadcast"
                                class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white rounded-lg font-medium transition">
                            <span wire:loading.remove wire:target="sendBroadcast">📡 {{ __('messages.admin_comms.send_broadcast') }}</span>
                            <span wire:loading wire:target="sendBroadcast">{{ __('messages.admin_comms.sending_broadcast') }}</span>
                        </button>
                    </div>
                </div>

                {{-- Broadcast History --}}
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Target</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Sent By</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                @forelse($broadcasts as $broadcast)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                                    <td class="px-4 py-3">
                                        <div>
                                            <p class="font-medium text-zinc-900 dark:text-white text-sm">{{ $broadcast->title }}</p>
                                            <p class="text-xs text-zinc-500 mt-1 line-clamp-2">{{ $broadcast->body }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php $kind = $broadcast->announcement_type ?? 'announcement'; @endphp
                                        <span class="px-2 py-1 text-xs font-bold rounded-lg
                                            {{ $kind === 'announcement' ? 'bg-blue-100 text-blue-700' :
                                               ($kind === 'maintenance' ? 'bg-amber-100 text-amber-700' :
                                               ($kind === 'warning' ? 'bg-red-100 text-red-700' :
                                               'bg-zinc-100 text-zinc-700')) }}">
                                            {{ ucfirst($kind) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $broadcast->target_label }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-zinc-900 dark:text-white">{{ $broadcast->admin?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ $broadcast->sent_at?->format('d M Y, H:i') ?? '—' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-16 text-center text-zinc-400">
                                        <div class="text-4xl mb-3">📡</div>
                                        <p class="font-medium">{{ __('messages.admin_comms.no_broadcasts') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($broadcasts->hasPages())
                    <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                        {{ $broadcasts->links() }}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
