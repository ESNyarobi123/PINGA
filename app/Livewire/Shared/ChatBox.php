<?php

namespace App\Livewire\Shared;

use App\Models\Application;
use App\Models\Conversation;
use App\Models\Job;
use App\Models\Message;
use App\Models\ServiceRequest;
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

    public ?int $serviceRequestId = null;

    public ?int $otherUserId = null;

    public function mount(?int $conversationId = null, ?int $jobId = null, ?int $otherUserId = null, ?int $serviceRequestId = null): void
    {
        $this->conversationId = $conversationId;
        $this->jobId = $jobId;
        $this->otherUserId = $otherUserId;
        $this->serviceRequestId = $serviceRequestId;

        if ($this->conversationId) {
            $this->openConversation($this->conversationId);
        }
    }

    public function openConversation(int $conversationId): void
    {
        $user = auth()->user();
        $this->conversation = Conversation::query()
            ->forUser($user->id)
            ->whereKey($conversationId)
            ->with(['job', 'serviceRequest.service', 'employer', 'worker'])
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

    /**
     * Start or open the conversation attached to a service request (client + winga).
     */
    public function startOrOpenServiceConversation(int $serviceRequestId): void
    {
        $user = auth()->user();

        $request = ServiceRequest::query()
            ->with('service:id,user_id,title')
            ->findOrFail($serviceRequestId);

        $wingaId = (int) $request->service->user_id;

        if (! in_array($user->id, [$request->client_id, $wingaId], true)) {
            abort(403);
        }

        $conversation = Conversation::firstOrCreate([
            'service_request_id' => $request->id,
            'employer_id' => $request->client_id,
            'worker_id' => $wingaId,
        ]);

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
     * Phone numbers are only visible once the work is paid into escrow.
     */
    protected function shouldMaskPhoneNumbers(): bool
    {
        if (! $this->conversation) {
            return true;
        }

        if ($this->conversation->service_request_id) {
            $payment = $this->conversation->serviceRequest?->payment;

            return ! $payment || ! in_array($payment->status, ['escrowed', 'released'], true);
        }

        // Direct member-to-member chat: keep phone numbers hidden until a paid job exists.
        if (! $this->conversation->job_id) {
            return true;
        }

        return ! Application::query()
            ->where('job_id', $this->conversation->job_id)
            ->where('worker_id', $this->conversation->worker_id)
            ->where('status', 'accepted')
            ->exists();
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
        if (! $this->conversation) {
            return;
        }

        $body = trim($this->newMessage);

        if ($body === '') {
            $this->resetValidation();
            $this->addError('newMessage', __('messages.chat_box.empty_message'));

            return;
        }

        $this->validate(['newMessage' => 'required|string|max:2000']);

        $user = auth()->user();

        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => $user->id,
            'body' => $body,
        ]);

        // Notify the other person
        $other = $this->conversation->otherParty($user->id);
        if ($other) {
            $other->notify(new WingaNotification(
                title: 'Ujumbe mpya kutoka '.$user->name,
                message: substr($body, 0, 80).(strlen($body) > 80 ? '...' : ''),
                icon: 'chat-bubble-left-right',
                color: 'blue',
                action_url: route('messages.conversation', ['conversationId' => $this->conversation->id]),
                action_label: 'Soma Ujumbe',
            ));
        }

        $this->newMessage = '';
        $this->resetValidation();
        $this->conversation->markAsRead($user->id);
        $this->loadMessages();
        $this->dispatch('chat-message-sent');
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
