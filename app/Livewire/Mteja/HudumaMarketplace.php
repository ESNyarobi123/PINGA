<?php

namespace App\Livewire\Mteja;

use App\Models\Category;
use App\Models\Service;
use App\Support\ServicePackageSchema;
use Livewire\Component;
use Livewire\WithPagination;

class HudumaMarketplace extends Component
{
    use WithPagination;

    public string $search = '';

    public string $categoryId = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $hasPackages = ServicePackageSchema::hasPackagesTable();

        $query = Service::query()
            ->where('status', 'active')
            ->whereHas('user', fn ($q) => $q->where('role', 'winga')->where('onboarding_completed', true));

        if ($hasPackages) {
            $query->whereHas('packages');
        }

        $with = [
            'user:id,name,avatar,mkoa,wilaya,mtaa',
            'category:id,name',
        ];
        if ($hasPackages) {
            $with['packages'] = fn ($pq) => $pq->orderBy('sort_order')->orderBy('id');
        }
        $query->with($with);

        if ($this->search !== '') {
            $raw = $this->search;
            $term = '%'.addcslashes($raw, '%_\\').'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $term));
            });
        }

        if ($this->categoryId !== '') {
            $query->where('category_id', (int) $this->categoryId);
        }

        $services = $query->latest('services.updated_at')->latest('services.id')->paginate(12);

        $categories = Category::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('livewire.mteja.huduma-marketplace', [
            'services' => $services,
            'categories' => $categories,
            'usesServicePackages' => $hasPackages,
        ])
            ->layout('layouts.mteja')
            ->title(__('messages.huduma_marketplace.page_title'));
    }
}
