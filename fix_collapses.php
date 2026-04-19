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

    // Replace the incorrectly used toggle with proper collapse button
    $content = str_replace('<flux:sidebar.toggle class="hidden lg:block in-data-flux-sidebar-collapsed-desktop:hidden" icon="chevron-double-left" />', '<flux:sidebar.collapse class="hidden lg:flex" />', $content);

    // Just in case the previous script used a slightly different match:
    $content = str_replace('<flux:sidebar.toggle class="hidden lg:flex" icon="chevron-double-left" />', '<flux:sidebar.collapse class="hidden lg:flex" />', $content);

    file_put_contents($file, $content);
}
echo "All collapses fixed!\n";
