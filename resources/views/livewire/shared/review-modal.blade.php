<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data x-on:keydown.escape.window="$wire.close()">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="close"></div>

        {{-- Modal --}}
        <div class="relative z-10 w-full max-w-md bg-gray-900 border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
            {{-- Header --}}
            <div class="px-6 pt-6 pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-white">⭐ {{ __('messages.review.title') }}</h2>
                    <button wire:click="close" class="text-gray-500 hover:text-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @if($job)
                    <div class="flex items-center gap-3 p-3 bg-white/5 rounded-xl mb-5">
                        <div class="w-10 h-10 rounded-xl bg-teal-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ $job->getLocalizedTitle() }}</p>
                            <p class="text-xs text-gray-500">
                                @if(auth()->id() === $job->employer_id)
                                    {{ __('messages.review.reviewing') }}: {{ $job->hiredWorker?->name ?? '—' }}
                                @else
                                    {{ __('messages.review.reviewing') }}: {{ $job->employer?->name ?? '—' }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Star Rating --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-300 mb-3">{{ __('messages.review.click_stars') }}</label>
                    <div class="flex items-center gap-2 justify-center">
                        @for ($i = 1; $i <= 5; $i++)
                            <button
                                wire:click="setRating({{ $i }})"
                                type="button"
                                class="transition-all duration-150 hover:scale-110"
                            >
                                <svg class="w-10 h-10 transition-colors duration-150 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-700' }}"
                                     fill="{{ $rating >= $i ? 'currentColor' : 'none' }}"
                                     stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    @if($rating > 0)
                        <p class="text-center mt-2 text-sm font-medium
                            {{ $rating === 5 ? 'text-yellow-400' : ($rating >= 4 ? 'text-emerald-400' : ($rating >= 3 ? 'text-blue-400' : 'text-red-400')) }}">
                            {{ ['', __('messages.review.rating_1'), __('messages.review.rating_2'), __('messages.review.rating_3'), __('messages.review.rating_4'), __('messages.review.rating_5')][$rating] }}
                        </p>
                    @endif
                    @error('rating')
                        <p class="text-red-400 text-xs text-center mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comment --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.review.comment_label') }}</label>
                    <textarea
                        wire:model="comment"
                        rows="3"
                        placeholder="{{ __('messages.review.comment_placeholder') }}"
                        class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-600 rounded-xl px-4 py-3 text-sm focus:border-teal-500 focus:outline-none resize-none transition"
                    ></textarea>
                </div>

                {{-- Submit --}}
                <button
                    wire:click="submit"
                    {{ $rating === 0 ? 'disabled' : '' }}
                    class="w-full py-3 rounded-xl font-semibold text-sm transition-all duration-200
                        {{ $rating > 0 ? 'bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white cursor-pointer' : 'bg-gray-800 text-gray-600 cursor-not-allowed' }}"
                >
                    <span wire:loading.remove wire:target="submit">{{ __('messages.review.submit') }}</span>
                    <span wire:loading wire:target="submit">{{ __('messages.review.submitting') }}</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
