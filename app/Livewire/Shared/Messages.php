<?php

namespace App\Livewire\Shared;

use App\Models\Conversation;
use Livewire\Component;

class Messages extends Component
{
    public ?int $activeConversationId = null;

    public array $conversations = [];

    public bool $showChatOnMobile = false;

    public function mount(?int $conversationId = null): void
    {
        $this->activeConversationId = $conversationId;
        $this->loadConversations();
    }

    public function loadConversations(): void
    {
        $user = auth()->user();

        $this->conversations = Conversation::where(function ($q) use ($user) {
            $q->where('employer_id', $user->id)->orWhere('worker_id', $user->id);
        })
            ->with(['job:id,title,slug', 'employer:id,name,avatar', 'worker:id,name,avatar', 'latestMessage'])
            ->latest('updated_at')
            ->get()
            ->map(function ($conv) use ($user) {
                $other = $conv->employer_id === $user->id ? $conv->worker : $conv->employer;

                return [
                    'id' => $conv->id,
                    'job_title' => $conv->job->title ?? '—',
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
