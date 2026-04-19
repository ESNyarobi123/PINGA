<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public string $filter = 'all'; // all | unread

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function markAsRead(string $id): void
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        $notification?->markAsRead();
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->dispatch('toast', message: 'Arifa zote zimesomwa.', type: 'success');
    }

    public function delete(string $id): void
    {
        auth()->user()->notifications()->where('id', $id)->delete();
    }

    public function render()
    {
        $user  = auth()->user();
        $query = $user->notifications()->latest();

        if ($this->filter === 'unread') {
            $query = $user->unreadNotifications()->latest();
        }

        $unreadCount = $user->unreadNotifications()->count();

        return view('livewire.shared.notifications', [
            'notifications' => $query->paginate(20),
            'unreadCount'   => $unreadCount,
        ]);
    }
}
