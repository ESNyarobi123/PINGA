<?php

namespace App\Livewire\Winga;

use App\Models\Category;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Services\SubscriptionLimitsService;
use App\Support\ServicePackageSchema;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostHuduma extends Component
{
    use WithFileUploads;

    public string $title = '';

    public string $description = '';

    public ?int $categoryId = null;

    public string $priceType = 'fixed';

    public array $images = [];

    /** @var array<int, array{title: string, description: string, price: mixed}> */
    public array $packages = [
        ['title' => '', 'description' => '', 'price' => null],
    ];

    public bool $showLimitError = false;

    public string $limitMessage = '';

    public ?array $suggestedUpgrade = null;

    protected SubscriptionLimitsService $limitsService;

    public function boot(SubscriptionLimitsService $limitsService): void
    {
        $this->limitsService = $limitsService;
    }

    public function mount(): void
    {
        $user = auth()->user();

        if (! $this->limitsService->canPostService($user)) {
            $this->showLimitError = true;
            $this->limitMessage = 'Umefika kikomo cha huduma. Jiunge na mpango wa juu kuweka huduma zaidi.';
            $this->suggestedUpgrade = $this->limitsService->getSuggestedUpgrade($user);
        }
    }

    public function addPackageRow(): void
    {
        if (count($this->packages) >= 10) {
            return;
        }
        $this->packages[] = ['title' => '', 'description' => '', 'price' => null];
    }

    public function removePackageRow(int $index): void
    {
        if (count($this->packages) <= 1) {
            return;
        }
        unset($this->packages[$index]);
        $this->packages = array_values($this->packages);
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:20|max:2000',
            'categoryId' => 'required|exists:categories,id',
            'priceType' => 'required|in:fixed,hourly,negotiable',
            'packages' => 'required|array|min:1|max:10',
            'packages.*.title' => 'required|string|min:2|max:120',
            'packages.*.description' => 'nullable|string|max:1000',
            'packages.*.price' => 'nullable|numeric|min:0',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:2048',
        ];
    }

    private function summaryServicePrice(): ?float
    {
        $prices = collect($this->packages)
            ->pluck('price')
            ->filter(fn ($p) => $p !== null && $p !== '')
            ->map(fn ($p) => (float) $p);

        return $prices->isEmpty() ? null : $prices->min();
    }

    public function submit(): void
    {
        $user = auth()->user();

        if (! $this->limitsService->canPostService($user)) {
            $this->showLimitError = true;
            $this->limitMessage = 'Umefika kikomo cha huduma. Jiunge na mpango wa juu kuweka huduma zaidi.';
            $this->suggestedUpgrade = $this->limitsService->getSuggestedUpgrade($user);

            $this->dispatch('toast', message: $this->limitMessage, type: 'error');

            return;
        }

        $this->validate();

        if (! ServicePackageSchema::hasPackagesTable()) {
            $this->dispatch('toast', message: __('messages.post_huduma.migrate_required'), type: 'error');

            return;
        }

        $imagePaths = [];
        foreach ($this->images as $image) {
            $imagePaths[] = $image->store('services', 'public');
        }

        $service = Service::create([
            'user_id' => $user->id,
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->summaryServicePrice(),
            'price_type' => $this->priceType,
            'images' => $imagePaths,
            'status' => 'active',
        ]);

        foreach (array_values($this->packages) as $i => $pkg) {
            ServicePackage::create([
                'service_id' => $service->id,
                'title' => $pkg['title'],
                'description' => isset($pkg['description']) && $pkg['description'] !== '' ? $pkg['description'] : null,
                'price' => isset($pkg['price']) && $pkg['price'] !== '' && $pkg['price'] !== null ? $pkg['price'] : null,
                'sort_order' => $i,
            ]);
        }

        $remaining = $this->limitsService->remainingServiceSlots($user);

        $this->dispatch('toast',
            message: "Huduma yako imewekwa! Salio: {$remaining} / ".$this->limitsService->getLimit($user, 'max_services').' huduma',
            type: 'success'
        );

        $this->reset(['title', 'description', 'categoryId', 'priceType', 'images']);
        $this->packages = [['title' => '', 'description' => '', 'price' => null]];

        if (! $this->limitsService->canPostService($user)) {
            $this->showLimitError = true;
            $this->limitMessage = 'Umefika kikomo cha huduma. Jiunge na mpango wa juu kuweka huduma zaidi.';
            $this->suggestedUpgrade = $this->limitsService->getSuggestedUpgrade($user);
        }
    }

    public function removeImage(int $index): void
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function render()
    {
        $user = auth()->user();
        $categories = Category::active()->orderBy('name')->get();

        $remaining = $this->limitsService->remainingServiceSlots($user);
        $max = $this->limitsService->getLimit($user, 'max_services');

        return view('livewire.winga.post-huduma', [
            'categories' => $categories,
            'remaining' => $remaining,
            'max' => $max,
            'canPost' => ! $this->showLimitError,
        ])
            ->layout('layouts.winga')
            ->title('Weka Huduma - Mfanyakazi');
    }
}
