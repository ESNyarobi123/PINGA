<?php

namespace App\Livewire\Admin;

use App\Models\AdminAuditLog;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Settings extends Component
{
    public array $settings = [];

    public array $categories = [];

    public string $activeCategory = 'general';

    public bool $showSaveConfirmation = false;

    public array $settingsToSave = [];

    protected $rules = [
        'settings.site_name' => 'required|string|max:255',
        'settings.site_description' => 'nullable|string|max:1000',
        'settings.site_email' => 'required|email|max:255',
        'settings.site_phone' => 'nullable|string|max:20',
        'settings.site_address' => 'nullable|string|max:500',
        'settings.maintenance_mode' => 'boolean',
        'settings.allow_registrations' => 'boolean',
        'settings.require_email_verification' => 'boolean',
        'settings.require_phone_verification' => 'boolean',
        'settings.default_user_role' => 'required|in:winga,mteja',
        'settings.commission_rate' => 'required|numeric|min:0|max:100',
        'settings.min_withdrawal_amount' => 'required|numeric|min:0',
        'settings.max_withdrawal_amount' => 'required|numeric|min:0',
        'settings.withdrawal_fee_rate' => 'required|numeric|min:0|max:100',
        'settings.auto_approve_withdrawals' => 'boolean',
        'settings.snippe_api_key' => 'nullable|string|max:255',
        'settings.snippe_secret_key' => 'nullable|string|max:255',
        'settings.snippe_webhook_url' => 'nullable|url|max:500',
        'settings.email_driver' => 'required|in:smtp,mailgun,ses,sendmail',
        'settings.mail_host' => 'nullable|required_if:email_driver,smtp|string|max:255',
        'settings.mail_port' => 'nullable|required_if:email_driver,smtp|integer|min:1|max:65535',
        'settings.mail_username' => 'nullable|required_if:email_driver,smtp|string|max:255',
        'settings.mail_password' => 'nullable|string|max:255',
        'settings.mail_encryption' => 'nullable|in:tls,ssl',
        'settings.mail_from_address' => 'nullable|email|max:255',
        'settings.mail_from_name' => 'nullable|string|max:255',
        'settings.pusher_app_id' => 'nullable|string|max:255',
        'settings.pusher_app_key' => 'nullable|string|max:255',
        'settings.pusher_app_secret' => 'nullable|string|max:255',
        'settings.pusher_app_cluster' => 'nullable|string|max:255',
        'settings.google_analytics_id' => 'nullable|string|max:50',
        'settings.facebook_pixel_id' => 'nullable|string|max:50',
        'settings.google_maps_api_key' => 'nullable|string|max:255',
        'settings.recaptcha_site_key' => 'nullable|string|max:255',
        'settings.recaptcha_secret_key' => 'nullable|string|max:255',
        'settings.enable_recaptcha' => 'boolean',
        'settings.session_lifetime' => 'required|integer|min:60|max:10080',
        'settings.max_login_attempts' => 'required|integer|min:1|max:10',
        'settings.lockout_duration' => 'required|integer|min:1|max:60',
        'settings.password_min_length' => 'required|integer|min:6|max:50',
        'settings.enable_two_factor' => 'boolean',
        'settings.backup_enabled' => 'boolean',
        'settings.backup_frequency' => 'required|in:daily,weekly,monthly',
        'settings.backup_retention_days' => 'required|integer|min:1|max:365',
        'settings.log_level' => 'required|in:debug,info,warning,error,critical',
        'settings.enable_performance_monitoring' => 'boolean',
        'settings.enable_error_tracking' => 'boolean',
        'settings.cache_driver' => 'required|in:file,redis,memcached,database',
        'settings.queue_driver' => 'required|in:sync,database,redis,beanstalkd',
        'settings.enable_notifications' => 'boolean',
        'settings.notification_channels' => 'required|array',
        'settings.notification_channels.*' => 'in:email,push,sms,database',
        'settings.enable_sms_notifications' => 'boolean',
        'settings.sms_provider' => 'required|in:twilio,africastalking,infobip',
        'settings.twilio_account_sid' => 'nullable|required_if:sms_provider,twilio|string|max:255',
        'settings.twilio_auth_token' => 'nullable|required_if:sms_provider,twilio|string|max:255',
        'settings.twilio_phone_number' => 'nullable|required_if:sms_provider,twilio|string|max:20',
        'settings.africastalking_username' => 'nullable|required_if:sms_provider,africastalking|string|max:255',
        'settings.africastalking_api_key' => 'nullable|required_if:sms_provider,africastalking|string|max:255',
        'settings.infobip_api_key' => 'nullable|required_if:sms_provider,infobip|string|max:255',
        'settings.infobip_base_url' => 'nullable|required_if:sms_provider,infobip|url|max:255',
        'settings.enable_chat_support' => 'boolean',
        'settings.support_email' => 'nullable|email|max:255',
        'settings.support_phone' => 'nullable|string|max:20',
        'settings.suspension_appeal_email' => 'nullable|email|max:255',
        'settings.suspension_appeal_whatsapp' => 'nullable|string|max:32',
        'settings.enable_live_chat' => 'boolean',
        'settings.chat_widget_position' => 'required|in:bottom-right,bottom-left,top-right,top-left',
        'settings.enable_help_center' => 'boolean',
        'settings.help_center_url' => 'nullable|url|max:500',
        'settings.enable_faq' => 'boolean',
        'settings.enable_ticket_system' => 'boolean',
        'settings.ticket_email' => 'nullable|email|max:255',
    ];

    public function mount(): void
    {
        $this->initializeSettings();
        $this->defineCategories();
        $this->loadSettings();
    }

    private function initializeSettings(): void
    {
        // Initialize all settings with default values
        $this->settings = [
            // General Settings
            'site_name' => config('app.name', 'Winga Platform'),
            'site_description' => config('app.description', 'Professional Services Platform'),
            'site_email' => config('mail.from.address', 'admin@winga.co.tz'),
            'site_phone' => '+255 712 345 678',
            'site_address' => 'Dar es Salaam, Tanzania',
            'maintenance_mode' => false,
            'allow_registrations' => true,
            'require_email_verification' => true,
            'require_phone_verification' => false,
            'default_user_role' => 'winga',

            // Financial Settings
            'commission_rate' => 10,
            'min_withdrawal_amount' => 1000,
            'max_withdrawal_amount' => 1000000,
            'withdrawal_fee_rate' => 2,
            'auto_approve_withdrawals' => false,

            // Payment Gateway Settings
            'snippe_api_key' => '',
            'snippe_secret_key' => '',
            'snippe_webhook_url' => '',

            // Email Settings
            'email_driver' => config('mail.default', 'smtp'),
            'mail_host' => config('mail.mailers.smtp.host', ''),
            'mail_port' => config('mail.mailers.smtp.port', 587),
            'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_password' => '',
            'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
            'mail_from_address' => config('mail.from.address', ''),
            'mail_from_name' => config('mail.from.name', 'Winga Platform'),

            // Real-time Settings
            'pusher_app_id' => config('broadcasting.connections.pusher.app_id', ''),
            'pusher_app_key' => config('broadcasting.connections.pusher.key', ''),
            'pusher_app_secret' => config('broadcasting.connections.pusher.secret', ''),
            'pusher_app_cluster' => config('broadcasting.connections.pusher.options.cluster', 'mt1'),

            // Analytics Settings
            'google_analytics_id' => '',
            'facebook_pixel_id' => '',
            'google_maps_api_key' => '',
            'recaptcha_site_key' => '',
            'recaptcha_secret_key' => '',
            'enable_recaptcha' => false,

            // Security Settings
            'session_lifetime' => config('session.lifetime', 120),
            'max_login_attempts' => 5,
            'lockout_duration' => 1,
            'password_min_length' => 8,
            'enable_two_factor' => false,

            // System Settings
            'backup_enabled' => true,
            'backup_frequency' => 'daily',
            'backup_retention_days' => 30,
            'log_level' => 'error',
            'enable_performance_monitoring' => true,
            'enable_error_tracking' => true,
            'cache_driver' => config('cache.default', 'file'),
            'queue_driver' => config('queue.default', 'sync'),

            // Notification Settings
            'enable_notifications' => true,
            'notification_channels' => ['email', 'database'],
            'enable_sms_notifications' => false,
            'sms_provider' => 'twilio',
            'twilio_account_sid' => '',
            'twilio_auth_token' => '',
            'twilio_phone_number' => '',
            'africastalking_username' => '',
            'africastalking_api_key' => '',
            'infobip_api_key' => '',
            'infobip_base_url' => '',

            // Support Settings
            'enable_chat_support' => true,
            'support_email' => 'support@winga.co.tz',
            'support_phone' => '+255 712 345 678',
            'enable_live_chat' => false,
            'chat_widget_position' => 'bottom-right',
            'enable_help_center' => true,
            'help_center_url' => '/help',
            'enable_faq' => true,
            'enable_ticket_system' => true,
            'ticket_email' => 'tickets@winga.co.tz',

            'suspension_appeal_email' => '',
            'suspension_appeal_whatsapp' => '',
        ];
    }

    private function defineCategories(): void
    {
        $this->categories = [
            'general' => [
                'name' => 'General',
                'icon' => '⚙️',
                'description' => 'Basic platform settings',
                'settings' => [
                    'site_name', 'site_description', 'site_email', 'site_phone',
                    'site_address', 'maintenance_mode', 'allow_registrations',
                    'require_email_verification', 'require_phone_verification', 'default_user_role',
                ],
            ],
            'financial' => [
                'name' => 'Financial',
                'icon' => '💰',
                'description' => 'Payment and commission settings',
                'settings' => [
                    'commission_rate', 'min_withdrawal_amount', 'max_withdrawal_amount',
                    'withdrawal_fee_rate', 'auto_approve_withdrawals',
                ],
            ],
            'payment' => [
                'name' => 'Payment Gateway',
                'icon' => '💳',
                'description' => 'Payment provider configurations',
                'settings' => [
                    'snippe_api_key', 'snippe_secret_key', 'snippe_webhook_url',
                ],
            ],
            'email' => [
                'name' => 'Email',
                'icon' => '📧',
                'description' => 'Email configuration settings',
                'settings' => [
                    'email_driver', 'mail_host', 'mail_port', 'mail_username',
                    'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
                ],
            ],
            'realtime' => [
                'name' => 'Real-time',
                'icon' => '🔄',
                'description' => 'WebSocket and real-time features',
                'settings' => [
                    'pusher_app_id', 'pusher_app_key', 'pusher_app_secret', 'pusher_app_cluster',
                ],
            ],
            'analytics' => [
                'name' => 'Analytics',
                'icon' => '📊',
                'description' => 'Tracking and analytics settings',
                'settings' => [
                    'google_analytics_id', 'facebook_pixel_id', 'google_maps_api_key',
                    'recaptcha_site_key', 'recaptcha_secret_key', 'enable_recaptcha',
                ],
            ],
            'security' => [
                'name' => 'Security',
                'icon' => '🔒',
                'description' => 'Security and authentication settings',
                'settings' => [
                    'session_lifetime', 'max_login_attempts', 'lockout_duration',
                    'password_min_length', 'enable_two_factor',
                ],
            ],
            'system' => [
                'name' => 'System',
                'icon' => '🖥️',
                'description' => 'System and performance settings',
                'settings' => [
                    'backup_enabled', 'backup_frequency', 'backup_retention_days',
                    'log_level', 'enable_performance_monitoring', 'enable_error_tracking',
                    'cache_driver', 'queue_driver',
                ],
            ],
            'notifications' => [
                'name' => 'Notifications',
                'icon' => '🔔',
                'description' => 'Notification and SMS settings',
                'settings' => [
                    'enable_notifications', 'notification_channels', 'enable_sms_notifications',
                    'sms_provider', 'twilio_account_sid', 'twilio_auth_token', 'twilio_phone_number',
                    'africastalking_username', 'africastalking_api_key', 'infobip_api_key', 'infobip_base_url',
                ],
            ],
            'support' => [
                'name' => 'Support',
                'icon' => '🎧',
                'description' => 'Customer support settings',
                'settings' => [
                    'enable_chat_support', 'support_email', 'support_phone',
                    'suspension_appeal_email', 'suspension_appeal_whatsapp',
                    'enable_live_chat', 'chat_widget_position', 'enable_help_center',
                    'help_center_url', 'enable_faq', 'enable_ticket_system', 'ticket_email',
                ],
            ],
        ];
    }

    private function loadSettings(): void
    {
        foreach ($this->settings as $key => $default) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $this->settings[$key] = $this->castSettingValue($setting->value, $setting->type);
            } elseif ($key === 'commission_rate') {
                $this->settings[$key] = SettingsService::commissionRate();
            }
        }
    }

    private function castSettingValue($value, string $type)
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'float' => (float) $value,
            'array' => json_decode($value, true),
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    public function switchCategory(string $category): void
    {
        $this->activeCategory = $category;
    }

    public function saveSettings(): void
    {
        $this->validate();

        $changedSettings = [];
        $categorySettings = $this->categories[$this->activeCategory]['settings'] ?? [];

        foreach ($categorySettings as $key) {
            if (array_key_exists($key, $this->settings)) {
                $oldValue = Setting::where('key', $key)->value('value');
                $newValue = $this->settings[$key];

                $setting = Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => is_array($newValue) ? json_encode($newValue) : $newValue,
                        'type' => gettype($newValue),
                        'description' => $this->getSettingDescription($key),
                        'category' => $this->activeCategory,
                    ]
                );

                // Sync financial settings to SettingsService (PlatformSetting)
                $this->syncToSettingsService($key, $newValue);

                if ($oldValue !== $newValue) {
                    $changedSettings[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
            }
        }

        if (! empty($changedSettings)) {
            $this->logAdminAction('update_settings', null, [
                'category' => $this->activeCategory,
                'changes' => $changedSettings,
            ]);

            // Clear relevant caches
            $this->clearRelevantCaches($changedSettings);

            $this->dispatch('toast', message: 'Settings saved successfully', type: 'success');
        } else {
            $this->dispatch('toast', message: 'No changes to save', type: 'info');
        }
    }

    private function getSettingDescription(string $key): string
    {
        $descriptions = [
            'site_name' => 'Platform name displayed throughout the application',
            'site_description' => 'Platform description for SEO and meta tags',
            'site_email' => 'Primary contact email address',
            'site_phone' => 'Primary contact phone number',
            'site_address' => 'Physical business address',
            'maintenance_mode' => 'Put the site in maintenance mode',
            'allow_registrations' => 'Allow new user registrations',
            'require_email_verification' => 'Require email verification for new users',
            'require_phone_verification' => 'Require phone verification for new users',
            'default_user_role' => 'Default role for new registrations',
            'commission_rate' => 'Platform commission rate percentage',
            'min_withdrawal_amount' => 'Minimum withdrawal amount in TZS',
            'max_withdrawal_amount' => 'Maximum withdrawal amount in TZS',
            'withdrawal_fee_rate' => 'Withdrawal processing fee percentage',
            'auto_approve_withdrawals' => 'Automatically approve withdrawal requests',
            'snippe_api_key' => 'Snippe payment gateway API key',
            'snippe_secret_key' => 'Snippe payment gateway secret key',
            'snippe_webhook_url' => 'Webhook URL for Snippe callbacks',
            'email_driver' => 'Email service driver',
            'mail_host' => 'SMTP server hostname',
            'mail_port' => 'SMTP server port',
            'mail_username' => 'SMTP username',
            'mail_password' => 'SMTP password',
            'mail_encryption' => 'SMTP encryption method',
            'mail_from_address' => 'Default from email address',
            'mail_from_name' => 'Default from name',
            'pusher_app_id' => 'Pusher application ID',
            'pusher_app_key' => 'Pusher application key',
            'pusher_app_secret' => 'Pusher application secret',
            'pusher_app_cluster' => 'Pusher application cluster',
            'google_analytics_id' => 'Google Analytics tracking ID',
            'facebook_pixel_id' => 'Facebook Pixel ID',
            'google_maps_api_key' => 'Google Maps API key',
            'recaptcha_site_key' => 'reCAPTCHA site key',
            'recaptcha_secret_key' => 'reCAPTCHA secret key',
            'enable_recaptcha' => 'Enable reCAPTCHA protection',
            'session_lifetime' => 'User session lifetime in minutes',
            'max_login_attempts' => 'Maximum login attempts before lockout',
            'lockout_duration' => 'Account lockout duration in minutes',
            'password_min_length' => 'Minimum password length',
            'enable_two_factor' => 'Enable two-factor authentication',
            'backup_enabled' => 'Enable automatic backups',
            'backup_frequency' => 'Backup frequency',
            'backup_retention_days' => 'Number of days to retain backups',
            'log_level' => 'Minimum logging level',
            'enable_performance_monitoring' => 'Enable performance monitoring',
            'enable_error_tracking' => 'Enable error tracking',
            'cache_driver' => 'Cache driver',
            'queue_driver' => 'Queue driver',
            'enable_notifications' => 'Enable system notifications',
            'notification_channels' => 'Available notification channels',
            'enable_sms_notifications' => 'Enable SMS notifications',
            'sms_provider' => 'SMS service provider',
            'twilio_account_sid' => 'Twilio account SID',
            'twilio_auth_token' => 'Twilio authentication token',
            'twilio_phone_number' => 'Twilio phone number',
            'africastalking_username' => 'AfricasTalking username',
            'africastalking_api_key' => 'AfricasTalking API key',
            'infobip_api_key' => 'Infobip API key',
            'infobip_base_url' => 'Infobip base URL',
            'enable_chat_support' => 'Enable chat support',
            'support_email' => 'Support email address',
            'support_phone' => 'Support phone number',
            'enable_live_chat' => 'Enable live chat widget',
            'chat_widget_position' => 'Chat widget position',
            'enable_help_center' => 'Enable help center',
            'help_center_url' => 'Help center URL',
            'enable_faq' => 'Enable FAQ section',
            'enable_ticket_system' => 'Enable ticket system',
            'ticket_email' => 'Ticket system email',
            'suspension_appeal_email' => 'Email shown to suspended users for appeals (falls back to support email)',
            'suspension_appeal_whatsapp' => 'WhatsApp number for suspended-user appeals (falls back to support phone)',
        ];

        return $descriptions[$key] ?? '';
    }

    private function clearRelevantCaches(array $changedSettings): void
    {
        $cacheKeys = [];

        foreach (array_keys($changedSettings) as $key) {
            if (str_starts_with($key, 'site_')) {
                $cacheKeys[] = 'site_settings';
            } elseif (str_starts_with($key, 'mail_')) {
                $cacheKeys[] = 'mail_config';
            } elseif (str_starts_with($key, 'notification')) {
                $cacheKeys[] = 'notification_settings';
            }
        }

        foreach ($cacheKeys as $cacheKey) {
            Cache::forget($cacheKey);
        }
    }

    /**
     * Map admin Settings keys → SettingsService (PlatformSetting) keys so that
     * Payment::getPlatformFeePercent() always reflects the admin-configured value.
     */
    private function syncToSettingsService(string $key, mixed $value): void
    {
        $map = [
            'commission_rate' => ['platform_commission_rate', 'float'],
            'min_withdrawal_amount' => ['min_withdrawal_amount', 'integer'],
            'max_withdrawal_amount' => ['max_withdrawal_daily', 'integer'],
        ];

        if (isset($map[$key])) {
            [$platformKey, $type] = $map[$key];
            SettingsService::set(
                $platformKey,
                match ($type) {
                    'float' => (float) $value,
                    'integer' => (int) $value,
                    default => $value,
                },
                $type,
                'financial',
                auth()->id()
            );
        }
    }

    public function testEmailConfiguration(): void
    {
        try {
            // Test email configuration by sending a test email
            \Mail::raw('This is a test email to verify your email configuration.', function ($message) {
                $message->to($this->settings['site_email'])
                    ->subject('Email Configuration Test - Winga Platform');
            });

            $this->dispatch('toast', message: 'Test email sent successfully', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Email test failed: '.$e->getMessage(), type: 'error');
        }
    }

    public function testSmsConfiguration(): void
    {
        try {
            // Test SMS configuration based on provider
            $provider = $this->settings['sms_provider'];

            // Implementation would depend on the SMS provider
            $this->dispatch('toast', message: "SMS test for {$provider} not implemented yet", type: 'info');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'SMS test failed: '.$e->getMessage(), type: 'error');
        }
    }

    public function clearCache(): void
    {
        Cache::flush();
        $this->dispatch('toast', message: 'Application cache cleared', type: 'success');
    }

    public function runBackup(): void
    {
        try {
            // Trigger backup process
            \Artisan::call('backup:run');
            $this->dispatch('toast', message: 'Backup initiated successfully', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Backup failed: '.$e->getMessage(), type: 'error');
        }
    }

    public function exportSettings(): void
    {
        $settings = Setting::all()->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'type' => $setting->type,
                'category' => $setting->category,
                'description' => $setting->description,
                'updated_at' => $setting->updated_at,
            ];
        });

        $json = json_encode($settings, JSON_PRETTY_PRINT);
        $this->dispatch('download', data: $json, filename: 'settings_export.json');
    }

    public function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'database_connection' => config('database.default'),
            'mail_driver' => config('mail.default'),
            'storage_disk' => config('filesystems.default'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'url' => config('app.url'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.settings', [
            'systemInfo' => $this->getSystemInfo(),
        ])
            ->layout('layouts.admin')
            ->title('Settings Management');
    }
}
