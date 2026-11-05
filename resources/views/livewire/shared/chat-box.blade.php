<div class="h-full flex flex-col bg-zinc-50 dark:bg-zinc-950">
    {{-- Messages Area (poll scoped here so the composer is never re-rendered mid-typing) --}}
    <div class="flex-1 overflow-y-auto px-4 sm:px-5 py-4 space-y-3" id="chat-messages" wire:poll.5s="poll">
        @forelse($messages as $msg)
            <div class="flex items-end gap-2.5 {{ $msg['is_mine'] ? 'flex-row-reverse' : '' }}" wire:key="msg-{{ $msg['id'] }}">
                @if(!$msg['is_mine'])
                    <img src="{{ $msg['sender_avatar'] }}" class="w-8 h-8 rounded-full flex-shrink-0 object-cover border-2 border-zinc-200 dark:border-zinc-700" alt="">
                @endif
                <div class="max-w-[75%] sm:max-w-[65%] {{ $msg['is_mine'] ? 'items-end' : 'items-start' }} flex flex-col gap-1">
                    <div class="px-3.5 py-2 text-sm leading-relaxed shadow-sm whitespace-pre-line break-words
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
        <form wire:submit="sendMessage" class="flex items-end gap-2.5" x-data="{ text: '' }">
            <div class="flex-1 relative">
                <textarea
                    wire:model="newMessage"
                    x-model="text"
                    placeholder="{{ __('messages.chat_box.placeholder') }}"
                    rows="1"
                    class="w-full bg-zinc-100 dark:bg-zinc-900 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 rounded-lg px-4 py-2.5 text-sm resize-none border transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/20
                        @error('newMessage') border-red-500 focus:border-red-500 @else border-zinc-200 dark:border-zinc-700 focus:border-emerald-500 @enderror"
                    x-on:keydown.enter.prevent="if (! $event.shiftKey) { $el.form.requestSubmit() }"
                    x-on:chat-message-sent.window="text = ''"
                ></textarea>
                @error('newMessage')
                    <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button
                type="submit"
                title="{{ __('messages.chat_box.send') }}"
                class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-lg flex items-center justify-center transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg"
                x-bind:disabled="text.trim() === ''"
                wire:loading.attr="disabled"
                wire:target="sendMessage"
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
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', () => {
            const el = document.getElementById('chat-messages');
            if (el) {
                el.scrollTop = el.scrollHeight;
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('chat-messages');
        if (el) {
            el.scrollTop = el.scrollHeight;
        }
    });
</script>
