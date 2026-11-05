<?php

use App\Livewire\Shared\ChatBox;
use App\Livewire\Shared\Messages;
use App\Livewire\Winga\HudumaMaombi;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Livewire\Livewire;

/**
 * @return array{0: User, 1: User, 2: ServiceRequest}
 */
function serviceRequestBetweenClientAndWinga(): array
{
    $winga = User::factory()->create(['role' => 'winga', 'onboarding_completed' => true]);
    $mteja = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);

    $category = Category::create(['name' => 'Umeme', 'slug' => 'umeme-'.uniqid()]);

    $service = Service::create([
        'user_id' => $winga->id,
        'category_id' => $category->id,
        'title' => 'Ufungaji wa umeme',
        'description' => 'Huduma ya ufungaji wa umeme nyumbani',
        'price' => 50000,
        'status' => 'active',
    ]);

    $request = ServiceRequest::create([
        'service_id' => $service->id,
        'client_id' => $mteja->id,
        'message' => 'Nahitaji huduma hii',
        'status' => 'pending',
    ]);

    return [$mteja, $winga, $request];
}

test('accepting a service request creates a conversation between client and winga', function () {
    [$mteja, $winga, $request] = serviceRequestBetweenClientAndWinga();

    Livewire::actingAs($winga)
        ->test(HudumaMaombi::class)
        ->call('accept', $request->id)
        ->assertHasNoErrors();

    $conversation = Conversation::where('service_request_id', $request->id)->first();

    expect($conversation)->not->toBeNull();
    expect($conversation->job_id)->toBeNull();
    expect($conversation->employer_id)->toBe($mteja->id);
    expect($conversation->worker_id)->toBe($winga->id);
    expect($conversation->contextLabel())->toBe('Ufungaji wa umeme');
});

test('both parties can exchange messages on a service conversation', function () {
    [$mteja, $winga, $request] = serviceRequestBetweenClientAndWinga();

    Livewire::actingAs($winga)
        ->test(HudumaMaombi::class)
        ->call('accept', $request->id);

    $conversation = Conversation::where('service_request_id', $request->id)->firstOrFail();

    Livewire::actingAs($mteja)
        ->test(ChatBox::class, ['conversationId' => $conversation->id])
        ->set('newMessage', 'Habari, bei ni 50,000?')
        ->call('sendMessage')
        ->assertHasNoErrors();

    Livewire::actingAs($winga)
        ->test(ChatBox::class, ['conversationId' => $conversation->id])
        ->set('newMessage', 'Ndio, bei ni hiyo.')
        ->call('sendMessage')
        ->assertHasNoErrors();

    expect(Message::count())->toBe(2);
});

test('opening messages with a serviceRequestId selects the matching conversation', function () {
    [$mteja, $winga, $request] = serviceRequestBetweenClientAndWinga();

    Livewire::actingAs($winga)
        ->test(HudumaMaombi::class)
        ->call('accept', $request->id);

    $conversation = Conversation::where('service_request_id', $request->id)->firstOrFail();

    Livewire::actingAs($mteja)
        ->withQueryParams(['serviceRequestId' => $request->id])
        ->test(Messages::class)
        ->assertSet('activeConversationId', $conversation->id);
});

test('a client can start a direct chat from a winga profile', function () {
    $mteja = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);
    $winga = User::factory()->create(['role' => 'winga', 'onboarding_completed' => true]);

    Livewire::actingAs($mteja)
        ->withQueryParams(['userId' => $winga->id])
        ->test(Messages::class)
        ->assertSet('activeConversationId', fn ($id) => $id !== null);

    expect(Conversation::where('employer_id', $mteja->id)
        ->where('worker_id', $winga->id)
        ->whereNull('job_id')
        ->whereNull('service_request_id')
        ->exists())->toBeTrue();
});
