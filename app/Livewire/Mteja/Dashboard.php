<?php

namespace App\Livewire\Mteja;

use App\Services\MuajiliDashboardService;
use Livewire\Component;

class Dashboard extends Component
{
    public bool $ready = false;

    /** @var array{total_kazi: int, kazi_active: int, maombi_mapya: int, wallet: float} */
    public array $stats = [];

    /** @var \Illuminate\Support\Collection */
    public $recentJobs;

    public function mount(): void
    {
        $this->recentJobs = collect();
    }

    public function loadData(MuajiliDashboardService $service): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }
        $data = $service->data($user);
        $this->stats = $data['stats'];
        $this->recentJobs = $data['recent_jobs'];
        $this->ready = true;
    }

    public function render()
    {
        return view('livewire.mteja.dashboard', [
            'showSkeleton' => ! $this->ready,
        ])
            ->layout('layouts.mteja')
            ->title('Dashboard - Muajili');
    }
}
