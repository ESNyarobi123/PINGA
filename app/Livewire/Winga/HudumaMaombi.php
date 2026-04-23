<?php

namespace App\Livewire\Winga;

use App\Models\ServiceRequest;
use App\Notifications\WingaNotification;
use App\Support\ServicePackageSchema;
use Livewire\Component;
use Livewire\WithPagination;

class HudumaMaombi extends Component
{
    use WithPagination;

    public string $filter = 'pending';

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $f = request()->query('filter');
        if (is_string($f) && in_array($f, ['all', 'pending', 'accepted', 'declined', 'in_progress', 'completed'], true)) {
            $this->filter = $f;
        }
    }

    public function accept(int $id): void
    {
        $req = $this->findOwnedRequest($id);
        if (! $req || ! $req->isPending()) {
            return;
        }

        $req->update(['status' => 'accepted', 'responded_at' => now()]);

        $req->client->notify(new WingaNotification(
            title: __('messages.huduma_maombi.notify_accepted_title'),
            message: __('messages.huduma_maombi.notify_accepted_body', [
                'worker' => auth()->user()->name,
                'title' => $req->service->title,
                'package' => ServicePackageSchema::hasPackagesTable() ? ($req->package?->title ?? '—') : '—',
            ]),
            icon: 'check-circle',
            color: 'green',
            action_url: route('mteja.huduma-malipo'),
            action_label: __('messages.huduma_maombi.notify_action_pay'),
        ));

        $this->dispatch('toast', message: __('messages.huduma_maombi.toast_accepted'), type: 'success');
    }

    public function decline(int $id): void
    {
        $req = $this->findOwnedRequest($id);
        if (! $req || ! $req->isPending()) {
            return;
        }

        $req->update(['status' => 'declined', 'responded_at' => now()]);

        $req->client->notify(new WingaNotification(
            title: __('messages.huduma_maombi.notify_declined_title'),
            message: __('messages.huduma_maombi.notify_declined_body', [
                'worker' => auth()->user()->name,
                'title' => $req->service->title,
                'package' => ServicePackageSchema::hasPackagesTable() ? ($req->package?->title ?? '—') : '—',
            ]),
            icon: 'x-circle',
            color: 'zinc',
        ));

        $this->dispatch('toast', message: __('messages.huduma_maombi.toast_declined'), type: 'success');
    }

    private function findOwnedRequest(int $id): ?ServiceRequest
    {
        return ServiceRequest::query()
            ->where('id', $id)
            ->whereHas('service', fn ($q) => $q->where('user_id', auth()->id()))
            ->with($this->requestDetailWith())
            ->first();
    }

    /**
     * @return array<int, string>
     */
    private function requestListWith(): array
    {
        $with = ['service.category', 'client:id,name,avatar', 'payment'];
        if (ServicePackageSchema::hasPackagesTable()) {
            $with[] = 'package';
        }

        return $with;
    }

    /**
     * @return array<int, string>
     */
    private function requestDetailWith(): array
    {
        $with = ['service', 'client', 'payment'];
        if (ServicePackageSchema::hasPackagesTable()) {
            $with[] = 'package';
        }

        return $with;
    }

    private function baseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return ServiceRequest::query()
            ->whereHas('service', fn ($s) => $s->where('user_id', auth()->id()));
    }

    public function render()
    {
        $query = $this->baseQuery()->with($this->requestListWith());

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $requests = $query->latest()->paginate(12);

        $counts = [
            'all' => $this->baseQuery()->count(),
            'pending' => $this->baseQuery()->where('status', 'pending')->count(),
            'accepted' => $this->baseQuery()->where('status', 'accepted')->count(),
            'declined' => $this->baseQuery()->where('status', 'declined')->count(),
            'in_progress' => $this->baseQuery()->where('status', 'in_progress')->count(),
            'completed' => $this->baseQuery()->where('status', 'completed')->count(),
        ];

        return view('livewire.winga.huduma-maombi', [
            'requests' => $requests,
            'counts' => $counts,
            'usesServicePackages' => ServicePackageSchema::hasPackagesTable(),
        ])
            ->layout('layouts.winga')
            ->title(__('messages.huduma_maombi.title'));
    }
}
