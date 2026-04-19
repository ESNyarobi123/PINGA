<div class="flex items-center gap-1">
    <button
        wire:click="switchLocale('sw')"
        title="Kiswahili"
        class="px-2.5 py-1 text-xs font-bold rounded-lg transition
            {{ $locale === 'sw'
                ? 'bg-winga-500 text-white shadow-sm'
                : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
        SW
    </button>
    <button
        wire:click="switchLocale('en')"
        title="English"
        class="px-2.5 py-1 text-xs font-bold rounded-lg transition
            {{ $locale === 'en'
                ? 'bg-winga-500 text-white shadow-sm'
                : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
        EN
    </button>
</div>
