@props(['sidebar' => false])

<a {{ $attributes->merge(['class' => 'flex items-center gap-2 group']) }}>
    <span class="flex items-center justify-center rounded-lg bg-winga-500 p-1 group-hover:bg-winga-600 transition-colors">
        <x-app-logo-icon class="size-7 text-white" />
    </span>
    <span class="font-bold text-xl tracking-tight text-zinc-900 dark:text-white">
        Winga
    </span>
</a>
