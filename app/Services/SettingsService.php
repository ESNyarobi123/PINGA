<?php

namespace App\Services;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    private static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (isset(static::$cache[$key])) {
            return static::$cache[$key];
        }

        $setting = Cache::remember(
            "platform_setting:{$key}",
            now()->addHours(6),
            fn() => PlatformSetting::where('key', $key)->first()
        );

        if (! $setting) {
            return $default;
        }

        $value = match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'float' => (float) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };

        static::$cache[$key] = $value;

        return $value;
    }

    public static function set(string $key, mixed $value, ?string $type = null, ?string $group = null, ?int $updatedBy = null): void
    {
        if ($type === null) {
            $type = match (gettype($value)) {
                'boolean' => 'boolean',
                'integer' => 'integer',
                'double' => 'float',
                'array' => 'json',
                default => 'string',
            };
        }

        if ($type === 'json') {
            $value = json_encode($value);
        }

        DB::transaction(function () use ($key, $value, $type, $group, $updatedBy) {
            PlatformSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'group' => $group ?? 'general',
                    'updated_by' => $updatedBy,
                ]
            );
        });

        // Clear cache
        Cache::forget("platform_setting:{$key}");
        unset(static::$cache[$key]);
    }

    public static function getAllByGroup(string $group): array
    {
        return Cache::remember(
            "platform_settings:group:{$group}",
            now()->addHours(6),
            function () use ($group) {
                $settings = PlatformSetting::where('group', $group)->get();
                $result = [];

                foreach ($settings as $setting) {
                    $value = match ($setting->type) {
                        'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                        'integer' => (int) $setting->value,
                        'float' => (float) $setting->value,
                        'json' => json_decode($setting->value, true),
                        default => $setting->value,
                    };

                    $result[$setting->key] = [
                        'value' => $value,
                        'type' => $setting->type,
                        'description' => $setting->description,
                    ];
                }

                return $result;
            }
        );
    }

    public static function clearCache(): void
    {
        Cache::flush();
        static::$cache = [];
    }

    // Convenience methods for common settings
    public static function platformName(): string
    {
        return static::get('platform_name', 'Winga');
    }

    public static function platformUrl(): string
    {
        return static::get('platform_url', 'https://winga.co.tz');
    }

    public static function supportEmail(): string
    {
        return static::get('support_email', 'support@winga.co.tz');
    }

    public static function commissionRate(): float
    {
        return static::get('platform_commission_rate', 10);
    }

    public static function minWithdrawalAmount(): int
    {
        return static::get('min_withdrawal_amount', 5000);
    }

    public static function maxWithdrawalDaily(): int
    {
        return static::get('max_withdrawal_daily', 1000000);
    }

    public static function minDepositAmount(): int
    {
        return static::get('min_deposit_amount', 1000);
    }

    public static function maintenanceMode(): bool
    {
        return static::get('maintenance_mode', false);
    }

    public static function allowRegistrations(): bool
    {
        return static::get('allow_registrations', true);
    }

    public static function subscriptionsEnabled(): bool
    {
        return static::get('subscriptions_enabled', true);
    }

    public static function jobApprovalRequired(): bool
    {
        return static::get('job_approval_required', true);
    }

    public static function blockPhoneInDescriptions(): bool
    {
        return static::get('block_phone_in_descriptions', true);
    }

    public static function blockUrlsInDescriptions(): bool
    {
        return static::get('block_urls_in_descriptions', true);
    }

    public static function maxLoginAttempts(): int
    {
        return static::get('max_login_attempts', 5);
    }

    public static function sessionTimeoutMinutes(): int
    {
        return static::get('session_timeout_minutes', 1440);
    }

    public static function forceAdmin2FA(): bool
    {
        return static::get('force_admin_2fa', false);
    }

    public static function emailNotificationsEnabled(): bool
    {
        return static::get('email_notifications_enabled', true);
    }

    public static function smsNotificationsEnabled(): bool
    {
        return static::get('sms_notifications_enabled', true);
    }

    public static function adminAlertEmail(): string
    {
        return static::get('admin_alert_email', 'admin@winga.co.tz');
    }

    public static function alertOnFailedPayouts(): bool
    {
        return static::get('alert_on_failed_payouts', true);
    }

    public static function alertOnDisputes(): bool
    {
        return static::get('alert_on_disputes', true);
    }

    public static function alertOnSuspiciousActivity(): bool
    {
        return static::get('alert_on_suspicious_activity', true);
    }

    // Subscription pricing
    public static function getPlanPrice(string $plan): int
    {
        return static::get("{$plan}_price", match ($plan) {
            'msingi' => 15000,
            'kawaida' => 45000,
            'bora' => 120000,
            default => 0,
        });
    }

    // Smart match settings
    public static function getBoostPoints(string $plan): int
    {
        return static::get("{$plan}_boost_points", match ($plan) {
            'msingi' => 10,
            'kawaida' => 25,
            'bora' => 50,
            default => 0,
        });
    }

    public static function getNotificationDelay(string $plan): int
    {
        return static::get("{$plan}_notification_delay", match ($plan) {
            'msingi' => 60,
            'kawaida' => 15,
            'bora' => 0,
            default => 60,
        });
    }

    public static function maxMatchingDistance(): int
    {
        return static::get('max_matching_distance_km', 100);
    }
}
