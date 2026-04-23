<?php

namespace App\Livewire\Admin;

use App\Models\AdminAuditLog;
use App\Models\BroadcastMessage;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class Mazungumzo extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    public string $filterType = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $activeTab = 'conversations';

    // Conversation viewing
    public ?int $activeConversationId = null;

    public array $messages = [];

    public string $replyMessage = '';

    // Broadcast message
    public string $broadcastTitle = '';

    public string $broadcastMessage = '';

    public string $broadcastType = 'announcement';

    /** @var array<int, string> */
    public array $targetAudience = ['all'];

    // Sorting
    public string $sortField = 'updated_at';

    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterType' => ['except' => ''],
        'activeTab' => ['except' => 'conversations'],
        'sortField' => ['except' => 'updated_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage('conversationsPage');
    }

    public function viewConversation(int $id): void
    {
        $this->activeConversationId = $id;

        $conv = Conversation::with(['messages.sender:id,name,avatar'])->find($id);

        if (! $conv) {
            return;
        }

        // Mark as read by admin - not implemented since admin_read_at doesn't exist
        // $conv->update(['admin_read_at' => now()]);

        $this->messages = $conv->messages->map(fn ($msg) => [
            'id' => $msg->id,
            'body' => $msg->body,
            'sender_name' => $msg->sender->name ?? '—',
            'sender_avatar' => $msg->sender && $msg->sender->avatar
                ? asset('storage/'.$msg->sender->avatar)
                : 'https://ui-avatars.com/api/?name='.urlencode($msg->sender->name ?? 'U').'&background=8b5cf6&color=fff&size=40',
            'sender_type' => $this->getSenderType($msg->sender_id, $conv),
            'time' => $msg->created_at->format('d M Y, H:i'),
            'is_admin' => $msg->sender_id === auth()->id(),
        ])->toArray();
    }

    private function getSenderType(int $senderId, Conversation $conversation): string
    {
        if ($senderId === auth()->id()) {
            return 'admin';
        }
        if ($senderId === $conversation->employer_id) {
            return 'client';
        }
        if ($senderId === $conversation->worker_id) {
            return 'worker';
        }

        return 'unknown';
    }

    public function closeConversation(): void
    {
        $this->activeConversationId = null;
        $this->messages = [];
        $this->replyMessage = '';
    }

    public function sendReply(): void
    {
        if (! $this->activeConversationId || ! $this->replyMessage) {
            return;
        }

        $conversation = Conversation::find($this->activeConversationId);

        Message::create([
            'conversation_id' => $this->activeConversationId,
            'sender_id' => auth()->id(),
            'body' => $this->replyMessage,
        ]);

        $conversation->update(['updated_at' => now()]);

        $this->logAdminAction('admin_reply', $conversation, [
            'message_length' => strlen($this->replyMessage),
        ]);

        $this->replyMessage = '';
        $this->viewConversation($this->activeConversationId);

        $this->dispatch('toast', message: 'Reply sent successfully', type: 'success');
    }

    public function sendBroadcast(): void
    {
        $this->validate([
            'broadcastTitle' => 'required|string|max:255',
            'broadcastMessage' => 'required|string',
            'broadcastType' => 'required|in:announcement,maintenance,warning,info',
            'targetAudience' => 'required|array|min:1',
            'targetAudience.*' => 'in:all,clients,workers,premium',
        ]);

        $segments = BroadcastMessage::segmentsFromUiAudience($this->targetAudience);
        if ($segments === []) {
            $this->addError('targetAudience', __('Invalid target audience selection.'));

            return;
        }

        $targetType = BroadcastMessage::storageTargetTypeFromSegments($segments);

        try {
            $broadcast = BroadcastMessage::create([
                'title' => $this->broadcastTitle,
                'body' => $this->broadcastMessage,
                'announcement_type' => $this->broadcastType,
                'admin_id' => auth()->id(),
                'target_type' => $targetType,
                'target_segments' => $segments,
                'channels' => ['app'],
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $recipients = $broadcast->getRecipients();
            Notification::send($recipients, new AdminBroadcastNotification($broadcast));
            $broadcast->update(['recipient_count' => $recipients->count()]);
        } catch (\Illuminate\Database\QueryException $e) {
            report($e);
            $this->dispatch('toast', message: __('messages.admin_comms.broadcast_save_failed'), type: 'error');

            return;
        }

        try {
            $this->logAdminAction('send_broadcast', $broadcast, [
                'target_audience' => $this->targetAudience,
                'target_segments' => $segments,
                'type' => $this->broadcastType,
                'recipient_count' => $recipients->count(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        $this->reset(['broadcastTitle', 'broadcastMessage', 'broadcastType', 'targetAudience']);
        $this->resetPage('broadcastsPage');
        $this->dispatch('toast', message: __('messages.admin_comms.broadcast_sent'), type: 'success');
    }

    private function getConversationsQuery()
    {
        return Conversation::query()
            ->with([
                'employer:id,name,avatar',
                'worker:id,name,avatar',
                'job:id,title',
                'latestMessage',
            ])
            ->when($this->search, fn ($query) => $query
                ->where(function ($q) {
                    $q->whereHas('employer', fn ($sub) => $sub->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('worker', fn ($sub) => $sub->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('job', fn ($sub) => $sub->where('title', 'like', "%{$this->search}%"))
                        ->orWhereHas('messages', fn ($sub) => $sub->where('body', 'like', "%{$this->search}%"));
                })
            )
            ->when($this->filterStatus, fn ($query) => match ($this->filterStatus) {
                'active' => $query, // All conversations since no ended_at column
                'ended' => $query->whereRaw('1=0'), // Return none since no ended_at column
                'unread' => $query->whereRaw('1=0'), // Return none since no admin_read_at column
                default => $query,
            })
            ->when($this->filterType, fn ($query) => match ($this->filterType) {
                'dispute' => $query->whereHas('job.disputes'),
                'normal' => $query->whereDoesntHave('job.disputes'),
                default => $query,
            })
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    private function getBroadcastsQuery()
    {
        return BroadcastMessage::query()
            ->with(['admin:id,name'])
            ->when($this->dateFrom, fn ($query) => $query->whereDate('sent_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('sent_at', '<=', $this->dateTo))
            ->latest('sent_at');
    }

    public function getConversationsProperty()
    {
        return $this->getConversationsQuery()->paginate(25, ['*'], 'conversationsPage');
    }

    public function getBroadcastsProperty()
    {
        return $this->getBroadcastsQuery()->paginate(25, ['*'], 'broadcastsPage');
    }

    public function getUnreadCountProperty(): int
    {
        // Since there's no admin_read_at column, return 0 for now
        // This could be implemented by checking for messages after a certain date
        return 0;
    }

    public function getActiveCountProperty(): int
    {
        // Since there's no ended_at column, count all conversations
        return Conversation::count();
    }

    public function getDisputeCountProperty(): int
    {
        return Conversation::whereHas('job.disputes')->count();
    }

    public function getUnreadMessagesCount(Conversation $conversation): int
    {
        // Since there's no admin_read_at column, count all messages not from admin
        return $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->count();
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.mazungumzo', [
            'conversations' => $this->conversations,
            'broadcasts' => $this->broadcasts,
            'unreadCount' => $this->unreadCount,
            'activeCount' => $this->activeCount,
            'disputeCount' => $this->disputeCount,
        ])
            ->layout('layouts.admin')
            ->title('Communication Center');
    }
}
