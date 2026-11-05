<?php

namespace App\Livewire\Shared;

use App\Models\Conversation;
use Livewire\Component;

class Messages extends Component
{
    public ?int $activeConversationId = null;

    public array $conversations = [];

    public bool $showChatOnMobile = false;

    public function mount(?int $conversationId = null, ?int $serviceRequestId = null, ?int $userId = null): void
    {
        if ($serviceRequestId === null && request()->filled('serviceRequestId')) {
            $serviceRequestId = (int) request()->query('serviceRequestId');
        }

        if ($userId === null && request()->filled('userId')) {
            $userId = (int) request()->query('userId');
        }

        if ($conversationId) {
            $this->activeConversationId = $conversationId;
            $this->showChatOnMobile = true;
        }

        if ($serviceRequestId) {
            $this->openServiceRequestConversation($serviceRequestId);
        }

        if ($userId) {
            $this->openDirectConversation($userId);
        }

        $this->loadConversations();
    }

    /**
     * Find or create a direct conversation (no job/service) between the user and another member.
     */
    public function openDirectConversation(int $userId): void
    {
        $user = auth()->user();

        if ($user->id === $userId) {
            return;
        }

        $other = \App\Models\User::find($userId);

        if (! $other) {
            return;
        }

        $currentIsClient = $user->role === 'mteja';

        $conversation = Conversation::firstOrCreate([
            'job_id' => null,
            'service_request_id' => null,
            'employer_id' => $currentIsClient ? $user->id : $other->id,
            'worker_id' => $currentIsClient ? $other->id : $user->id,
        ]);

        $this->activeConversationId = $conversation->id;
        $this->showChatOnMobile = true;
    }

    /**
     * Resolve (or create) the conversation for a service request the user belongs to.
     */
    public function openServiceRequestConversation(int $serviceRequestId): void
    {
        $user = auth()->user();

        $request = \App\Models\ServiceRequest::query()
            ->with('service:id,user_id')
            ->find($serviceRequestId);

        if (! $request) {
            return;
        }

        $wingaId = (int) $request->service?->user_id;

        if (! in_array($user->id, [$request->client_id, $wingaId], true)) {
            return;
        }

        $conversation = Conversation::firstOrCreate([
            'service_request_id' => $request->id,
            'employer_id' => $request->client_id,
            'worker_id' => $wingaId,
        ]);

        $this->activeConversationId = $conversation->id;
        $this->showChatOnMobile = true;
    }

    public function loadConversations(): void
    {
        $user = auth()->user();

        $this->conversations = Conversation::query()
            ->forUser($user->id)
            ->with([
                'job:id,title,slug',
                'serviceRequest:id,service_id',
                'serviceRequest.service:id,title',
                'employer:id,name,avatar',
                'worker:id,name,avatar',
                'latestMessage',
            ])
            ->latest('updated_at')
            ->get()
            ->map(function ($conv) use ($user) {
                $other = $conv->employer_id === $user->id ? $conv->worker : $conv->employer;
                $isService = $conv->service_request_id !== null;

                return [
                    'id' => $conv->id,
                    'context_title' => $conv->contextLabel(),
                    'is_service' => $isService,
                    'other_name' => $other->name ?? '—',
                    'other_avatar' => $other && $other->avatar
                        ? asset('storage/'.$other->avatar)
                        : 'https://ui-avatars.com/api/?name='.urlencode($other->name ?? 'U').'&background=0d9488&color=fff&size=48',
                    'last_message' => $conv->latestMessage?->body ?? 'Bonyeza kuanza mazungumzo',
                    'last_time' => $conv->latestMessage?->created_at->diffForHumans() ?? '',
                    'unread' => $conv->unreadCount($user->id),
                ];
            })
            ->toArray();
    }

    public function selectConversation(int $id): void
    {
        $this->activeConversationId = $id;
        $this->showChatOnMobile = true;
    }

    public function backToList(): void
    {
        $this->showChatOnMobile = false;
    }

    public function render()
    {
        $user = auth()->user();
        $layout = match (true) {
            $user->isAdmin() => 'layouts.admin',
            $user->isMuajili() => 'layouts.mteja',
            default => 'layouts.winga',
        };

        return view('livewire.shared.messages')
            ->layout($layout)
            ->title('Mazungumzo Yangu');
    }
}
