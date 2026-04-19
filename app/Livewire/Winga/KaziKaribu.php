<?php

namespace App\Livewire\Winga;

use App\Models\Application;
use App\Models\Category;
use App\Models\Job;
use App\Services\LocationService;
use App\Services\SubscriptionLimitsService;
use Livewire\Component;
use Livewire\WithPagination;

class KaziKaribu extends Component
{
    use WithPagination;

    // Search & filter state
    public string $search = '';

    public string $categoryId = '';

    public string $location = '';

    public string $urgency = '';

    public string $budgetMin = '';

    public string $budgetMax = '';

    public string $sortBy = 'latest'; // latest | budget_high | budget_low

    protected SubscriptionLimitsService $limitsService;

    public function boot(SubscriptionLimitsService $limitsService): void
    {
        $this->limitsService = $limitsService;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatingLocation(): void
    {
        $this->resetPage();
    }

    public function updatingUrgency(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'categoryId', 'location', 'urgency', 'budgetMin', 'budgetMax', 'sortBy']);
        $this->resetPage();
    }

    public function viewJob(int $id): void
    {
        $job = Job::where('id', $id)->where('status', 'open')->where('is_approved', true)->first();
        if (! $job) {
            $this->dispatch('toast', message: 'Kazi haipatikani tena.', type: 'error');
            return;
        }

        $this->redirectRoute('winga.kazi-detail', ['slug' => $job->slug]);
    }

    public function render()
    {
        $query = Job::with(['employer:id,name,avatar', 'category:id,name,icon'])
            ->withCount('applications')
            ->where('status', 'open')
            ->where('is_approved', true);

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('title', 'like', $this->search . '%')
                  ->orWhere('description', 'like', $this->search . '%')
                  ->orWhere('location', 'like', $this->search . '%');
            });
        }

        if ($this->categoryId !== '') {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->location !== '') {
            $query->where('location', 'like', '%' . $this->location . '%');
        }

        if ($this->urgency !== '') {
            $query->where('urgency', $this->urgency);
        }

        if ($this->budgetMin !== '') {
            $query->where('budget_min', '>=', (float) $this->budgetMin);
        }

        if ($this->budgetMax !== '') {
            $query->where('budget_max', '<=', (float) $this->budgetMax);
        }

        $query = match ($this->sortBy) {
            'budget_high' => $query->orderByDesc('budget_max'),
            'budget_low'  => $query->orderBy('budget_min'),
            default       => $query->latest(),
        };

        $categories = Category::where('is_active', true)->orderBy('name')->get(['id', 'name', 'icon']);
        $locations = LocationService::getAllLocations();

        return view('livewire.winga.kazi-karibu', [
            'jobs'       => $query->paginate(12),
            'categories' => $categories,
            'locations'  => $locations,
        ])
            ->layout('layouts.winga')
            ->title('Kazi Karibu Yangu');
    }
}
