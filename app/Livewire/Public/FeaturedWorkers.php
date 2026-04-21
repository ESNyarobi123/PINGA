<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class FeaturedWorkers extends Component
{
    public ?int $categoryId = null;

    public string $categorySlug = '';

    public int $limit = 6;

    /** @var Collection<int, User>|array */
    public $featured = [];

    public function mount(?string $category = null): void
    {
        $this->categorySlug = $category ?? '';

        if ($category) {
            $cat = Category::where('slug', $category)->first();
            $this->categoryId = $cat?->id;
        }

        $this->loadFeatured();
    }

    public function loadFeatured(): void
    {
        $query = User::query()
            ->where('role', 'mfanyakazi')
            ->where('onboarding_completed', true)
            ->whereHas('activeSubscription.subscriptionPlan', function ($q) {
                $q->where('slug', 'bora');
            })
            ->with(['skills', 'activeSubscription.subscriptionPlan'])
            ->withAvg('reviewsReceived', 'rating')
            ->orderByDesc('reviews_received_avg_rating')
            ->orderByDesc('created_at');

        // Filter by category if provided
        if ($this->categoryId) {
            $query->whereHas('skills', function ($q) {
                // Get all skill IDs related to this category
                $skillIds = \App\Models\Skill::where('category_id', $this->categoryId)->pluck('id');
                $q->whereIn('skills.id', $skillIds);
            });
        }

        $this->featured = $query->limit($this->limit)->get();
    }

    public function render()
    {
        return view('livewire.public.featured-workers', [
            'hasFeatured' => $this->featured->isNotEmpty(),
        ]);
    }
}
