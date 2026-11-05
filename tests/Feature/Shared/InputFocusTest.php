<?php

test('layouts keep the text cursor when livewire refreshes an input', function () {
    $head = file_get_contents(resource_path('views/partials/head.blade.php'));
    $wekaCode = file_get_contents(resource_path('views/livewire/winga/weka-code.blade.php'));

    expect($head)->toContain('morph.updating')
        ->and($head)->toContain('document.activeElement')
        ->and($wekaCode)->not->toContain('<div class="space-y-6" wire:poll.60s>');
});
