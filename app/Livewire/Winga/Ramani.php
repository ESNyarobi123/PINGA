<?php

namespace App\Livewire\Winga;

use App\Models\Job;
use Illuminate\Support\Js;
use Livewire\Component;

class Ramani extends Component
{
    public string $search = '';

    public string $radius = '50'; // km

    public array $jobs = [];

    public bool $ready = false;

    public function mount(): void
    {
        $this->loadJobs();
    }

    public function updatedSearch(): void
    {
        $this->loadJobs();
    }

    public function updatedRadius(): void
    {
        $this->loadJobs();
    }

    public function loadJobs(): void
    {
        $user = auth()->user();

        $query = Job::query()
            ->where('status', 'open')
            ->where('is_approved', true)
            ->with(['employer:id,name,avatar', 'category:id,name'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($this->search !== '') {
            $term = '%'.addcslashes($this->search, '%_\\').'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('location', 'like', $term);
            });
        }

        if ($user->latitude !== null && $user->longitude !== null) {
            $lat = (float) $user->latitude;
            $lng = (float) $user->longitude;
            $r = max(1, (int) $this->radius);

            $haversine = '(6371 * acos(least(1, greatest(-1, cos(radians(?)) * cos(radians(job_listings.latitude)) * cos(radians(job_listings.longitude) - radians(?)) + sin(radians(?)) * sin(radians(job_listings.latitude))))))';

            $query->select('job_listings.*')
                ->selectRaw("{$haversine} as distance", [$lat, $lng, $lat])
                ->havingRaw('distance < ?', [$r])
                ->orderBy('distance');
        } else {
            $query->latest('updated_at')->latest('id');
        }

        $this->jobs = $query->limit(100)->get()->map(fn (Job $job) => [
            'id' => $job->id,
            'title' => $job->title,
            'slug' => $job->slug,
            'latitude' => (float) $job->latitude,
            'longitude' => (float) $job->longitude,
            'location' => $job->location ?? '—',
            'budget_min' => (int) ($job->budget_min ?? 0),
            'budget_type' => $job->budget_type,
            'urgency' => $job->urgency,
            'category' => $job->category?->name ?? '—',
            'employer' => $job->employer?->name ?? '—',
            'distance' => isset($job->distance) ? round((float) $job->distance, 1) : null,
            'posted_at' => $job->created_at->diffForHumans(),
            'url' => route('kazi.show', $job->slug),
        ])->values()->all();

        $this->ready = true;

        $this->dispatchMapUpdate($user);
    }

    private function dispatchMapUpdate($user): void
    {
        $data = Js::from([
            'jobs' => $this->jobs,
            'userLat' => $user->latitude !== null ? (float) $user->latitude : null,
            'userLng' => $user->longitude !== null ? (float) $user->longitude : null,
        ]);

        $this->js("if(typeof window.__wingaRamaniApplyMap==='function'){window.__wingaRamaniApplyMap({$data})}");
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.winga.ramani', [
            'userLat' => $user->latitude,
            'userLng' => $user->longitude,
            'userLocation' => trim(implode(', ', array_filter([$user->mtaa, $user->wilaya, $user->mkoa]))),
        ])
            ->layout('layouts.winga')
            ->title(__('messages.ramani.page_title'));
    }
}
