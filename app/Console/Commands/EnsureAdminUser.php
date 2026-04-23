<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class EnsureAdminUser extends Command
{
    protected $signature = 'admin:ensure
                            {--email=admin@gmail.com : Admin email}
                            {--password=Winga@2026 : Admin password (plain text)}';

    protected $description = 'Create or reset the system admin user (email, password, role, clear 2FA/suspension).';

    public function handle(): int
    {
        $email = strtolower(trim((string) $this->option('email')));
        $plain = (string) $this->option('password');

        if ($email === '' || $plain === '') {
            $this->error('Email and password cannot be empty.');

            return self::FAILURE;
        }

        $admin = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Winga Admin',
                'email_verified_at' => now(),
                'password' => $plain,
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

        $this->info("Admin OK: {$email} (you can log in at /login)");

        return self::SUCCESS;
    }
}
