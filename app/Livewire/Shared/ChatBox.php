<?php

namespace App\Livewire\Shared;

use App\Models\Conversation;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use App\Notifications\WingaNotification;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatBox extends Component
{
    use WithFileUploads;

    public ?int $conversationId = null;

    public ?Conversation $conversation = null;

    public string $newMessage = '';

    public bool $showChat = false;

    public array $messages = [];

    // For starting a new conversation
    public ?int $jobId = null;

    public ?int $otherUserId = null;

    public function mount(?int $conversationId = null, ?int $jobId = null, ?int $otherUserId = null): void
    {
        $this->conversationId = $conversationId;
        $this->jobId = $jobId;
        $this->otherUserId = $otherUserId;

        if ($this->conversationId) {
            $this->openConversation($this->conversationId);
        }
    }

    public function openConversation(int $conversationId): void
    {
        $user = auth()->user();
        $this->conversation = Conversation::where('id', $conversationId)
            ->where(function ($q) use ($user) {
                $q->where('employer_id', $user->id)->orWhere('worker_id', $user->id);
            })
            ->with(['job', 'employer', 'worker'])
            ->firstOrFail();

        $this->conversationId = $conversationId;
        $this->conversation->markAsRead($user->id);
        $this->loadMessages();
        $this->showChat = true;
    }

    /**
     * Start or open a conversation between employer and worker for a job.
     */
    public function startOrOpenConversation(int $jobId, int $otherUserId): void
    {
        $user = auth()->user();
        $job = Job::findOrFail($jobId);

        // Determine roles
        $employerId = $job->employer_id;
        $workerId = $user->id === $employerId ? $otherUserId : $user->id;

        $conversation = Conversation::firstOrCreate(
            ['job_id' => $jobId, 'employer_id' => $employerId, 'worker_id' => $workerId],
        );

        $this->openConversation($conversation->id);
    }

    public function loadMessages(): void
    {
        if (! $this->conversation) {
            return;
        }

        // Check if phone numbers should be masked
        $shouldMaskPhoneNumbers = $this->shouldMaskPhoneNumbers();

        $this->messages = $this->conversation->messages()
            ->with('sender:id,name,avatar')
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->map(fn ($m) => [
                'id' => $m->id,
                'body' => $shouldMaskPhoneNumbers ? $this->maskPhoneNumbers($m->body) : $m->body,
                'sender_id' => $m->sender_id,
                'sender_name' => $m->sender->name,
                'sender_avatar' => $m->sender->avatar
                    ? asset('storage/'.$m->sender->avatar)
                    : 'https://ui-avatars.com/api/?name='.urlencode($m->sender->name).'&background=0d9488&color=fff&size=48',
                'is_mine' => $m->sender_id === auth()->id(),
                'time' => $m->created_at->format('H:i'),
                'date' => $m->created_at->diffForHumans(),
            ])
            ->values()
            ->toArray();
    }

    /**
     * Check if phone numbers should be masked in this conversation.
     * Phone numbers are only visible if the worker has an accepted application for this job.
     */
    protected function shouldMaskPhoneNumbers(): bool
    {
        if (! $this->conversation) {
            return true;
        }

        // Check if there's an accepted application for this job and worker
        $hasAcceptedApplication = \App\Models\Application::where('job_id', $this->conversation->job_id)
            ->where('worker_id', $this->conversation->worker_id)
            ->where('status', 'accepted')
            ->exists();

        return ! $hasAcceptedApplication;
    }

    /**
     * Mask phone numbers in message body.
     * Masks formats: +255678165524, 0678165524, 678165524
     */
    protected function maskPhoneNumbers(string $text): string
    {
        // Pattern to match Tanzanian phone numbers in various formats
        $patterns = [
            '/\+255\d{9}/',           // +255678165524
            '/\b0\d{9}\b/',           // 0678165524
            '/\b[67]\d{8}\b/',        // 678165524 or 756123456
        ];

        foreach ($patterns as $pattern) {
            $text = preg_replace_callback($pattern, function ($matches) {
                return '[Namba imefichwa]';
            }, $text);
        }

        return $text;
    }

    public function sendMessage(): void
    {
        $this->validate(['newMessage' => 'required|string|max:2000']);

        if (! $this->conversation) {
            return;
        }

        $user = auth()->user();

        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $user->id,
            'body' => $this->newMessage,
        ]);

        // Notify the other person
        $otherId = $this->conversation->employer_id === $user->id
            ? $this->conversation->worker_id
            : $this->conversation->employer_id;

        $other = User::find($otherId);
        if ($other) {
            $other->notify(new WingaNotification(
                title: 'Ujumbe mpya kutoka '.$user->name,
                message: substr($this->newMessage, 0, 80).(strlen($this->newMessage) > 80 ? '...' : ''),
                icon: 'chat-bubble-left-right',
                color: 'blue',
                action_url: url('/messages/'.$this->conversation->id),
                action_label: 'Soma Ujumbe',
            ));
        }

        $this->newMessage = '';
        $this->conversation->markAsRead($user->id);
        $this->loadMessages();
    }

    public function poll(): void
    {
        if ($this->conversation) {
            $this->loadMessages();
        }
    }

    public function render()
    {
        return view('livewire.shared.chat-box');
    }
}
