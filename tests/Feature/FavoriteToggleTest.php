<?php

use App\Models\Favorite;
use App\Models\Job;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guest clicking favorite on job card redirects to login', function () {
    Livewire::test(\App\Livewire\Public\TafutaKazi::class)
        ->call('toggleFavorite', 1)
        ->assertRedirect(route('login'));
});

test('authenticated user can favorite a job', function () {
    $user = User::factory()->create();
    $job = Job::factory()->create(['status' => 'open', 'is_approved' => true]);

    $this->actingAs($user);

    expect(Favorite::count())->toBe(0);

    Livewire::test(\App\Livewire\Public\TafutaKazi::class)
        ->call('toggleFavorite', $job->id)
        ->assertSet('favoritedJobIds', [$job->id]);

    expect(Favorite::count())->toBe(1);
    expect(Favorite::first()->favorable_type)->toBe(Job::class);
    expect(Favorite::first()->favorable_id)->toBe($job->id);
});

test('authenticated user can unfavorite a job', function () {
    $user = User::factory()->create();
    $job = Job::factory()->create(['status' => 'open', 'is_approved' => true]);

    Favorite::create([
        'user_id' => $user->id,
        'favorable_type' => Job::class,
        'favorable_id' => $job->id,
    ]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Public\TafutaKazi::class)
        ->call('toggleFavorite', $job->id);

    expect(Favorite::count())->toBe(0);
});

test('guest clicking favorite on worker card redirects to login', function () {
    Livewire::test(\App\Livewire\Public\TafutaWafanyakazi::class)
        ->call('toggleFavorite', 1)
        ->assertRedirect(route('login'));
});

test('authenticated user can favorite a worker', function () {
    $user = User::factory()->create();
    $worker = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Public\TafutaWafanyakazi::class)
        ->call('toggleFavorite', $worker->id)
        ->assertSet('favoritedWorkerIds', [$worker->id]);

    expect(Favorite::count())->toBe(1);
    expect(Favorite::first()->favorable_type)->toBe(User::class);
});

test('authenticated user can unfavorite a worker', function () {
    $user = User::factory()->create();
    $worker = User::factory()->create();

    Favorite::create([
        'user_id' => $user->id,
        'favorable_type' => User::class,
        'favorable_id' => $worker->id,
    ]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Public\TafutaWafanyakazi::class)
        ->call('toggleFavorite', $worker->id);

    expect(Favorite::count())->toBe(0);
});
