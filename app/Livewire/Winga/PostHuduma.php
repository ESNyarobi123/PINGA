<?php

namespace App\Livewire\Winga;

use App\Models\Category;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Services\SubscriptionLimitsService;
use App\Support\ServicePackageSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostHuduma extends Component
{
    use WithFileUploads;

    public bool $isEditing = false;

    public ?int $editingServiceId = null;

    /** @var list<string> */
    public array $existingImages = [];

    /** @var list<string> */
    public array $originalExistingImages = [];

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

    public function mount(?Service $service = null): void
    {
        $user = auth()->user();

        if ($service !== null) {
            if ($service->user_id !== $user->id) {
                abort(403);
            }

            $this->isEditing = true;
            $this->editingServiceId = $service->id;
            $this->title = $service->title;
            $this->description = $service->description;
            $this->categoryId = $service->category_id;
            $this->priceType = $service->price_type ?? 'fixed';
            $this->existingImages = array_values($service->images ?? []);
            $this->originalExistingImages = $this->existingImages;

            $service->load(['packages' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')]);
            if ($service->packages->isNotEmpty()) {
                $this->packages = $service->packages->map(fn (ServicePackage $p) => [
                    'title' => $p->title,
                    'description' => $p->description ?? '',
                    'price' => $p->price,
                ])->values()->all();
            }

            return;
        }

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

    public function removeExistingImage(int $index): void
    {
        unset($this->existingImages[$index]);
        $this->existingImages = array_values($this->existingImages);
    }

    protected function rules(): array
    {
        $maxNew = max(0, 5 - count($this->existingImages));

        return [
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:20|max:2000',
            'categoryId' => 'required|exists:categories,id',
            'priceType' => 'required|in:fixed,hourly,negotiable',
            'packages' => 'required|array|min:1|max:10',
            'packages.*.title' => 'required|string|min:2|max:120',
            'packages.*.description' => 'nullable|string|max:1000',
            'packages.*.price' => 'nullable|numeric|min:0',
            'images' => 'nullable|array|max:'.$maxNew,
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
        $isEdit = $this->editingServiceId !== null;

        if (! $isEdit && ! $this->limitsService->canPostService($user)) {
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

        if (count($this->existingImages) + count($this->images) > 5) {
            $this->addError('images', __('messages.post_huduma.too_many_images'));

            return;
        }

        $newUploadPaths = [];
        foreach ($this->images as $image) {
            $newUploadPaths[] = $image->store('services', 'public');
        }

        $allImagePaths = array_values(array_merge($this->existingImages, $newUploadPaths));

        if ($isEdit) {
            $service = Service::query()
                ->where('user_id', $user->id)
                ->whereKey($this->editingServiceId)
                ->firstOrFail();

            foreach (array_diff($this->originalExistingImages, $this->existingImages) as $removed) {
                if (is_string($removed) && $removed !== '') {
                    Storage::disk('public')->delete($removed);
                }
            }

            DB::transaction(function () use ($service, $allImagePaths): void {
                $service->update([
                    'category_id' => $this->categoryId,
                    'title' => $this->title,
                    'description' => $this->description,
                    'price' => $this->summaryServicePrice(),
                    'price_type' => $this->priceType,
                    'images' => $allImagePaths,
                ]);

                $service->packages()->delete();

                foreach (array_values($this->packages) as $i => $pkg) {
                    ServicePackage::create([
                        'service_id' => $service->id,
                        'title' => $pkg['title'],
                        'description' => isset($pkg['description']) && $pkg['description'] !== '' ? $pkg['description'] : null,
                        'price' => isset($pkg['price']) && $pkg['price'] !== '' && $pkg['price'] !== null ? $pkg['price'] : null,
                        'sort_order' => $i,
                    ]);
                }
            });

            $this->dispatch('toast', message: __('messages.post_huduma.updated'), type: 'success');

            $this->redirect(route('winga.huduma-zangu'), navigate: true);

            return;
        }

        $service = Service::create([
            'user_id' => $user->id,
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->summaryServicePrice(),
            'price_type' => $this->priceType,
            'images' => $allImagePaths,
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

        $this->reset(['title', 'description', 'categoryId', 'priceType', 'images', 'existingImages', 'originalExistingImages']);
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

        $canPost = $this->isEditing || ! $this->showLimitError;

        return view('livewire.winga.post-huduma', [
            'categories' => $categories,
            'remaining' => $remaining,
            'max' => $max,
            'canPost' => $canPost,
            'isEditing' => $this->isEditing,
        ])
            ->layout('layouts.winga')
            ->title($this->isEditing ? __('messages.post_huduma.edit_page_title') : __('messages.post_huduma.page_title'));
    }
}
