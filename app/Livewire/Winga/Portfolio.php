<?php

namespace App\Livewire\Winga;

use App\Models\Category;
use App\Models\Portfolio as PortfolioModel;
use App\Services\SubscriptionLimitsService;
use App\Services\SubscriptionService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Portfolio extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Form fields
    public string $title = '';

    public string $description = '';

    public ?int $categoryId = null;

    public ?string $projectUrl = null;

    public $image = null;

    // Edit mode
    public ?int $editingId = null;

    // Limit states
    public bool $showLimitError = false;

    public string $limitMessage = '';

    public ?array $suggestedUpgrade = null;

    // Modal state
    public bool $showUploadModal = false;

    public bool $is_featured = false;

    protected SubscriptionLimitsService $limitsService;

    public function boot(SubscriptionLimitsService $limitsService): void
    {
        $this->limitsService = $limitsService;
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'categoryId' => 'nullable|exists:categories,id',
            'projectUrl' => 'nullable|url|max:255',
            'image' => $this->editingId ? 'nullable|image|max:5120' : 'required|image|max:5120',
        ];
    }

    public function save(): void
    {
        $user = auth()->user();

        // Check portfolio limit for new items
        if (! $this->editingId && ! $this->limitsService->canUploadPortfolio($user)) {
            $limit = $this->limitsService->getLimit($user, 'portfolio_imgs');
            $this->showLimitError = true;
            $this->limitMessage = "Umefika kikomo cha picha za portfolio ({$limit}). Futa zingine au panda mpango wako!";
            $this->suggestedUpgrade = $this->limitsService->getSuggestedUpgrade($user);

            $this->dispatch('toast', message: $this->limitMessage, type: 'error');

            return;
        }

        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('portfolio', 'public');
        }

        if ($this->editingId) {
            $portfolio = PortfolioModel::where('id', $this->editingId)
                ->where('user_id', $user->id)
                ->first();

            if ($portfolio) {
                $portfolio->update([
                    'title' => $this->title,
                    'description' => $this->description,
                    'category_id' => $this->categoryId,
                    'project_url' => $this->projectUrl,
                    'image_path' => $imagePath ?? $portfolio->image_path,
                ]);
                $this->dispatch('toast', message: 'Portfolio imehifadhiwa!', type: 'success');
            }
        } else {
            PortfolioModel::create([
                'user_id' => $user->id,
                'title' => $this->title,
                'description' => $this->description,
                'category_id' => $this->categoryId,
                'project_url' => $this->projectUrl,
                'image_path' => $imagePath,
            ]);

            $remaining = $this->limitsService->remainingPortfolioSlots($user);
            $this->dispatch('toast',
                message: "Portfolio imeongezeka! Salio: {$remaining} picha",
                type: 'success'
            );
        }

        $this->reset(['title', 'description', 'categoryId', 'projectUrl', 'image', 'editingId']);
    }

    public function edit(int $id): void
    {
        $portfolio = PortfolioModel::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($portfolio) {
            $this->editingId = $portfolio->id;
            $this->title = $portfolio->title;
            $this->description = $portfolio->description ?? '';
            $this->categoryId = $portfolio->category_id;
            $this->projectUrl = $portfolio->project_url;
            $this->image = null; // Reset image for editing
        }
    }

    public function cancelEdit(): void
    {
        $this->reset(['title', 'description', 'categoryId', 'projectUrl', 'image', 'editingId']);
    }

    public function delete(int $id): void
    {
        $portfolio = PortfolioModel::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($portfolio) {
            $portfolio->delete();
            $this->dispatch('toast', message: 'Portfolio imefutwa!', type: 'success');
        }
    }

    public function toggleUploadModal(): void
    {
        $this->showUploadModal = ! $this->showUploadModal;
        if (! $this->showUploadModal) {
            $this->resetForm();
        }
    }

    public function resetForm(): void
    {
        $this->title = '';
        $this->description = '';
        $this->categoryId = null;
        $this->projectUrl = null;
        $this->image = null;
        $this->is_featured = false;
        $this->editingId = null;
    }

    public function render()
    {
        $user = auth()->user();
        $portfolios = PortfolioModel::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->paginate(12);

        $categories = Category::active()->orderBy('name')->get();

        $remaining = $this->limitsService->remainingPortfolioSlots($user);
        $max = $this->limitsService->getLimit($user, 'portfolio_imgs');

        return view('livewire.winga.portfolio', [
            'portfolios' => $portfolios,
            'categories' => $categories,
            'remaining' => $remaining,
            'max' => $max,
            'canUpload' => $this->limitsService->canUploadPortfolio($user),
            'debug_info' => [
                'plan_slug' => app(SubscriptionService::class)->getActivePlan($user)?->plan_slug ?? 'free',
                'limit' => $this->limitsService->getLimit($user, 'portfolio_imgs'),
                'current_count' => $user->portfolioImages()->count(),
                'remaining_slots' => $remaining,
            ],
        ])
            ->layout('layouts.winga')
            ->title('Portfolio Yangu');
    }
}
