<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Run Winga seeder (roles, permissions, categories, skills)
        $this->call(WingaSeeder::class);

        // Seed open jobs for Tafuta Kazi page
        $this->call(JobSeeder::class);

        // Seed demo users (winga@gmail.com, mteja@gmail.com) + 15 approved jobs
        $this->call(DemoDataSeeder::class);

        // System admin — nenosiri: Winga@2026 (herufi kubwa W, @, tarakimu)
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Winga Admin',
                'email_verified_at' => now(),
                'password' => 'Winga@2026',
                'phone' => '+255700000000',
                'role' => 'admin',
                'onboarding_completed' => true,
                'two_factor_enabled' => false,
                'suspended_at' => null,
                'suspended_reason' => null,
            ]
        );
        $admin->syncRoles(['admin']);
        $admin->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        // Test employer (idempotent — safe to re-run db:seed)
        $mteja = User::updateOrCreate(
            ['email' => 'juma@example.com'],
            [
                'name' => 'Juma Hassan',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+255712345678',
                'location' => 'Dar es Salaam',
                'onboarding_completed' => true,
                'role' => 'mteja',
            ]
        );
        // Spatie roles from WingaSeeder: muajili / mfanyakazi (see Auth\Register::getDbRole)
        $mteja->syncRoles(['muajili']);

        // Test worker (idempotent)
        $winga = User::updateOrCreate(
            ['email' => 'amina@example.com'],
            [
                'name' => 'Amina Said',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+255798765432',
                'location' => 'Arusha',
                'bio' => 'Mtaalamu wa teknolojia na programu. Nina uzoefu wa miaka 5.',
                'onboarding_completed' => true,
                'role' => 'winga',
            ]
        );
        $winga->syncRoles(['mfanyakazi']);
    }
}
