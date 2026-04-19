<?php

namespace App\Livewire\Mteja;

use App\Models\Skill;
use App\Services\LocationService;
use App\Services\WafanyakaziListingService;
use Livewire\Component;

class Mawinga extends Component
{
    public string $search = '';

    public string $skill = '';

    public string $location = '';

    public string $filter = '';

    public bool $ready = false;

    public array $wafanyakazi = [];

    public int $total = 0;

    public $skillsForFilter = [];
    
    public $locationsForFilter = [];

    public function mount(): void
    {
        $this->filter = request('filter', 'mpya');
        $this->skillsForFilter = collect();
    }

    public function loadData(WafanyakaziListingService $service): void
    {
        $result = $service->list([
            'search' => $this->search,
            'skill' => $this->skill,
            'location' => $this->location,
            'filter' => $this->filter,
            'per_page' => 12,
        ]);

        $this->wafanyakazi = $result['data'];
        $this->total = $result['meta']['total'];
        $this->skillsForFilter = Skill::query()->orderBy('name')->get(['id', 'name', 'slug']);
        $this->locationsForFilter = LocationService::getAllLocations();
        $this->ready = true;
    }

    public function loadMore(): void
    {
        $this->loadData(app(WafanyakaziListingService::class));
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['search', 'skill', 'location', 'filter'], true) && $this->ready) {
            $this->loadData(app(WafanyakaziListingService::class));
        }
    }

    public function render()
    {
        return view('livewire.mteja.mawinga', [
            'showSkeleton' => ! $this->ready,
        ])
            ->layout('layouts.mteja')
            ->title('Mawinga');
    }
}
