<?php

namespace App\Livewire\Winga;

use App\Services\MfanyakaziDashboardService;
use Livewire\Component;

class Dashboard extends Component
{
    public bool $ready = false;

    /** @var array{kazi_karibu: int, maombi_active: int, kazi_zilizomalika: int, mapato_jumla: float, maombi_wiki: int, kukubaliwa_wiki: int, mapato_wiki: float} */
    public array $stats = [];

    /** @var \Illuminate\Support\Collection */
    public $recentJobs;

    public $ongoingJob = null;

    public function mount(): void
    {
        $this->recentJobs = collect();
    }

    public function loadData(MfanyakaziDashboardService $service): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }
        $data = $service->data($user);
        $this->stats      = $data['stats'];
        $this->recentJobs  = $data['recent_jobs'];
        $this->ongoingJob  = $data['ongoing_job'];
        $this->ready       = true;
    }

    public function render()
    {
        return view('livewire.winga.dashboard', [
            'showSkeleton' => ! $this->ready,
            'ongoingJob' => $this->ongoingJob,
            'stats' => $this->stats,
            'recentJobs' => $this->recentJobs,
        ])
            ->layout('layouts.winga')
            ->title('Dashboard - Winga');
    }
}
