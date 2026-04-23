<?php

namespace App\Notifications;

use App\Models\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class AdminBroadcastNotification extends Notification
{
    public function __construct(public BroadcastMessage $broadcast) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $kind = $this->broadcast->announcement_type ?? 'announcement';

        [$icon, $color] = match ($kind) {
            'maintenance' => ['wrench', 'amber'],
            'warning' => ['exclamation-triangle', 'red'],
            'info' => ['information-circle', 'blue'],
            default => ['megaphone', 'blue'],
        };

        return [
            'title' => $this->broadcast->title,
            'message' => Str::limit(strip_tags($this->broadcast->body), 500),
            'icon' => $icon,
            'color' => $color,
            'action_url' => null,
            'action_label' => null,
            'broadcast_id' => $this->broadcast->id,
        ];
    }
}
