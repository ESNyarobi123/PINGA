<?php

namespace App\Livewire\Winga;

use App\Models\Service;
use App\Support\ServicePackageSchema;
use Livewire\Component;
use Livewire\WithPagination;

class HudumaZangu extends Component
{
    use WithPagination;

    public function render()
    {
        $with = ['category'];
        if (ServicePackageSchema::hasPackagesTable()) {
            $with['packages'] = fn ($q) => $q->orderBy('sort_order')->orderBy('id');
        }

        $services = Service::query()
            ->where('user_id', auth()->id())
            ->with($with)
            ->withCount([
                'serviceRequests as pending_requests_count' => fn ($q) => $q->where('status', 'pending'),
            ])
            ->latest()
            ->paginate(12);

        return view('livewire.winga.huduma-zangu', [
            'services' => $services,
            'usesServicePackages' => ServicePackageSchema::hasPackagesTable(),
        ])
            ->layout('layouts.winga')
            ->title(__('messages.huduma_zangu.title'));
    }
}
