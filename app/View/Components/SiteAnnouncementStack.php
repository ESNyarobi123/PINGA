<?php

namespace App\View\Components;

use App\Models\SiteAnnouncement;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SiteAnnouncementStack extends Component
{
    public function __construct(public string $scope = SiteAnnouncement::AUDIENCE_PUBLIC) {}

    /**
     * @return Collection<int, SiteAnnouncement>
     */
    public function announcements(): Collection
    {
        return SiteAnnouncement::query()
            ->active()
            ->forAudience($this->scope)
            ->orderBy('id')
            ->get();
    }

    public function render(): View
    {
        return view('components.site-announcement-stack', [
            'announcements' => $this->announcements(),
        ]);
    }
}
