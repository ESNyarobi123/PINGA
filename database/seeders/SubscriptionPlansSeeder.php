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
                'slug'           => 'winga-complex',
                'name'           => 'Winga Complex',
                'name_en'        => 'Simple Worker',
                'price'          => 5000,
                'duration_days'  => 30,
                'badge_label'    => 'Winga',
                'badge_color'    => 'amber',
                'is_recommended' => false,
                'is_active'      => true,
                'sort_order'     => 1,
                'features'       => [
                    'Beji ya "Winga" kwenye wasifu',
                    'Maombi 5 ya kazi kwa siku',
                    'Portfolio picha 3',
                    'Maoni ya kazi 10',
                    'Uonekane katika utafutaji',
                ],
            ],
            [
                'slug'           => 'winga-karume',
                'name'           => 'Winga Karume',
                'name_en'        => 'Skilled Worker',
                'price'          => 15000,
                'duration_days'  => 60,
                'badge_label'    => 'Winga Karume ⭐',
                'badge_color'    => 'blue',
                'is_recommended' => true,
                'is_active'      => true,
                'sort_order'     => 2,
                'features'       => [
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
                'slug'           => 'winga-kkoo',
                'name'           => 'Winga k/koo',
                'name_en'        => 'Top Rated Worker',
                'price'          => 35000,
                'duration_days'  => 90,
                'badge_label'    => 'Winga k/koo 🏆',
                'badge_color'    => 'winga',
                'is_recommended' => false,
                'is_active'      => true,
                'sort_order'     => 3,
                'features'       => [
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
