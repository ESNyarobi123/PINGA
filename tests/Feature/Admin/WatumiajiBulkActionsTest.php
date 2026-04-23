<?php

use App\Livewire\Admin\Watumiaji;
use App\Models\User;
use Livewire\Livewire;

test('admin can bulk verify selected users', function () {
    $admin = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
    $a = User::factory()->create(['role' => 'winga', 'is_verified' => false]);
    $b = User::factory()->create(['role' => 'mteja', 'is_verified' => false]);

    Livewire::actingAs($admin)
        ->test(Watumiaji::class)
        ->set('selectedUsers', [(string) $a->id, (string) $b->id])
        ->call('bulkVerifyUsers')
        ->assertSet('selectedUsers', [])
        ->assertSet('selectAll', false);

    expect($a->fresh()->is_verified)->toBeTrue()
        ->and($b->fresh()->is_verified)->toBeTrue();
});

test('admin can bulk suspend selected users that are active', function () {
    $admin = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
    $a = User::factory()->create(['role' => 'winga', 'suspended_at' => null]);
    $b = User::factory()->create(['role' => 'mteja', 'suspended_at' => null]);

    Livewire::actingAs($admin)
        ->test(Watumiaji::class)
        ->set('selectedUsers', [(string) $a->id, (string) $b->id])
        ->call('bulkSuspendUsers')
        ->assertSet('selectedUsers', []);

    expect($a->fresh()->suspended_at)->not->toBeNull()
        ->and($b->fresh()->suspended_at)->not->toBeNull();
});

test('bulk select all only includes non admin users', function () {
    $admin = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
    $winga = User::factory()->create(['role' => 'winga']);

    $selected = Livewire::actingAs($admin)
        ->test(Watumiaji::class)
        ->set('selectAll', true)
        ->get('selectedUsers');

    expect($selected)->toContain((string) $winga->id)
        ->and($selected)->not->toContain((string) $admin->id);
});
