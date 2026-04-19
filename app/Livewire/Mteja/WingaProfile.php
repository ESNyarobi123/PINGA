<?php

namespace App\Livewire\Mteja;

use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\WingaNotification;
use App\Support\ServicePackageSchema;
use Livewire\Component;

class WingaProfile extends Component
{
    public int $id;

    public ?User $winga = null;

    public array $highlights = [];

    public bool $showRequestModal = false;

    public ?int $requestServiceId = null;

    public ?int $requestPackageId = null;

    public string $requestMessage = '';

    public function mount(int $id): void
    {
        $this->id = $id;

        $hasPackages = ServicePackageSchema::hasPackagesTable();

        $servicesWith = ['category'];
        if ($hasPackages) {
            $servicesWith['packages'] = fn ($pq) => $pq->orderBy('sort_order')->orderBy('id');
        }

        $this->winga = User::query()
            ->where('id', $id)
            ->where('role', 'winga')
            ->where('onboarding_completed', true)
            ->with([
                'skills',
                'portfolios',
                'reviewsReceived.reviewer',
                'activeSubscription.plan',
                'services' => fn ($q) => $q->where('status', 'active')->with($servicesWith)->orderBy('title'),
            ])
            ->withAvg('reviewsReceived', 'rating')
            ->withCount('reviewsReceived')
            ->firstOrFail();

        $this->highlights = $this->buildHighlights();

        $serviceParam = request()->query('service');
        if (is_numeric($serviceParam)) {
            $sid = (int) $serviceParam;
            $svc = $this->winga->services->firstWhere('id', $sid);
            if ($svc !== null) {
                if ($hasPackages && $svc->packages->isNotEmpty()) {
                    $this->openRequestModal($sid);
                } elseif (! $hasPackages) {
                    $this->openRequestModal($sid);
                }
            }
        }
    }

    public function openRequestModal(int $serviceId): void
    {
        $this->requestServiceId = $serviceId;
        $this->requestMessage = '';
        $service = $this->winga?->services->firstWhere('id', $serviceId);
        if (ServicePackageSchema::hasPackagesTable()) {
            $first = $service?->packages->first();
            $this->requestPackageId = $first?->id;
        } else {
            $this->requestPackageId = null;
        }
        $this->showRequestModal = true;
    }

    public function closeRequestModal(): void
    {
        $this->showRequestModal = false;
        $this->requestServiceId = null;
        $this->requestPackageId = null;
        $this->requestMessage = '';
    }

    public function submitServiceRequest(): void
    {
        $usesPackages = ServicePackageSchema::hasPackagesTable()
            && ServicePackageSchema::serviceRequestsHavePackageIdColumn();

        if ($usesPackages) {
            $this->validate([
                'requestPackageId' => 'required|integer|exists:service_packages,id',
                'requestMessage' => 'nullable|string|max:2000',
            ]);
        } else {
            $this->validate([
                'requestMessage' => 'nullable|string|max:2000',
            ]);
        }

        $user = auth()->user();
        if (! $user || ! $user->isMteja()) {
            $this->dispatch('toast', message: __('messages.huduma_request.must_be_client'), type: 'error');

            return;
        }

        $service = Service::query()
            ->where('id', $this->requestServiceId)
            ->where('user_id', $this->winga->id)
            ->where('status', 'active')
            ->first();

        if (! $service) {
            $this->dispatch('toast', message: __('messages.huduma_request.service_not_found'), type: 'error');

            return;
        }

        $package = null;
        if ($usesPackages) {
            $package = ServicePackage::query()
                ->where('id', $this->requestPackageId)
                ->where('service_id', $service->id)
                ->first();

            if (! $package) {
                $this->dispatch('toast', message: __('messages.huduma_request.package_invalid'), type: 'error');

                return;
            }
        }

        $duplicateQuery = ServiceRequest::query()
            ->where('service_id', $service->id)
            ->where('client_id', $user->id)
            ->where('status', 'pending');

        if ($usesPackages && $package) {
            $duplicateQuery->where('service_package_id', $package->id);
        }

        if ($duplicateQuery->exists()) {
            $this->dispatch('toast', message: __('messages.huduma_request.duplicate_pending'), type: 'error');

            return;
        }

        $payload = [
            'service_id' => $service->id,
            'client_id' => $user->id,
            'message' => $this->requestMessage !== '' ? $this->requestMessage : null,
            'status' => 'pending',
        ];
        if ($usesPackages && $package) {
            $payload['service_package_id'] = $package->id;
        }
        ServiceRequest::create($payload);

        $this->winga->notify(new WingaNotification(
            title: __('messages.huduma_request.notify_worker_title'),
            message: __('messages.huduma_request.notify_worker_body', [
                'client' => $user->name,
                'title' => $service->title,
                'package' => $package?->title ?? '—',
            ]),
            icon: 'inbox',
            color: 'blue',
            action_url: route('winga.huduma-maombi'),
            action_label: __('messages.huduma_request.notify_worker_action'),
        ));

        $this->closeRequestModal();
        $this->dispatch('toast', message: __('messages.huduma_request.sent'), type: 'success');
    }

    private function buildHighlights(): array
    {
        $highlights = [];
        $user = $this->winga;

        $plan = $user->activeSubscription?->plan;
        if ($plan) {
            $highlights['plan'] = [
                'name' => $plan->name,
                'slug' => $plan->slug,
                'class' => match ($plan->slug) {
                    'bora' => 'bg-gradient-to-r from-amber-500 to-orange-500 text-white',
                    'kawaida' => 'bg-gradient-to-r from-sky-500 to-cyan-500 text-white',
                    default => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
                },
            ];
        }

        if ($user->is_verified) {
            $highlights['verified'] = [
                'label' => 'Imethibitishwa',
                'icon' => '✓',
                'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
            ];
        }

        if ($user->is_top_rated) {
            $rating = round($user->reviews_received_avg_rating ?? 0, 1);
            $highlights['top_rated'] = [
                'label' => 'Top Rated ⭐ '.$rating,
                'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            ];
        }

        return $highlights;
    }

    public function render()
    {
        return view('livewire.mteja.winga-profile', [
            'highlights' => $this->highlights,
            'usesServicePackages' => ServicePackageSchema::hasPackagesTable(),
        ])
            ->layout('layouts.mteja')
            ->title($this->winga?->name ?? 'Mfanyakazi');
    }
}
