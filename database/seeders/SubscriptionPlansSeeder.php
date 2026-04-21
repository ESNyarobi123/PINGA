<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'slug' => 'winga-complex',
                'name' => 'Winga Complex',
                'name_en' => 'Simple Worker',
                'price' => 5000,
                'duration_days' => 30,
                'badge_label' => 'Winga',
                'badge_color' => 'amber',
                'is_recommended' => false,
                'is_active' => true,
                'sort_order' => 1,
                'limits' => [
                    'max_services' => 5,
                    'daily_bids' => 5,
                    'portfolio_imgs' => 3,
                    'analytics' => 'basic',
                    'smart_match_priority' => 'normal',
                    'search_boost' => 10,
                    'custom_url' => false,
                    'verified_badge' => false,
                    'chat_badge' => false,
                    'top_rated_eligible' => false,
                    'featured_category' => false,
                    'priority_support' => false,
                ],
                'features' => [
                    'Beji ya "Winga" kwenye wasifu',
                    'Maombi 5 ya kazi kwa siku',
                    'Portfolio picha 3',
                    'Maoni ya kazi 10',
                    'Uonekane katika utafutaji',
                ],
            ],
            [
                'slug' => 'winga-karume',
                'name' => 'Winga Karume',
                'name_en' => 'Skilled Worker',
                'price' => 15000,
                'duration_days' => 60,
                'badge_label' => 'Winga Karume ⭐',
                'badge_color' => 'blue',
                'is_recommended' => true,
                'is_active' => true,
                'sort_order' => 2,
                'limits' => [
                    'max_services' => 15,
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
                ],
                'features' => [
                    'Faida zote za Winga Complex',
                    'Beji ya "Winga Karume" ya bluu',
                    'Maombi 15 ya kazi kwa siku',
                    'Portfolio picha 10',
                    'Upaumbele katika utafutaji',
                    'Uonekane kwenye winga bora',
                    'Analytics ya kazi 30 siku',
                    'Verified badge',
                ],
            ],
            [
                'slug' => 'winga-kkoo',
                'name' => 'Winga k/koo',
                'name_en' => 'Top Rated Worker',
                'price' => 35000,
                'duration_days' => 90,
                'badge_label' => 'Winga k/koo 🏆',
                'badge_color' => 'winga',
                'is_recommended' => false,
                'is_active' => true,
                'sort_order' => 3,
                'limits' => [
                    'max_services' => 0,
                    'daily_bids' => 0,
                    'portfolio_imgs' => 0,
                    'analytics' => 'full',
                    'smart_match_priority' => 'highest',
                    'search_boost' => 50,
                    'custom_url' => true,
                    'verified_badge' => true,
                    'chat_badge' => true,
                    'top_rated_eligible' => true,
                    'featured_category' => true,
                    'priority_support' => true,
                ],
                'features' => [
                    'Faida zote za Winga Karume',
                    'Beji ya dhahabu "Winga k/koo"',
                    'Maombi zisizo na kikomo za kazi',
                    'Portfolio picha zisizo na kikomo',
                    'Nafasi ya kwanza kwenye utafutaji',
                    'Uonekane kwenye home page carousel',
                    'Analytics kamili ya kazi',
                    'Verified + Top Rated badge',
                    'Msaada wa kipaumbele 24/7',
                    'Smart match priority ya haraka',
                    'Custom URL ya wasifu',
                    'Ishara ya muda wa majibu',
                ],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }

        $this->command->info('Subscription plans seeded: Winga Complex, Winga Karume, Winga k/koo');
    }
}
