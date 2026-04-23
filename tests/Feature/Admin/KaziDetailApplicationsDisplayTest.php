<?php

use App\Livewire\Admin\KaziDetail;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Livewire\Livewire;

test('admin job detail lists applicant worker name and proposed budget', function () {
    $admin = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
    $employer = User::factory()->create(['role' => 'mteja']);
    $worker = User::factory()->create(['role' => 'winga', 'name' => 'Ali Mfanyakazi']);

    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'is_approved' => true,
    ]);

    Application::query()->create([
        'job_id' => $job->id,
        'worker_id' => $worker->id,
        'cover_letter' => 'Nataka kazi hii',
        'proposed_budget' => 250000,
        'proposed_duration' => '1 day',
        'status' => 'accepted',
    ]);

    Livewire::actingAs($admin)
        ->test(KaziDetail::class, ['id' => $job->id])
        ->assertSee('Ali Mfanyakazi', false)
        ->assertSee('250,000', false);
});

test('admin reset completion code stores plain six digit code not bcrypt', function () {
    $admin = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
    $employer = User::factory()->create(['role' => 'mteja']);

    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'completion_code' => '$2y$12$legacyhashplaceholder012345678901234567890',
    ]);

    Livewire::actingAs($admin)
        ->test(KaziDetail::class, ['id' => $job->id])
        ->call('resetCompletionCode');

    $code = $job->fresh()->completion_code;
    expect($code)->not->toStartWith('$2y$')
        ->and(strlen((string) $code))->toBe(6)
        ->and(ctype_digit((string) $code))->toBeTrue();
});
