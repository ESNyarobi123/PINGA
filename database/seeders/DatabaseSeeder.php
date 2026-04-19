<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Winga Admin',
            'email' => 'admin@winga.co.tz',
            'phone' => '+255700000000',
            'role' => 'admin',
            'onboarding_completed' => true,
        ]);
        $admin->assignRole('admin');

        // Create test employer (Mteja)
        $mteja = User::factory()->create([
            'name' => 'Juma Hassan',
            'email' => 'juma@example.com',
            'phone' => '+255712345678',
            'location' => 'Dar es Salaam',
            'onboarding_completed' => true,
            'role' => 'mteja',
        ]);
        $mteja->assignRole('mteja');

        // Create test worker (Winga)
        $winga = User::factory()->create([
            'name' => 'Amina Said',
            'email' => 'amina@example.com',
            'phone' => '+255798765432',
            'location' => 'Arusha',
            'bio' => 'Mtaalamu wa teknolojia na programu. Nina uzoefu wa miaka 5.',
            'onboarding_completed' => true,
            'role' => 'winga',
        ]);
        $winga->assignRole('winga');
    }
}
