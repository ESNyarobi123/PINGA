<?php

use App\Livewire\Admin\Mazungumzo;
use App\Models\BroadcastMessage;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

it('queues in-app notifications to wingas when workers audience is selected', function () {
    Notification::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $winga = User::factory()->create(['role' => 'winga']);
    $mteja = User::factory()->create(['role' => 'mteja']);

    Livewire::actingAs($admin)
        ->test(Mazungumzo::class)
        ->set('activeTab', 'broadcasts')
        ->set('broadcastTitle', 'Test broadcast')
        ->set('broadcastMessage', 'Hello winga')
        ->set('broadcastType', 'announcement')
        ->set('targetAudience', ['workers'])
        ->call('sendBroadcast')
        ->assertHasNoErrors();

    Notification::assertSentTo($winga, AdminBroadcastNotification::class);
    Notification::assertNotSentTo($mteja, AdminBroadcastNotification::class);
    Notification::assertNotSentTo($admin, AdminBroadcastNotification::class);

    $broadcast = BroadcastMessage::query()->latest('id')->first();
    expect($broadcast)->not->toBeNull()
        ->and($broadcast->recipient_count)->toBe(1)
        ->and($broadcast->announcement_type)->toBe('announcement')
        ->and($broadcast->target_segments)->toBe(['wingas']);
});

it('sends to users with an active subscription when premium audience is selected', function () {
    Notification::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $subscribed = User::factory()->create(['role' => 'winga']);
    Subscription::query()->create([
        'user_id' => $subscribed->id,
        'plan' => 'premium',
        'amount_paid' => 100,
        'starts_at' => now()->subDay(),
        'expires_at' => now()->addMonth(),
        'status' => 'active',
    ]);
    $other = User::factory()->create(['role' => 'winga']);

    Livewire::actingAs($admin)
        ->test(Mazungumzo::class)
        ->set('broadcastTitle', 'Premium note')
        ->set('broadcastMessage', 'Subscribers only')
        ->set('broadcastType', 'info')
        ->set('targetAudience', ['premium'])
        ->call('sendBroadcast')
        ->assertHasNoErrors();

    Notification::assertSentTo($subscribed, AdminBroadcastNotification::class);
    Notification::assertNotSentTo($other, AdminBroadcastNotification::class);
});

it('sends to both clients and workers when both audiences are selected', function () {
    Notification::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $winga = User::factory()->create(['role' => 'winga']);
    $mteja = User::factory()->create(['role' => 'mteja']);

    Livewire::actingAs($admin)
        ->test(Mazungumzo::class)
        ->set('broadcastTitle', 'Everyone')
        ->set('broadcastMessage', 'Clients and workers')
        ->set('broadcastType', 'warning')
        ->set('targetAudience', ['clients', 'workers'])
        ->call('sendBroadcast')
        ->assertHasNoErrors();

    Notification::assertSentTo($winga, AdminBroadcastNotification::class);
    Notification::assertSentTo($mteja, AdminBroadcastNotification::class);

    $broadcast = BroadcastMessage::query()->latest('id')->first();
    expect($broadcast->target_segments)->toBe(['wateja', 'wingas'])
        ->and($broadcast->recipient_count)->toBe(2);
});
