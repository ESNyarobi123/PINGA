<div class="h-full flex flex-col bg-zinc-50 dark:bg-zinc-950" wire:poll.5s="poll">
    {{-- Messages Area --}}
    <div class="flex-1 overflow-y-auto px-4 sm:px-5 py-4 space-y-3" id="chat-messages">
        @forelse($messages as $msg)
            <div class="flex items-end gap-2.5 {{ $msg['is_mine'] ? 'flex-row-reverse' : '' }}" wire:key="msg-{{ $msg['id'] }}">
                @if(!$msg['is_mine'])
                    <img src="{{ $msg['sender_avatar'] }}" class="w-8 h-8 rounded-full flex-shrink-0 object-cover border-2 border-zinc-200 dark:border-zinc-700" alt="">
                @endif
                <div class="max-w-[75%] sm:max-w-[65%] {{ $msg['is_mine'] ? 'items-end' : 'items-start' }} flex flex-col gap-1">
                    <div class="px-3.5 py-2 text-sm leading-relaxed shadow-sm
                        {{ $msg['is_mine']
                            ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl rounded-br-md'
                            : 'bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-zinc-100 rounded-2xl rounded-bl-md' }}">
                        {{ $msg['body'] }}
                    </div>
                    <span class="text-[10px] text-zinc-400 dark:text-zinc-500 px-1">{{ $msg['time'] }}</span>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full py-10 text-center">
                <div class="text-3xl mb-3 opacity-50">💬</div>
                <p class="text-sm font-bold text-zinc-900 dark:text-white">{{ __('messages.chat_box.no_messages') }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.chat_box.write_to_start') }}</p>
            </div>
        @endforelse
    </div>

    {{-- Input Area --}}
    <div class="bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800 px-4 sm:px-5 py-3.5">
        <form wire:submit="sendMessage" class="flex items-end gap-2.5">
            <div class="flex-1 relative">
                <textarea
                    wire:model="newMessage"
                    placeholder="{{ __('messages.chat_box.placeholder') }}"
                    rows="1"
                    class="w-full bg-zinc-100 dark:bg-zinc-900 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 rounded-lg px-4 py-2.5 text-sm resize-none border border-zinc-200 dark:border-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    x-data
                    x-on:keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage() }"
                ></textarea>
            </div>
            <button
                type="submit"
                class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-lg flex items-center justify-center transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg hover:scale-105"
                wire:loading.attr="disabled"
            >
                <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                </svg>
                <svg wire:loading wire:target="sendMessage" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>
        <p class="text-[10px] text-zinc-400 dark:text-zinc-500 text-center mt-2">{{ __('messages.chat_box.press') }} <kbd class="px-1 py-0.5 border border-zinc-300 dark:border-zinc-700 rounded bg-zinc-100 dark:bg-zinc-800 text-[9px]">Enter</kbd> {{ __('messages.chat_box.to_send') }}</p>
    </div>
</div>

<script>
    // Auto-scroll to bottom on new messages smoothly
    document.addEventListener('livewire:update', () => {
        const el = document.getElementById('chat-messages');
        if (el) el.scrollTop = el.scrollHeight;
    });
    window.addEventListener('load', () => {
        const el = document.getElementById('chat-messages');
        if (el) el.scrollTop = el.scrollHeight;
    });
</script>
