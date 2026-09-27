<?php

use App\Livewire\Admin\Kategoria;
use App\Livewire\Mteja\HudumaMarketplace;
use App\Livewire\Mteja\KaziZangu;
use App\Livewire\Mteja\PostKazi;
use App\Models\Category;
use App\Models\Job;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\User;
use Livewire\Livewire;

test('post kazi only provides active categories and rejects deactivated category', function () {
    $mteja = User::factory()->create([
        'role' => 'mteja',
        'onboarding_completed' => true,
    ]);

    $activeCat = Category::create([
        'name' => 'Active Category Test',
        'slug' => 'active-category-test-'.uniqid(),
        'is_active' => true,
    ]);

    $inactiveCat = Category::create([
        'name' => 'Inactive Category Test',
        'slug' => 'inactive-category-test-'.uniqid(),
        'is_active' => false,
    ]);

    Livewire::actingAs($mteja)
        ->test(PostKazi::class)
        ->assertViewHas('categories', function ($categories) use ($activeCat, $inactiveCat) {
            return $categories->contains('id', $activeCat->id)
                && ! $categories->contains('id', $inactiveCat->id);
        })
        ->set('title', 'Kazi ya Kupaka Rangi')
        ->set('description', 'Hii ni kazi ya kupaka rangi nyumba nzima vizuri kabisa bila shida yoyote.')
        ->set('category_id', (string) $inactiveCat->id)
        ->set('location', 'Kinondoni, Dar es Salaam')
        ->set('budget_min', 50000)
        ->call('submit')
        ->assertHasErrors(['category_id']);

    // Now with active category, it succeeds
    Livewire::actingAs($mteja)
        ->test(PostKazi::class)
        ->set('title', 'Kazi ya Kupaka Rangi Halisi')
        ->set('description', 'Hii ni kazi ya kupaka rangi nyumba nzima vizuri kabisa bila shida yoyote.')
        ->set('category_id', (string) $activeCat->id)
        ->set('location', 'Kinondoni, Dar es Salaam')
        ->set('budget_min', 50000)
        ->call('submit')
        ->assertHasNoErrors();

    expect(Job::where('title', 'Kazi ya Kupaka Rangi Halisi')->exists())->toBeTrue();
});

test('kazi zangu edit only provides active categories and rejects deactivated category', function () {
    $mteja = User::factory()->create([
        'role' => 'mteja',
        'onboarding_completed' => true,
    ]);

    $activeCat = Category::create([
        'name' => 'Active Edit Cat',
        'slug' => 'active-edit-cat-'.uniqid(),
        'is_active' => true,
    ]);

    $inactiveCat = Category::create([
        'name' => 'Inactive Edit Cat',
        'slug' => 'inactive-edit-cat-'.uniqid(),
        'is_active' => false,
    ]);

    $job = Job::create([
        'employer_id' => $mteja->id,
        'title' => 'Kazi ya Majaribio ya Uhariri',
        'description' => 'Maelezo ya kazi ya majaribio ya uhariri wa mteja.',
        'category_id' => $activeCat->id,
        'location' => 'Ilala, Dar',
        'budget_type' => 'fixed',
        'budget_min' => 20000,
        'budget_max' => 30000,
        'status' => 'open',
    ]);

    Livewire::actingAs($mteja)
        ->test(KaziZangu::class)
        ->assertViewHas('categories', function ($categories) use ($activeCat, $inactiveCat) {
            return $categories->contains('id', $activeCat->id)
                && ! $categories->contains('id', $inactiveCat->id);
        })
        ->call('editJob', $job->id)
        ->set('editCategoryId', (string) $inactiveCat->id)
        ->call('updateJob')
        ->assertHasErrors(['editCategoryId']);
});

test('huduma marketplace does not display services from deactivated categories', function () {
    $winga = User::factory()->create([
        'role' => 'winga',
        'onboarding_completed' => true,
    ]);
    $mteja = User::factory()->create([
        'role' => 'mteja',
        'onboarding_completed' => true,
    ]);

    $inactiveCat = Category::create([
        'name' => 'Deactivated Service Cat',
        'slug' => 'deactivated-service-cat-'.uniqid(),
        'is_active' => false,
    ]);

    $service = Service::create([
        'user_id' => $winga->id,
        'category_id' => $inactiveCat->id,
        'title' => 'Huduma Iliyozimwa Kategoria',
        'description' => 'Maelezo ya huduma hii haitakiwi kuonekana kwa mteja.',
        'price' => 45000,
        'price_type' => 'fixed',
        'status' => 'active',
    ]);

    ServicePackage::create([
        'service_id' => $service->id,
        'title' => 'Standard',
        'price' => 45000,
        'sort_order' => 0,
    ]);

    Livewire::actingAs($mteja)
        ->test(HudumaMarketplace::class)
        ->assertDontSee('Huduma Iliyozimwa Kategoria');
});

test('admin can export categories to csv', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'onboarding_completed' => true,
    ]);

    Category::create([
        'name' => 'Kategoria ya Export',
        'slug' => 'kategoria-ya-export-'.uniqid(),
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(Kategoria::class)
        ->call('exportCategories')
        ->assertFileDownloaded();
});
