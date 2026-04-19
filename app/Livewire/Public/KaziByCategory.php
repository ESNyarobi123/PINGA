<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Job;
use Livewire\Component;

class KaziByCategory extends Component
{
    /** @var \Illuminate\Support\Collection */
    public $categories;

    public function mount(): void
    {
        $this->categories = Category::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->withCount(['jobs' => fn ($q) => $q->where('status', 'open')->where('is_approved', true)])
            ->orderByDesc('jobs_count')
            ->get();
    }

    public function render()
    {
        $jobsByCategory = [];

        foreach ($this->categories as $category) {
            $jobs = Job::query()
                ->where('category_id', $category->id)
                ->where('status', 'open')
                ->where('is_approved', true)
                ->with(['employer:id,name,avatar', 'category:id,name,slug,icon'])
                ->latest()
                ->take(6)
                ->get();

            if ($jobs->isNotEmpty()) {
                $jobsByCategory[$category->id] = $jobs;
            }
        }

        return view('livewire.public.kazi-by-category', [
            'jobsByCategory' => $jobsByCategory,
        ])
            ->layout('layouts.public')
            ->title('Kazi kwa Kategoria');
    }
}
