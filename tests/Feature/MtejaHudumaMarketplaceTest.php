<?php

use App\Livewire\Mteja\HudumaMarketplace;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\User;
use Livewire\Livewire;

test('mteja can open services marketplace and see listed services', function () {
    $winga = User::factory()->create([
        'role' => 'winga',
        'onboarding_completed' => true,
    ]);
    $mteja = User::factory()->create([
        'role' => 'mteja',
        'onboarding_completed' => true,
    ]);

    $category = Category::query()->first();
    if ($category === null) {
        $category = Category::create([
            'name' => 'Test Cat MP',
            'slug' => 'test-cat-mp-'.uniqid(),
            'is_active' => true,
        ]);
    }

    $service = Service::create([
        'user_id' => $winga->id,
        'category_id' => $category->id,
        'title' => 'Ukarabati wa WordPress Marketplace',
        'description' => str_repeat('Huduma ya ukarabati wa tovuti na WordPress. ', 4),
        'price' => 80000,
        'price_type' => 'fixed',
        'status' => 'active',
        'images' => null,
    ]);

    ServicePackage::create([
        'service_id' => $service->id,
        'title' => 'Standard',
        'description' => null,
        'price' => 80000,
        'sort_order' => 0,
    ]);

    $this->actingAs($mteja)->get(route('mteja.huduma'))->assertOk()->assertSee('Ukarabati wa WordPress Marketplace', false);

    Livewire::actingAs($mteja)
        ->test(HudumaMarketplace::class)
        ->set('search', 'WordPress')
        ->assertSee('Ukarabati wa WordPress Marketplace');
});
