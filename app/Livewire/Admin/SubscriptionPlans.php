<?php

namespace App\Livewire\Admin;

use App\Models\SubscriptionPlan;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriptionPlans extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $planId = null;

    // Form fields
    public string $slug = '';
    public string $name = '';
    public string $name_en = '';
    public string $price = '';
    public string $duration_days = '30';
    public array $features = [''];
    public string $badge_label = '';
    public string $badge_color = 'amber';
    public bool $is_recommended = false;
    public bool $is_active = true;
    public string $sort_order = '0';

    protected function rules(): array
    {
        return [
            'slug' => 'required|string|max:50|unique:subscription_plans,slug,' . $this->planId,
            'name' => 'required|string|max:100',
            'name_en' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'badge_label' => 'nullable|string|max:50',
            'badge_color' => 'required|in:amber,blue,winga,green,red,purple',
            'is_recommended' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function updatedSlug(): void
    {
        $this->slug = \Illuminate\Support\Str::slug($this->slug);
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $this->planId = $plan->id;
        $this->slug = $plan->slug;
        $this->name = $plan->name;
        $this->name_en = $plan->name_en ?? '';
        $this->price = (string) $plan->price;
        $this->duration_days = (string) $plan->duration_days;
        $this->features = $plan->features ?? [''];
        $this->badge_label = $plan->badge_label ?? '';
        $this->badge_color = $plan->badge_color ?? 'amber';
        $this->is_recommended = $plan->is_recommended;
        $this->is_active = $plan->is_active;
        $this->sort_order = (string) $plan->sort_order;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Filter out empty features
        $validated['features'] = array_filter($validated['features'] ?? [], fn($f) => !empty(trim($f)));
        if (empty($validated['features'])) {
            $validated['features'] = [];
        }

        if ($this->isEditing) {
            SubscriptionPlan::findOrFail($this->planId)->update($validated);
            $this->dispatch('toast', message: 'Mpango umehindishwa.', type: 'success');
        } else {
            SubscriptionPlan::create($validated);
            $this->dispatch('toast', message: 'Mpango mpya umeundwa.', type: 'success');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $plan = SubscriptionPlan::findOrFail($id);
        
        // Check if plan has subscriptions
        if ($plan->subscriptions()->exists()) {
            $this->dispatch('toast', message: 'Mpango una subscriptions - huwezi kufuta.', type: 'error');
            return;
        }

        $plan->delete();
        $this->dispatch('toast', message: 'Mpango umefutwa.', type: 'success');
    }

    public function toggleActive(int $id): void
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->update(['is_active' => !$plan->is_active]);
        $status = $plan->is_active ? 'wamewashwa' : 'mmezimwa';
        $this->dispatch('toast', message: "Mpango {$status}.", type: 'success');
    }

    public function addFeature(): void
    {
        $this->features[] = '';
    }

    public function removeFeature(int $index): void
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->planId = null;
        $this->slug = '';
        $this->name = '';
        $this->name_en = '';
        $this->price = '';
        $this->duration_days = '30';
        $this->features = [''];
        $this->badge_label = '';
        $this->badge_color = 'amber';
        $this->is_recommended = false;
        $this->is_active = true;
        $this->sort_order = '0';
        $this->resetValidation();
    }

    public function render()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->orderBy('id')->paginate(10);

        return view('livewire.admin.subscription-plans', [
            'plans' => $plans,
        ])
            ->layout('layouts.admin')
            ->title('Admin — Subscription Plans');
    }
}
