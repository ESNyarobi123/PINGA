<div>
    @if($visible)
    <div class="mb-6 p-5 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/40 dark:to-orange-950/30 border border-amber-200 dark:border-amber-800">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-amber-800 dark:text-amber-400 mb-1">{{ $title }}</h3>
                        <p class="text-sm text-amber-700 dark:text-amber-300">{{ $message }}</p>
                    </div>
                    <button wire:click="close" class="text-amber-400 hover:text-amber-600 transition flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @if($suggested)
                <div class="mt-4 bg-white dark:bg-zinc-900 rounded-xl p-4 border border-amber-200 dark:border-amber-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="px-2 py-1 bg-winga-100 dark:bg-winga-900/40 text-winga-700 dark:text-winga-400 text-[10px] font-black rounded-lg uppercase tracking-wider mb-2 inline-block">
                                {{ __('messages.upgrade.recommended') }}: {{ $suggested['name'] }}
                            </span>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $suggested['benefit'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-winga-600 text-lg">TZS {{ number_format($suggested['price']) }}</p>
                            <a href="{{ route('mfanyakazi.subscription') }}" wire:navigate
                                class="inline-flex items-center gap-1 text-sm font-bold text-white bg-winga-600 hover:bg-winga-700 px-4 py-2 rounded-xl transition mt-2">
                                {{ __('messages.upgrade.upgrade_plan') }} →
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
