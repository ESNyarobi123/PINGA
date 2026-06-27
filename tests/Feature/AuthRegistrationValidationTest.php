<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Livewire\Livewire;

it('shows a friendly error when email is already taken on step one', function () {
    User::factory()->create([
        'email' => 'taken@example.com',
        'phone' => '0711111111',
    ]);

    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'taken@example.com')
        ->set('phone', '0722222222')
        ->set('mkoa', 'Dar es Salaam')
        ->call('nextStep')
        ->assertHasErrors(['email' => __('messages.auth.email_taken')]);
});

it('shows a friendly error when phone is already taken on step one', function () {
    User::factory()->create([
        'email' => 'existing@example.com',
        'phone' => '0712345678',
    ]);

    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'new@example.com')
        ->set('phone', '0712345678')
        ->set('mkoa', 'Dar es Salaam')
        ->call('nextStep')
        ->assertHasErrors(['phone' => __('messages.auth.phone_taken')]);
});

it('revalidates email and returns to step one on final submit', function () {
    User::factory()->create([
        'email' => 'dup@example.com',
        'phone' => '0711111111',
    ]);

    Livewire::test(Register::class)
        ->set('step', 2)
        ->set('name', 'Test User')
        ->set('email', 'dup@example.com')
        ->set('phone', '0722222222')
        ->set('mkoa', 'Dar es Salaam')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('role', 'mteja')
        ->call('register')
        ->assertHasErrors(['email'])
        ->assertSet('step', 1);
});

it('rejects email addresses entered as phone numbers', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'new@example.com')
        ->set('phone', 'winga@gmail.com')
        ->set('mkoa', 'Dar es Salaam')
        ->call('nextStep')
        ->assertHasErrors(['phone']);
});

it('normalizes international phone numbers before uniqueness check', function () {
    User::factory()->create([
        'email' => 'existing@example.com',
        'phone' => '0744000001',
    ]);

    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'another@example.com')
        ->set('phone', '+255744000001')
        ->set('mkoa', 'Dar es Salaam')
        ->call('nextStep')
        ->assertHasErrors(['phone']);
});
