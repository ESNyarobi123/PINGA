<?php

$file = 'resources/views/layouts/muajili.blade.php';
$content = file_get_contents($file);
$content = str_replace('<flux:sidebar sticky stashable ', '<flux:sidebar sticky collapsible ', $content);
file_put_contents($file, $content);
