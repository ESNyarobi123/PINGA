<?php

namespace App\Livewire\Winga;

use App\Models\Service;
use App\Support\ServicePackageSchema;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class HudumaZangu extends Component
{
    use WithPagination;

    public function deleteService(int $serviceId): void
    {
        $user = auth()->user();
        $service = Service::query()->where('user_id', $user->id)->whereKey($serviceId)->first();
        if (! $service) {
            $this->dispatch('toast', message: __('messages.huduma_zangu.delete_not_found'), type: 'error');

            return;
        }

        foreach ($service->images ?? [] as $path) {
            if (is_string($path) && $path !== '') {
                Storage::disk('public')->delete($path);
            }
        }

        $service->delete();

        $this->dispatch('toast', message: __('messages.huduma_zangu.deleted'), type: 'success');
    }

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
