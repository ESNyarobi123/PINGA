<?php

use App\Livewire\Winga\HudumaZangu;
use App\Livewire\Winga\PostHuduma;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;

test('winga can delete own service from huduma zangu', function () {
    if (! Schema::hasTable('service_packages')) {
        $this->markTestSkipped('service_packages table required');
    }

    $winga = User::factory()->create([
        'role' => 'winga',
        'onboarding_completed' => true,
    ]);

    $category = Category::query()->first();
    if ($category === null) {
        $category = Category::create([
            'name' => 'Cat WHM',
            'slug' => 'cat-whm-'.uniqid(),
            'is_active' => true,
        ]);
    }

    $service = Service::create([
        'user_id' => $winga->id,
        'category_id' => $category->id,
        'title' => 'Huduma ya kufutwa WHM',
        'description' => str_repeat('Maelezo ya kutosha kwa uhalali wa fomu. ', 5),
        'price' => 10000,
        'price_type' => 'fixed',
        'status' => 'active',
        'images' => null,
    ]);

    ServicePackage::create([
        'service_id' => $service->id,
        'title' => 'Msingi',
        'description' => null,
        'price' => 10000,
        'sort_order' => 0,
    ]);

    expect(Service::query()->whereKey($service->id)->exists())->toBeTrue();

    Livewire::actingAs($winga)
        ->test(HudumaZangu::class)
        ->call('deleteService', $service->id)
        ->assertDispatched('toast');

    expect(Service::query()->whereKey($service->id)->exists())->toBeFalse();
});

test('winga edit huduma page loads service into form', function () {
    if (! Schema::hasTable('service_packages')) {
        $this->markTestSkipped('service_packages table required');
    }

    $winga = User::factory()->create([
        'role' => 'winga',
        'onboarding_completed' => true,
    ]);

    $category = Category::query()->first();
    if ($category === null) {
        $category = Category::create([
            'name' => 'Cat WHM2',
            'slug' => 'cat-whm2-'.uniqid(),
            'is_active' => true,
        ]);
    }

    $service = Service::create([
        'user_id' => $winga->id,
        'category_id' => $category->id,
        'title' => 'Huduma ya kuhariri WHM',
        'description' => str_repeat('Maelezo ya kutosha kwa uhalali wa fomu. ', 5),
        'price' => 20000,
        'price_type' => 'fixed',
        'status' => 'active',
        'images' => null,
    ]);

    ServicePackage::create([
        'service_id' => $service->id,
        'title' => 'Kawaida',
        'description' => 'Kifuko',
        'price' => 20000,
        'sort_order' => 0,
    ]);

    Livewire::actingAs($winga)
        ->test(PostHuduma::class, ['service' => $service])
        ->assertSet('isEditing', true)
        ->assertSet('editingServiceId', $service->id)
        ->assertSet('title', 'Huduma ya kuhariri WHM');
});
