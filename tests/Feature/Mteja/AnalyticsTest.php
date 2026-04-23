<?php

use App\Livewire\Mteja\Analytics;
use App\Models\User;
use Livewire\Livewire;

test('mteja analytics supports all-days period', function () {
    $mteja = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);

    $component = Livewire::actingAs($mteja)
        ->test(Analytics::class)
        ->set('period', 'all')
        ->call('loadData')
        ->assertSet('ready', true)
        ->assertSet('period', 'all');

    $trend = $component->get('applicationsTrend');

    expect($trend)->toBeArray()
        ->and(count($trend))->toBeGreaterThanOrEqual(1)
        ->and(count($trend))->toBeLessThanOrEqual(24);
});
