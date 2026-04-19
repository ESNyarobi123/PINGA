<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    public bool $showPanel = false;

    public array $notifications = [];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }

        $this->notifications = $user->notifications()
            ->latest()
            ->limit(15)
            ->get()
            ->map(fn ($n) => [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Arifa',
                'message' => $n->data['message'] ?? '',
                'icon' => $n->data['icon'] ?? 'bell',
                'color' => $n->data['color'] ?? 'blue',
                'action_url' => $n->data['action_url'] ?? null,
                'action_label' => $n->data['action_label'] ?? null,
                'read_at' => $n->read_at,
                'time' => $n->created_at->diffForHumans(),
            ])
            ->toArray();

        $this->unreadCount = $user->unreadNotifications()->count();
    }

    public function togglePanel(): void
    {
        $this->showPanel = ! $this->showPanel;
        if ($this->showPanel) {
            $this->loadNotifications();
        }
    }

    public function markAsRead(string $notificationId): void
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        $this->loadNotifications();
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
        $this->dispatch('toast', message: 'Arifa zote zimesomwa.', type: 'success');
    }

    // Livewire polling every 15 seconds — simulates real-time
    public function poll(): void
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.shared.notification-bell');
    }
}
