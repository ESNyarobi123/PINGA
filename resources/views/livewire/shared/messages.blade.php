<div class="min-h-[calc(100vh-64px)]">
    <div class="max-w-7xl mx-auto px-4 py-6">
        {{-- Compact Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">💬 {{ __('messages.shared_messages.title') }}</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.shared_messages.subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 h-[calc(100vh-200px)]">
            {{-- Sidebar: Conversation List --}}
            <div class="lg:col-span-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl flex flex-col overflow-hidden shadow-sm {{ $showChatOnMobile ? 'hidden lg:flex' : 'flex' }}">
                <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-800">
                    <h2 class="text-sm font-bold text-zinc-900 dark:text-white">{{ __('messages.shared_messages.conversations') }} ({{ count($conversations) }})</h2>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($conversations as $conv)
                        <button
                            wire:click="selectConversation({{ $conv['id'] }})"
                            class="group w-full flex items-center gap-3 px-4 py-3 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-all text-left
                                {{ $activeConversationId == $conv['id'] ? 'bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-l-emerald-500' : 'border-l-4 border-l-transparent' }}"
                        >
                            <div class="relative flex-shrink-0">
                                <img src="{{ $conv['other_avatar'] }}" class="w-11 h-11 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700 group-hover:border-emerald-400 transition-colors" alt="">
                                @if($conv['unread'] > 0)
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-md border-2 border-white dark:border-zinc-900">
                                        {{ $conv['unread'] }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <p class="text-sm font-bold text-zinc-900 dark:text-white truncate group-hover:text-emerald-600 transition-colors">{{ $conv['other_name'] }}</p>
                                    <span class="text-[10px] text-zinc-500 dark:text-zinc-400 flex-shrink-0">{{ $conv['last_time'] }}</span>
                                </div>
                                <p class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 truncate mb-1">{{ $conv['job_title'] }}</p>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400 truncate">{{ Str::limit($conv['last_message'], 35) }}</p>
                            </div>
                        </button>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center px-4">
                            <div class="w-14 h-14 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-zinc-900 dark:text-white">{{ __('messages.shared_messages.no_conversations') }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.shared_messages.accept_to_chat') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Chat Area --}}
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl flex flex-col overflow-hidden shadow-sm {{ $showChatOnMobile ? 'flex' : 'hidden lg:flex' }}">
                @if($activeConversationId)
                    {{-- Chat Header --}}
                    @php
                        $activeConv = collect($conversations)->firstWhere('id', $activeConversationId);
                    @endphp
                    @if($activeConv)
                    <div class="flex items-center gap-3 px-4 sm:px-5 py-3 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                        {{-- Back button on mobile --}}
                        <button wire:click="backToList" class="lg:hidden p-1 -ml-1 rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                            <svg class="w-5 h-5 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <img src="{{ $activeConv['other_avatar'] }}" class="w-10 h-10 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700" alt="">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-zinc-900 dark:text-white truncate">{{ $activeConv['other_name'] }}</p>
                            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 truncate">{{ $activeConv['job_title'] }}</p>
                        </div>
                        <div class="hidden sm:flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 rounded-full">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">{{ __('messages.shared_messages.online') }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- ChatBox Component --}}
                    @livewire('shared.chat-box', ['conversationId' => $activeConversationId], key('chat-'.$activeConversationId))
                @else
                    <div class="flex flex-col items-center justify-center h-full text-center py-16 px-4">
                        <div class="w-20 h-20 rounded-full border-2 border-dashed border-zinc-300 dark:border-zinc-700 flex items-center justify-center mb-4">
                            <div class="w-14 h-14 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                                <span class="text-2xl">👋</span>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.shared_messages.select_conversation') }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 max-w-sm">{{ __('messages.shared_messages.click_to_start') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
