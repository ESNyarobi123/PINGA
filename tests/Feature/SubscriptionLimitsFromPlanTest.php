<?php

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionLimitsService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makePlanLimits(int $maxServices = 15): array
{
    return [
        'max_services' => $maxServices,
        'daily_bids' => 15,
        'portfolio_imgs' => 10,
        'analytics' => 'advanced',
        'smart_match_priority' => 'high',
        'search_boost' => 25,
        'custom_url' => false,
        'verified_badge' => true,
        'chat_badge' => true,
        'top_rated_eligible' => true,
        'featured_category' => false,
        'priority_support' => false,
    ];
}

it('reads max_services from subscription plan limits for non-legacy slugs', function () {
    $plan = SubscriptionPlan::create([
        'slug' => 'winga-karume',
        'name' => 'Winga Karume',
        'name_en' => 'Karume',
        'price' => 15000,
        'duration_days' => 60,
        'features' => ['Portfolio 10'],
        'limits' => makePlanLimits(15),
        'badge_label' => 'Karume',
        'badge_color' => 'blue',
        'is_recommended' => true,
        'is_active' => true,
        'sort_order' => 2,
    ]);

    $user = User::factory()->create(['role' => 'winga']);

    Subscription::create([
        'user_id' => $user->id,
        'subscription_plan_id' => $plan->id,
        'plan' => 'basic',
        'plan_slug' => 'winga-karume',
        'amount_paid' => 15000,
        'starts_at' => now()->subDay(),
        'expires_at' => now()->addMonth(),
        'status' => 'active',
        'payment_status' => 'completed',
        'payment_reference' => 'test-ref',
        'payment_method' => 'wallet',
    ]);

    $limits = app(SubscriptionLimitsService::class)->getLimits($user);

    expect($limits['max_services'])->toBe(15)
        ->and($limits['portfolio_imgs'])->toBe(10);
});

it('falls back to seeded winga-karume defaults when limits json is null', function () {
    $plan = SubscriptionPlan::create([
        'slug' => 'winga-karume',
        'name' => 'Winga Karume',
        'name_en' => 'Karume',
        'price' => 15000,
        'duration_days' => 60,
        'features' => ['Portfolio 10'],
        'limits' => null,
        'badge_label' => 'Karume',
        'badge_color' => 'blue',
        'is_recommended' => true,
        'is_active' => true,
        'sort_order' => 2,
    ]);

    $user = User::factory()->create(['role' => 'winga']);

    Subscription::create([
        'user_id' => $user->id,
        'subscription_plan_id' => $plan->id,
        'plan' => 'basic',
        'plan_slug' => 'winga-karume',
        'amount_paid' => 15000,
        'starts_at' => now()->subDay(),
        'expires_at' => now()->addMonth(),
        'status' => 'active',
        'payment_status' => 'completed',
        'payment_reference' => 'test-ref',
        'payment_method' => 'wallet',
    ]);

    $limits = app(SubscriptionLimitsService::class)->getLimits($user);

    expect($limits['max_services'])->toBe(15)
        ->and($limits['portfolio_imgs'])->toBe(10);
});

it('uses free tier when there is no active subscription', function () {
    $user = User::factory()->create(['role' => 'winga']);

    $limits = app(SubscriptionLimitsService::class)->getLimits($user);

    expect($limits['max_services'])->toBe(2);
});
