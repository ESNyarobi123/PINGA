<?php

$files = [
    'resources/views/layouts/admin.blade.php',
    'resources/views/layouts/muajili.blade.php',
    'resources/views/layouts/mfanyakazi.blade.php',
];

foreach ($files as $file) {
    if (! file_exists($file)) {
        continue;
    }
    $content = file_get_contents($file);

    // Replace the sticky collapsible with stashable
    $content = str_replace('<flux:sidebar sticky collapsible ', '<flux:sidebar sticky stashable="desktop" ', $content);

    // Hide Winga text on collapse
    $content = str_replace('<span class="text-lg font-bold text-zinc-900 dark:text-white">Winga</span>', '<span class="text-md font-bold text-zinc-900 dark:text-white in-data-flux-sidebar-collapsed-desktop:hidden">Winga</span>', $content);

    // Hide badge on collapse
    $content = preg_replace('/<flux:badge color="([^"]+)" size="sm">([^<]+)<\/flux:badge>/', '<flux:badge color="$1" size="sm" class="in-data-flux-sidebar-collapsed-desktop:hidden">$2</flux:badge>', $content);

    // Fix sidebar toggle alignment
    $content = str_replace('<flux:sidebar.toggle class="hidden lg:flex" icon="chevron-double-left" />', '<flux:sidebar.toggle class="hidden lg:block in-data-flux-sidebar-collapsed-desktop:hidden" icon="chevron-double-left" />', $content);

    // Fix groups that hide the icons inside
    $content = preg_replace('/<flux:sidebar\.group :heading="\'([^\']+)\'" class="grid">/', '<div class="px-3 pt-4 pb-2 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider in-data-flux-sidebar-collapsed-desktop:hidden">$1</div>'."\n".'                <div class="grid gap-1">', $content);

    // Close the divs that replaced the group
    $content = str_replace('</flux:sidebar.group>', '</div>', $content);

    file_put_contents($file, $content);
}
echo "All sidebars fixed!\n";
