<?php

namespace App\Livewire\Admin;

use App\Models\SubscriptionPlan;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriptionPlans extends Component
{
    use WithPagination;

    /** @var list<string> */
    private const LIMIT_FORM_KEYS = [
        'limit_max_services',
        'limit_daily_bids',
        'limit_portfolio_imgs',
        'limit_search_boost',
        'limit_analytics',
        'limit_smart_match',
        'limit_custom_url',
        'limit_verified_badge',
        'limit_chat_badge',
        'limit_top_rated_eligible',
        'limit_featured_category',
        'limit_priority_support',
    ];

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

    public string $limit_max_services = '5';

    public string $limit_daily_bids = '10';

    public string $limit_portfolio_imgs = '5';

    public string $limit_search_boost = '10';

    public string $limit_analytics = 'basic';

    public string $limit_smart_match = 'normal';

    public bool $limit_custom_url = false;

    public bool $limit_verified_badge = false;

    public bool $limit_chat_badge = false;

    public bool $limit_top_rated_eligible = false;

    public bool $limit_featured_category = false;

    public bool $limit_priority_support = false;

    protected function rules(): array
    {
        return [
            'slug' => 'required|string|max:50|unique:subscription_plans,slug,'.$this->planId,
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
            'limit_max_services' => 'required|integer|min:0|max:999999',
            'limit_daily_bids' => 'required|integer|min:0|max:999999',
            'limit_portfolio_imgs' => 'required|integer|min:0|max:999999',
            'limit_search_boost' => 'required|integer|min:0|max:999999',
            'limit_analytics' => 'required|in:none,basic,advanced,full',
            'limit_smart_match' => 'required|in:none,normal,high,highest',
            'limit_custom_url' => 'boolean',
            'limit_verified_badge' => 'boolean',
            'limit_chat_badge' => 'boolean',
            'limit_top_rated_eligible' => 'boolean',
            'limit_featured_category' => 'boolean',
            'limit_priority_support' => 'boolean',
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

        $l = is_array($plan->limits) ? $plan->limits : [];
        $this->limit_max_services = (string) ($l['max_services'] ?? 5);
        $this->limit_daily_bids = (string) ($l['daily_bids'] ?? 10);
        $this->limit_portfolio_imgs = (string) ($l['portfolio_imgs'] ?? 5);
        $this->limit_search_boost = (string) ($l['search_boost'] ?? 10);
        $adv = $l['analytics'] ?? 'basic';
        $this->limit_analytics = in_array($adv, ['none', 'basic', 'advanced', 'full'], true) ? $adv : 'basic';
        $sm = $l['smart_match_priority'] ?? 'normal';
        $this->limit_smart_match = in_array($sm, ['none', 'normal', 'high', 'highest'], true) ? $sm : 'normal';
        $this->limit_custom_url = (bool) ($l['custom_url'] ?? false);
        $this->limit_verified_badge = (bool) ($l['verified_badge'] ?? false);
        $this->limit_chat_badge = (bool) ($l['chat_badge'] ?? false);
        $this->limit_top_rated_eligible = (bool) ($l['top_rated_eligible'] ?? false);
        $this->limit_featured_category = (bool) ($l['featured_category'] ?? false);
        $this->limit_priority_support = (bool) ($l['priority_support'] ?? false);

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Filter out empty features
        $validated['features'] = array_filter($validated['features'] ?? [], fn ($f) => ! empty(trim($f)));
        if (empty($validated['features'])) {
            $validated['features'] = [];
        }

        $validated['limits'] = $this->limitsFromForm();
        foreach (self::LIMIT_FORM_KEYS as $key) {
            unset($validated[$key]);
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
        $plan->update(['is_active' => ! $plan->is_active]);
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
        $this->limit_max_services = '5';
        $this->limit_daily_bids = '10';
        $this->limit_portfolio_imgs = '5';
        $this->limit_search_boost = '10';
        $this->limit_analytics = 'basic';
        $this->limit_smart_match = 'normal';
        $this->limit_custom_url = false;
        $this->limit_verified_badge = false;
        $this->limit_chat_badge = false;
        $this->limit_top_rated_eligible = false;
        $this->limit_featured_category = false;
        $this->limit_priority_support = false;
        $this->resetValidation();
    }

    /**
     * @return array<string, int|string|bool>
     */
    private function limitsFromForm(): array
    {
        return [
            'max_services' => (int) $this->limit_max_services,
            'daily_bids' => (int) $this->limit_daily_bids,
            'portfolio_imgs' => (int) $this->limit_portfolio_imgs,
            'analytics' => $this->limit_analytics,
            'smart_match_priority' => $this->limit_smart_match,
            'search_boost' => (int) $this->limit_search_boost,
            'custom_url' => $this->limit_custom_url,
            'verified_badge' => $this->limit_verified_badge,
            'chat_badge' => $this->limit_chat_badge,
            'top_rated_eligible' => $this->limit_top_rated_eligible,
            'featured_category' => $this->limit_featured_category,
            'priority_support' => $this->limit_priority_support,
        ];
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
