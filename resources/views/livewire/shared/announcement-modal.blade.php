<div>
    @if($show && $announcement)
        @php
            $accent = [
                'info' => 'from-blue-500 to-indigo-500',
                'success' => 'from-emerald-500 to-teal-500',
                'warning' => 'from-amber-500 to-orange-500',
                'danger' => 'from-rose-500 to-red-500',
            ][$announcement->type] ?? 'from-blue-500 to-indigo-500';

            $icon = [
                'info' => 'ℹ️',
                'success' => '✅',
                'warning' => '⚠️',
                'danger' => '🚨',
            ][$announcement->type] ?? 'ℹ️';
        @endphp

        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
             x-data="{
                seconds: {{ (int) $announcement->min_view_seconds }},
                ready: {{ ((int) $announcement->min_view_seconds) === 0 ? 'true' : 'false' }},
                init() {
                    if (this.seconds > 0) {
                        const t = setInterval(() => {
                            this.seconds--;
                            if (this.seconds <= 0) {
                                this.ready = true;
                                clearInterval(t);
                            }
                        }, 1000);
                    }
                }
             }"
             wire:transition.fade>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
                {{-- Header bar (gradient by type) --}}
                <div class="h-2 bg-gradient-to-r {{ $accent }}"></div>

                <div class="p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="text-3xl">{{ $icon }}</div>
                        <div class="flex-1">
                            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $announcement->title }}</h2>
                        </div>
                    </div>

                    <div class="text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-line leading-relaxed">{{ $announcement->body }}</div>

                    @if($announcement->cta_label && $announcement->cta_url)
                        <a href="{{ $announcement->cta_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="mt-5 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r {{ $accent }} text-white font-medium rounded-lg hover:opacity-90 transition">
                            {{ $announcement->cta_label }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif

                    @if($announcement->is_dismissible)
                        <div class="mt-6 flex justify-end">
                            <button type="button"
                                    wire:click="dismiss"
                                    :disabled="!ready"
                                    x-bind:class="ready ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 hover:opacity-90' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-500 cursor-not-allowed'"
                                    class="px-5 py-2 rounded-lg font-medium transition">
                                <span x-show="ready">{{ __('messages.announcement.got_it') }}</span>
                                <span x-show="!ready">{{ __('messages.announcement.wait') }} (<span x-text="seconds"></span>s)</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
