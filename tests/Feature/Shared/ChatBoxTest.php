<?php

use App\Livewire\Shared\ChatBox;
use App\Models\Application;
use App\Models\Conversation;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

/**
 * @return array{0: User, 1: User, 2: Conversation}
 */
function acceptedJobConversation(): array
{
    $mteja = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);
    $winga = User::factory()->create(['role' => 'winga', 'onboarding_completed' => true]);

    $job = Job::factory()->create([
        'employer_id' => $mteja->id,
        'hired_worker_id' => $winga->id,
        'status' => 'in_progress',
    ]);

    Application::create([
        'job_id' => $job->id,
        'worker_id' => $winga->id,
        'cover_letter' => 'Nina uzoefu wa kutosha kwa kazi hii.',
        'proposed_budget' => 50000,
        'proposed_duration' => 'siku 3',
        'status' => 'accepted',
    ]);

    $conversation = Conversation::create([
        'job_id' => $job->id,
        'employer_id' => $mteja->id,
        'worker_id' => $winga->id,
    ]);

    return [$mteja, $winga, $conversation];
}

test('sending a chat message stores it trimmed and clears the composer', function () {
    [$mteja, , $conversation] = acceptedJobConversation();

    Livewire::actingAs($mteja)
        ->test(ChatBox::class, ['conversationId' => $conversation->id])
        ->set('newMessage', '  Habari yako, tunaanza lini?  ')
        ->call('sendMessage')
        ->assertSet('newMessage', '')
        ->assertHasNoErrors();

    expect(Message::count())->toBe(1);
    expect(Message::first()->body)->toBe('Habari yako, tunaanza lini?');
    expect(Message::first()->sender_id)->toBe($mteja->id);
});

test('an empty or whitespace-only message is rejected and nothing is stored', function () {
    [$mteja, , $conversation] = acceptedJobConversation();

    Livewire::actingAs($mteja)
        ->test(ChatBox::class, ['conversationId' => $conversation->id])
        ->set('newMessage', '    ')
        ->call('sendMessage')
        ->assertHasErrors(['newMessage'])
        ->assertSet('newMessage', '    ');

    expect(Message::count())->toBe(0);
});

test('the worker can reply in the same conversation', function () {
    [$mteja, $winga, $conversation] = acceptedJobConversation();

    Livewire::actingAs($mteja)
        ->test(ChatBox::class, ['conversationId' => $conversation->id])
        ->set('newMessage', 'Habari')
        ->call('sendMessage')
        ->assertHasNoErrors();

    Livewire::actingAs($winga)
        ->test(ChatBox::class, ['conversationId' => $conversation->id])
        ->set('newMessage', 'Sawa, ninaanza sasa')
        ->call('sendMessage')
        ->assertHasNoErrors();

    expect(Message::count())->toBe(2);
});

test('a user who is not part of the conversation cannot open it', function () {
    [, , $conversation] = acceptedJobConversation();
    $stranger = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);

    expect(function () use ($stranger, $conversation) {
        Livewire::actingAs($stranger)
            ->test(ChatBox::class, ['conversationId' => $conversation->id]);
    })->toThrow(ModelNotFoundException::class);
});
