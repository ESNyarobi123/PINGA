<?php

use App\Livewire\Mteja\KaziZangu;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Livewire\Livewire;

function createCategory(): Category
{
    return Category::create([
        'name' => 'Umeme',
        'slug' => 'umeme-'.uniqid(),
        'icon' => '⚡',
        'color' => 'amber',
    ]);
}

test('editJob loads localized title and description into form', function () {
    $user = User::factory()->create(['role' => 'mteja']);
    $category = createCategory();
    $job = Job::factory()->create([
        'employer_id' => $user->id,
        'title' => 'Umeme wa Nyumba',
        'title_en' => 'House Wiring',
        'description' => 'Mahitaji ya umeme',
        'description_en' => 'Electrical work needed',
        'category_id' => $category->id,
        'status' => 'open',
        'budget_min' => 5000,
        'location' => 'Dar',
    ]);

    app()->setLocale('en');

    $component = Livewire::actingAs($user)->test(KaziZangu::class);
    $component->call('editJob', $job->id);

    // In English locale, should load the English (localized) version
    $component->assertSet('editTitle', 'House Wiring');
    $component->assertSet('editDescription', 'Electrical work needed');
});

test('updateJob saves to both primary and translation fields', function () {
    $user = User::factory()->create(['role' => 'mteja']);
    $category = createCategory();
    $job = Job::factory()->create([
        'employer_id' => $user->id,
        'title' => 'Old Title',
        'title_en' => 'Old English',
        'description' => 'Old Desc',
        'description_en' => 'Old English Desc',
        'category_id' => $category->id,
        'status' => 'open',
        'budget_min' => 5000,
        'location' => 'Dar',
    ]);

    $component = Livewire::actingAs($user)->test(KaziZangu::class);
    $component->set('editTitle', 'New Title');
    $component->set('editDescription', 'New Description of the electrical work required here');
    $component->set('editCategoryId', (string) $category->id);
    $component->set('editLocation', 'Arusha');
    $component->set('editBudgetMin', 10000);
    $component->set('editingJobId', $job->id);

    $component->call('updateJob');

    $job->refresh();

    expect($job->title)->toBe('New Title');
    expect($job->title_en)->toBe('New Title');
    expect($job->description)->toBe('New Description of the electrical work required here');
    expect($job->description_en)->toBe('New Description of the electrical work required here');
    expect($job->is_approved)->toBeFalse();
});
