<?php

namespace App\Livewire\Shared;

use App\Models\SiteAnnouncement;
use Livewire\Component;

class AnnouncementModal extends Component
{
    public string $scope = SiteAnnouncement::AUDIENCE_MTEJA;

    public ?int $announcementId = null;

    public ?SiteAnnouncement $announcement = null;

    public bool $show = false;

    public function mount(string $scope): void
    {
        $this->scope = $scope;
        $this->loadNext();
    }

    private function loadNext(): void
    {
        $user = auth()->user();

        if (! $user) {
            $this->reset(['announcement', 'announcementId', 'show']);

            return;
        }

        $next = SiteAnnouncement::query()
            ->active()
            ->forAudience($this->scope)
            ->whereDoesntHave('users', function ($q) use ($user) {
                $q->where('users.id', $user->id)
                    ->whereNotNull('site_announcement_user.dismissed_at');
            })
            ->orderBy('id')
            ->first();

        if ($next === null) {
            $this->reset(['announcement', 'announcementId', 'show']);

            return;
        }

        $this->announcement = $next;
        $this->announcementId = $next->id;
        $this->show = true;

        $next->users()->syncWithoutDetaching([
            $user->id => ['viewed_at' => now()],
        ]);
    }

    public function dismiss(): void
    {
        $user = auth()->user();

        if (! $user || $this->announcement === null) {
            return;
        }

        if (! $this->announcement->is_dismissible) {
            return;
        }

        $this->announcement->users()->syncWithoutDetaching([
            $user->id => [
                'viewed_at' => now(),
                'dismissed_at' => now(),
            ],
        ]);

        $this->loadNext();
    }

    public function render()
    {
        return view('livewire.shared.announcement-modal');
    }
}
