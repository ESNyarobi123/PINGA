<?php

use App\Livewire\Winga\HudumaMaombi;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Livewire\Livewire;

function createServiceForWinga(User $winga): Service
{
    $category = Category::create(['name' => 'Test Category', 'slug' => 'test-'.uniqid()]);

    return Service::create([
        'user_id' => $winga->id,
        'category_id' => $category->id,
        'title' => 'Test Service',
        'description' => 'A test service',
        'price' => 50000,
        'status' => 'active',
    ]);
}

test('declining a service request opens modal and saves reason', function () {
    $winga = User::factory()->create(['role' => 'winga']);
    $mteja = User::factory()->create(['role' => 'mteja']);
    $service = createServiceForWinga($winga);

    $req = ServiceRequest::create([
        'service_id' => $service->id,
        'client_id' => $mteja->id,
        'message' => 'Nahitaji huduma yako',
        'status' => 'pending',
    ]);

    Livewire::actingAs($winga)
        ->test(HudumaMaombi::class)
        ->call('openDeclineModal', $req->id)
        ->assertSet('decliningRequestId', $req->id)
        ->set('declineReason', 'Sina uwezo wa kufanya kazi hii kwa sasa')
        ->call('confirmDecline')
        ->assertSet('decliningRequestId', null)
        ->assertSet('declineReason', '');

    $req->refresh();
    expect($req->status)->toBe('declined');
    expect($req->decline_reason)->toBe('Sina uwezo wa kufanya kazi hii kwa sasa');
});

test('decline requires reason with at least 5 characters', function () {
    $winga = User::factory()->create(['role' => 'winga']);
    $mteja = User::factory()->create(['role' => 'mteja']);
    $service = createServiceForWinga($winga);

    $req = ServiceRequest::create([
        'service_id' => $service->id,
        'client_id' => $mteja->id,
        'status' => 'pending',
    ]);

    Livewire::actingAs($winga)
        ->test(HudumaMaombi::class)
        ->call('openDeclineModal', $req->id)
        ->set('declineReason', 'Hi')
        ->call('confirmDecline')
        ->assertHasErrors(['declineReason' => 'min']);

    $req->refresh();
    expect($req->status)->toBe('pending');
});

test('cannot decline an already declined request', function () {
    $winga = User::factory()->create(['role' => 'winga']);
    $mteja = User::factory()->create(['role' => 'mteja']);
    $service = createServiceForWinga($winga);

    $req = ServiceRequest::create([
        'service_id' => $service->id,
        'client_id' => $mteja->id,
        'status' => 'declined',
        'decline_reason' => 'Already declined',
    ]);

    Livewire::actingAs($winga)
        ->test(HudumaMaombi::class)
        ->call('openDeclineModal', $req->id)
        ->set('declineReason', 'Trying again with new reason')
        ->call('confirmDecline');

    $req->refresh();
    expect($req->decline_reason)->toBe('Already declined');
});
