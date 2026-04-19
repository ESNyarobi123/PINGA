<div class="relative" wire:poll.15s="poll">
    {{-- Bell Button --}}
    <button
        wire:click="togglePanel"
        class="relative p-2 rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all duration-200"
        aria-label="Arifa"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white dark:ring-zinc-900 animate-pulse">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Notifications Panel --}}
    @if($showPanel)
    <div
        x-data
        x-init="$el.scrollTop = 0"
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-xl shadow-zinc-200/50 dark:shadow-black/50 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <h3 class="text-sm font-bold text-zinc-900 dark:text-white">{{ __('messages.notifications.new_notifications') }}</h3>
                @if($unreadCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-black tracking-widest text-white bg-red-500 rounded-full">{{ $unreadCount }}</span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                @if($unreadCount > 0)
                    <button wire:click="markAllRead" class="text-xs font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 transition">
                        {{ __('messages.notifications.mark_all_read') }}
                    </button>
                @endif
                <button wire:click="togglePanel" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Notifications List --}}
        <div class="max-h-96 overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-800/50">
            @forelse($notifications as $notif)
                @php
                    $colors = [
                        'blue' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20',
                        'green' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20',
                        'red' => 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-500/20',
                        'yellow' => 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border border-yellow-100 dark:border-yellow-500/20',
                        'gray' => 'bg-zinc-100 dark:bg-zinc-500/10 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-500/20',
                    ];
                    $colorClass = $colors[$notif['color']] ?? $colors['blue'];
                @endphp
                <div
                    wire:key="notif-{{ $notif['id'] }}"
                    class="flex items-start gap-4 px-4 py-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/60 transition cursor-pointer {{ is_null($notif['read_at']) ? 'bg-zinc-50/50 dark:bg-white/[0.02]' : '' }}"
                    wire:click="markAsRead('{{ $notif['id'] }}')"
                >
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center shadow-sm {{ $colorClass }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            @if($notif['icon'] === 'chat-bubble-left-right')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                            @elseif($notif['icon'] === 'star')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                            @elseif($notif['icon'] === 'check-circle')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            @endif
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-0.5">
                            <p class="text-sm font-bold text-zinc-900 dark:text-white truncate" title="{{ $notif['title'] }}">{{ $notif['title'] }}</p>
                            @if(is_null($notif['read_at']))
                                <span class="flex-shrink-0 w-2.5 h-2.5 rounded-full bg-violet-500 mt-1"></span>
                            @endif
                        </div>
                        <p class="text-[13px] text-zinc-600 dark:text-zinc-400 leading-snug line-clamp-2">{{ $notif['message'] }}</p>
                        <p class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 mt-1.5 uppercase tracking-wider">{{ $notif['time'] }}</p>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                        <span class="text-3xl opacity-50">🔔</span>
                    </div>
                    <p class="text-zinc-900 dark:text-white font-bold mb-1">{{ __('messages.notifications.no_notifications') }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 max-w-[200px]">{{ __('messages.notifications.no_notifications_desc') }}</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        @if(count($notifications) > 0)
        <div class="bg-zinc-50 dark:bg-zinc-900/50 p-3 border-t border-zinc-200 dark:border-zinc-800 text-center">
            <a href="{{ route('notifications') }}" wire:navigate class="inline-flex items-center justify-center gap-1.5 w-full py-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs font-bold text-zinc-700 dark:text-zinc-300 hover:text-violet-600 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-500/30 transition-all shadow-sm">
                {{ __('messages.notifications.view_all') }} <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        @endif
    </div>
    @endif
</div>
