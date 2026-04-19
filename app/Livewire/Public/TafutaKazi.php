<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Job;
use App\Models\Skill;
use App\Services\LocationService;
use App\Services\KaziListingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TafutaKazi extends Component
{
    public string $search = '';

    public string $category = '';

    public string $location = '';

    public string $skill = '';

    public string $filter = '';

    public string $filterType = 'all';

    /** @var bool Show skeleton until first data load (perceived speed) */
    public bool $ready = false;

    /** @var array<int, array<string, mixed>> */
    public array $kazi = [];

    public int $total = 0;

    public int $perPage = 12;

    /** @var \Illuminate\Support\Collection<int, \App\Models\Category>|array */
    public $categoriesForFilter = [];

    /** @var \Illuminate\Support\Collection<int, \App\Models\Skill>|array */
    public $skillsForFilter = [];

    /** @var \Illuminate\Support\Collection<string> */
    public $locationsForFilter = [];

    /** @var array<int> IDs of jobs the current user has favorited */
    public array $favoritedJobIds = [];

    public function mount(): void
    {
        $this->filterType = request('filter', 'all');
        $this->filter = 'mpya';
        $this->category = '';
        $this->categoriesForFilter = collect();
    }

    public function loadData(KaziListingService $service): void
    {
        $result = $service->list([
            'search' => $this->search,
            'category' => $this->category,
            'location' => $this->location,
            'skill' => $this->skill,
            'filter' => $this->filter,
            'per_page' => $this->perPage,
        ]);

        $this->kazi = $result['data'];
        $this->total = $result['meta']['total'];
        $this->categoriesForFilter = Category::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
        $this->skillsForFilter = Skill::query()->orderBy('name')->get(['id', 'name', 'slug']);
        $this->locationsForFilter = LocationService::getAllLocations();
        $this->loadFavorites();
        $this->ready = true;
    }

    public function loadMore(): void
    {
        $this->perPage += 12;
        $this->loadData(app(KaziListingService::class));
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['search', 'category', 'location', 'skill', 'filter'], true) && $this->ready) {
            $this->perPage = 12;
            $this->loadData(app(KaziListingService::class));
        }
    }

    public function toggleFavorite(int $jobId): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $user = Auth::user();
        $isFavorited = $user->toggleFavorite(Job::class, $jobId);

        if ($isFavorited) {
            $this->favoritedJobIds[] = $jobId;
        } else {
            $this->favoritedJobIds = array_values(array_diff($this->favoritedJobIds, [$jobId]));
        }
    }

    private function loadFavorites(): void
    {
        if (Auth::check()) {
            $this->favoritedJobIds = Auth::user()->favorites()
                ->where('favorable_type', Job::class)
                ->pluck('favorable_id')
                ->all();
        }
    }

    public function render()
    {
        return view('livewire.public.tafuta-kazi', [
            'showSkeleton' => ! $this->ready,
        ])
            ->layout('layouts.public')
            ->title('Tafuta Kazi');
    }
}
