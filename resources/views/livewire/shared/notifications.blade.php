<div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 pb-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">🔔 {{ __('messages.notifications.my_notifications') }}</h1>
                <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-1">{{ __('messages.notifications.subtitle') }}</p>
            </div>
            @if($unreadCount > 0)
            <button wire:click="markAllRead"
                class="px-4 py-2 text-sm font-bold text-winga-600 dark:text-winga-400 hover:bg-winga-50 dark:hover:bg-winga-900/20 rounded-xl transition">
                {{ __('messages.notifications.mark_all_read') }} ({{ $unreadCount }})
            </button>
            @endif
        </div>

        {{-- Filter tabs --}}
        <div class="flex gap-2 mb-5">
            @foreach(['all' => __('messages.notifications.filter_all'), 'unread' => __('messages.notifications.filter_unread')] as $key => $label)
            <button wire:click="$set('filter', '{{ $key }}')"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition
                    {{ $filter === $key ? 'bg-winga-500 text-white shadow-md' : 'bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300' }}">
                {{ $label }}
                @if($key === 'unread' && $unreadCount > 0)
                <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white text-[10px] font-black rounded-full">{{ $unreadCount }}</span>
                @endif
            </button>
            @endforeach
        </div>

        {{-- Notifications list --}}
        <div class="space-y-2">
            @forelse($notifications as $notif)
            @php
                $data    = $notif->data;
                $isUnread = is_null($notif->read_at);
                $colorMap = [
                    'green'  => 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
                    'red'    => 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
                    'amber'  => 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400',
                    'blue'   => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
                    'winga'  => 'bg-winga-100 dark:bg-winga-900/30 text-winga-600 dark:text-winga-400',
                ];
                $iconColor = $colorMap[$data['color'] ?? 'blue'] ?? $colorMap['blue'];
            @endphp
            <div wire:key="notif-page-{{ $notif->id }}"
                 class="bg-white dark:bg-zinc-900 rounded-2xl border {{ $isUnread ? 'border-winga-200 dark:border-winga-800' : 'border-zinc-200 dark:border-zinc-700' }} p-4 flex items-start gap-4 transition hover:shadow-sm {{ $isUnread ? 'ring-1 ring-winga-200 dark:ring-winga-900' : '' }}">

                {{-- Icon --}}
                <div class="w-10 h-10 flex-shrink-0 rounded-xl flex items-center justify-center text-lg {{ $iconColor }}">
                    @if(($data['icon'] ?? '') === 'check-circle') ✅
                    @elseif(($data['icon'] ?? '') === 'banknotes') 💸
                    @elseif(($data['icon'] ?? '') === 'x-circle') ❌
                    @elseif(($data['icon'] ?? '') === 'exclamation-triangle') ⚠️
                    @elseif(($data['icon'] ?? '') === 'clock') ⏳
                    @elseif(($data['icon'] ?? '') === 'star') ⭐
                    @else 🔔
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-bold text-zinc-900 dark:text-white leading-snug">{{ $data['title'] ?? __('messages.notifications.notification') }}</p>
                        @if($isUnread)
                        <span class="flex-shrink-0 w-2 h-2 bg-winga-500 rounded-full mt-1.5"></span>
                        @endif
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-0.5 leading-relaxed">{{ $data['message'] ?? '' }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $notif->created_at->diffForHumans() }}</span>
                        @if(!empty($data['action_url']))
                        <a href="{{ $data['action_url'] }}" wire:navigate
                           class="text-xs font-bold text-winga-600 dark:text-winga-400 hover:underline">
                            {{ $data['action_label'] ?? __('messages.notifications.view') }} →
                        </a>
                        @endif
                        @if($isUnread)
                        <button wire:click="markAsRead('{{ $notif->id }}')"
                            class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                            {{ __('messages.notifications.read') }}
                        </button>
                        @endif
                        <button wire:click="delete('{{ $notif->id }}')"
                            wire:confirm="{{ __('messages.notifications.delete_confirm') }}"
                            class="text-xs text-red-400 hover:text-red-600 ml-auto">
                            {{ __('messages.notifications.delete') }}
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-16 text-center">
                <div class="text-5xl mb-4">🔔</div>
                <p class="font-bold text-zinc-900 dark:text-white mb-1">{{ __('messages.notifications.no_notifications') }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $filter === 'unread' ? __('messages.notifications.all_read') : __('messages.notifications.no_notifications_yet') }}
                </p>
            </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
        <div class="mt-6">{{ $notifications->links() }}</div>
        @endif
    </div>
</div>
