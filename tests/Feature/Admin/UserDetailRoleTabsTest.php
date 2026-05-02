<?php

use App\Livewire\Admin\UserDetail;
use App\Models\User;
use Livewire\Livewire;

test('winga user detail shows services and portfolio tabs instead of jobs', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $winga = User::factory()->create(['role' => 'winga', 'onboarding_completed' => true]);

    Livewire::actingAs($admin)
        ->test(UserDetail::class, ['id' => $winga->id])
        ->assertSee('Huduma')
        ->assertSee('Portfolio')
        ->assertSee('Maombi')
        ->assertDontSee('Kazi (');
});

test('mteja user detail shows jobs tab instead of services', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $mteja = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);

    Livewire::actingAs($admin)
        ->test(UserDetail::class, ['id' => $mteja->id])
        ->assertSee('Kazi (')
        ->assertDontSee('Huduma (')
        ->assertDontSee('Portfolio (');
});

test('winga services tab displays service details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $winga = User::factory()->create(['role' => 'winga', 'onboarding_completed' => true]);

    Livewire::actingAs($admin)
        ->test(UserDetail::class, ['id' => $winga->id])
        ->set('activeTab', 'services')
        ->assertSee('hajaweka huduma yoyote bado');
});

test('winga portfolio tab displays empty state', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $winga = User::factory()->create(['role' => 'winga', 'onboarding_completed' => true]);

    Livewire::actingAs($admin)
        ->test(UserDetail::class, ['id' => $winga->id])
        ->set('activeTab', 'portfolio')
        ->assertSee('hajaweka portfolio yoyote bado');
});

test('mteja jobs tab displays empty state', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $mteja = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);

    Livewire::actingAs($admin)
        ->test(UserDetail::class, ['id' => $mteja->id])
        ->set('activeTab', 'jobs')
        ->assertSee('hajaweka kazi yoyote bado');
});
