<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Skill;
use App\Models\User;
use App\Services\LocationService;
use App\Services\WafanyakaziListingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TafutaWafanyakazi extends Component
{
    public string $search = '';

    public string $skill = '';

    public string $location = '';

    public string $category = '';

    public string $filter = '';

    public string $filterType = 'all';

    /** @var bool Show skeleton until first data load (perceived speed) */
    public bool $ready = false;

    /** @var array<int, array<string, mixed>> */
    public array $wafanyakazi = [];

    public int $total = 0;

    /** @var \Illuminate\Support\Collection<int, \App\Models\Skill>|array */
    public $skillsForFilter = [];

    /** @var \Illuminate\Support\Collection<int, \App\Models\Category>|array */
    public $categoriesForFilter = [];

    /** @var \Illuminate\Support\Collection<string> */
    public $locationsForFilter = [];

    /** @var array<int> IDs of workers the current user has favorited */
    public array $favoritedWorkerIds = [];

    public function mount(): void
    {
        $this->filterType = request('filter', 'all');
        $this->filter = 'mpya';
        $this->skillsForFilter = collect();
    }

    public function loadData(WafanyakaziListingService $service): void
    {
        $result = $service->list([
            'search' => $this->search,
            'skill' => $this->skill,
            'location' => $this->location,
            'category' => $this->category,
            'filter' => $this->filter,
            'per_page' => 12,
        ]);

        $this->wafanyakazi = $result['data'];
        $this->total = $result['meta']['total'];
        $this->skillsForFilter = Skill::query()->orderBy('name')->get(['id', 'name', 'slug']);
        $this->categoriesForFilter = Category::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
        $this->locationsForFilter = LocationService::getAllLocations();
        $this->loadFavorites();
        $this->ready = true;
    }

    public function loadMore(): void
    {
        $this->loadData(app(WafanyakaziListingService::class));
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['search', 'skill', 'location', 'category', 'filter'], true) && $this->ready) {
            $this->loadData(app(WafanyakaziListingService::class));
        }
    }

    public function toggleFavorite(int $workerId): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $user = Auth::user();
        $isFavorited = $user->toggleFavorite(User::class, $workerId);

        if ($isFavorited) {
            $this->favoritedWorkerIds[] = $workerId;
        } else {
            $this->favoritedWorkerIds = array_values(array_diff($this->favoritedWorkerIds, [$workerId]));
        }
    }

    private function loadFavorites(): void
    {
        if (Auth::check()) {
            $this->favoritedWorkerIds = Auth::user()->favorites()
                ->where('favorable_type', User::class)
                ->pluck('favorable_id')
                ->all();
        }
    }

    public function render()
    {
        return view('livewire.public.tafuta-wafanyakazi', [
            'showSkeleton' => ! $this->ready,
        ])
            ->layout('layouts.public')
            ->title('Tafuta Wafanyakazi');
    }
}
