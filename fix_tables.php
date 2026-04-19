<?php

$files = [
    'resources/views/livewire/admin/watumiaji.blade.php',
    'resources/views/livewire/admin/migogoro.blade.php',
];

foreach ($files as $file) {
    if (! file_exists($file)) {
        continue;
    }
    $content = file_get_contents($file);

    // Replace opening and closing tags for table components
    $replaces = [
        '<flux:columns>' => '<flux:table.columns>',
        '</flux:columns>' => '</flux:table.columns>',
        '<flux:column' => '<flux:table.column',
        '</flux:column>' => '</flux:table.column>',
        '<flux:rows>' => '<flux:table.rows>',
        '</flux:rows>' => '</flux:table.rows>',
        '<flux:row>' => '<flux:table.row>',
        '<flux:row ' => '<flux:table.row ',
        '</flux:row>' => '</flux:table.row>',
        '<flux:cell' => '<flux:table.cell',
        '</flux:cell>' => '</flux:table.cell>',
    ];

    foreach ($replaces as $find => $replace) {
        $content = str_replace($find, $replace, $content);
    }

    file_put_contents($file, $content);
}
echo 'Done replacing table tags.\\n';
