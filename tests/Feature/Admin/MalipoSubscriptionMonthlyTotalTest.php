<?php

use App\Livewire\Admin\Malipo;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Livewire\Livewire;

test('subscriptionMonthlyTotalByPlan sums amount_paid for current month by plan slug', function () {
    $admin = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
    $winga = User::factory()->create(['role' => 'winga']);

    $plan = SubscriptionPlan::query()->firstOrCreate(
        ['slug' => 'msingi'],
        [
            'name' => 'Msingi',
            'name_en' => 'Basic',
            'price' => 15000,
            'duration_days' => 30,
            'features' => [],
            'limits' => [],
            'is_active' => true,
            'sort_order' => 0,
        ]
    );

    Subscription::query()->create([
        'user_id' => $winga->id,
        'subscription_plan_id' => $plan->id,
        'plan' => 'basic',
        'plan_slug' => 'msingi',
        'amount_paid' => 15000,
        'starts_at' => now(),
        'expires_at' => now()->addMonth(),
        'status' => 'active',
        'payment_status' => 'completed',
    ]);

    $total = Livewire::actingAs($admin)
        ->test(Malipo::class)
        ->instance()
        ->subscriptionMonthlyTotalByPlan('msingi');

    expect($total)->toBe(15000.0);
});
