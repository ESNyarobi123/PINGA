{{-- Skeleton card matching tafuta-wafanyakazi worker card layout --}}
<article class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden shadow-sm flex flex-col animate-in fade-in duration-300">
    <div class="p-5 pb-4">
        <flux:skeleton.group animate="shimmer" class="flex items-start gap-4">
            <flux:skeleton class="size-16 shrink-0 rounded-xl" />
            <div class="min-w-0 flex-1 space-y-2">
                <flux:skeleton.line class="w-2/3" />
                <flux:skeleton.line class="w-1/2" size="lg" />
                <div class="flex gap-3 pt-1">
                    <flux:skeleton class="size-5 rounded-full" />
                    <flux:skeleton class="h-4 w-16 rounded" />
                </div>
            </div>
        </flux:skeleton.group>
    </div>
    <div class="px-5 pb-3 flex-1 space-y-2">
        <flux:skeleton.line class="w-3/4" />
        <flux:skeleton.line class="w-full" />
        <flux:skeleton.line class="w-1/3" />
        <div class="flex flex-wrap gap-1.5 pt-2">
            <flux:skeleton class="h-6 w-14 rounded-lg" />
            <flux:skeleton class="h-6 w-20 rounded-lg" />
            <flux:skeleton class="h-6 w-16 rounded-lg" />
            <flux:skeleton class="h-6 w-20 rounded-lg" />
        </div>
    </div>
    <div class="p-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30 flex items-center gap-2">
        <flux:skeleton class="h-9 flex-1 rounded-lg" />
        <flux:skeleton class="size-9 rounded-lg shrink-0" />
        <flux:skeleton class="size-9 rounded-lg shrink-0" />
    </div>
</article>
