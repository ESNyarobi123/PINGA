@props(['announcements', 'scope' => 'public'])

@if($announcements->count() > 0)
    <div class="w-full" x-data="{
            dismissed: JSON.parse(localStorage.getItem('pinga_dismissed_announcements') || '[]'),
            isDismissed(id) { return this.dismissed.includes(id); },
            dismiss(id) {
                this.dismissed.push(id);
                localStorage.setItem('pinga_dismissed_announcements', JSON.stringify(this.dismissed));
            }
        }">
        @foreach($announcements as $a)
            @php
                $styles = [
                    'info' => 'bg-blue-50 border-blue-200 text-blue-900 dark:bg-blue-950/40 dark:border-blue-800 dark:text-blue-100',
                    'success' => 'bg-emerald-50 border-emerald-200 text-emerald-900 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-100',
                    'warning' => 'bg-amber-50 border-amber-200 text-amber-900 dark:bg-amber-950/40 dark:border-amber-800 dark:text-amber-100',
                    'danger' => 'bg-rose-50 border-rose-200 text-rose-900 dark:bg-rose-950/40 dark:border-rose-800 dark:text-rose-100',
                ][$a->type] ?? 'bg-blue-50 border-blue-200 text-blue-900';

                $icon = [
                    'info' => 'ℹ️',
                    'success' => '✅',
                    'warning' => '⚠️',
                    'danger' => '🚨',
                ][$a->type] ?? 'ℹ️';
            @endphp

            <div x-show="!isDismissed({{ $a->id }})"
                 x-cloak
                 class="border-b {{ $styles }}">
                <div class="max-w-7xl mx-auto px-4 py-2.5 flex items-center gap-3 text-sm">
                    <span class="text-lg shrink-0">{{ $icon }}</span>
                    <div class="flex-1 min-w-0">
                        <span class="font-semibold">{{ $a->title }}</span>
                        <span class="opacity-90 ml-1 hidden sm:inline">— {{ $a->body }}</span>
                    </div>
                    @if($a->cta_label && $a->cta_url)
                        <a href="{{ $a->cta_url }}"
                           class="shrink-0 px-3 py-1 text-xs font-semibold rounded-full bg-white/70 dark:bg-white/10 hover:bg-white/90 dark:hover:bg-white/20 transition">
                            {{ $a->cta_label }} →
                        </a>
                    @endif
                    @if($a->is_dismissible)
                        <button type="button"
                                @click="dismiss({{ $a->id }})"
                                class="shrink-0 p-1 rounded hover:bg-black/10 dark:hover:bg-white/10 transition"
                                aria-label="Dismiss">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
