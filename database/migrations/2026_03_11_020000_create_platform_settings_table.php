<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->enum('type', ['string', 'integer', 'boolean', 'json', 'float'])->default('string');
            $table->enum('group', [
                'general', 'payment', 'security', 'notifications', 
                'subscription', 'smart_match', 'content', 'maintenance'
            ])->default('general');
            $table->text('description')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['group', 'key']);
        });

        // Insert default settings
        DB::table('platform_settings')->insert([
            // General Settings
            ['key' => 'platform_name', 'value' => 'Winga', 'type' => 'string', 'group' => 'general', 'description' => 'Platform display name'],
            ['key' => 'platform_url', 'value' => 'https://winga.co.tz', 'type' => 'string', 'group' => 'general', 'description' => 'Platform base URL'],
            ['key' => 'support_email', 'value' => 'support@winga.co.tz', 'type' => 'string', 'group' => 'general', 'description' => 'Support email address'],
            ['key' => 'support_phone', 'value' => '+255123456789', 'type' => 'string', 'group' => 'general', 'description' => 'Support phone number'],
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group' => 'general', 'description' => 'Enable maintenance mode'],
            ['key' => 'maintenance_message', 'value' => 'Tunafanya matengenezo. Tafadhali jaribu tena baada ya dakika chache.', 'type' => 'string', 'group' => 'general', 'description' => 'Maintenance mode message'],
            ['key' => 'allow_registrations', 'value' => 'true', 'type' => 'boolean', 'group' => 'general', 'description' => 'Allow new user registrations'],

            // Payment Settings
            ['key' => 'snippe_api_key', 'value' => '', 'type' => 'string', 'group' => 'payment', 'description' => 'Snippe API key for payments'],
            ['key' => 'platform_commission_rate', 'value' => '10', 'type' => 'float', 'group' => 'payment', 'description' => 'Platform commission percentage'],
            ['key' => 'min_withdrawal_amount', 'value' => '5000', 'type' => 'integer', 'group' => 'payment', 'description' => 'Minimum withdrawal amount in TZS'],
            ['key' => 'max_withdrawal_daily', 'value' => '1000000', 'type' => 'integer', 'group' => 'payment', 'description' => 'Maximum withdrawal amount per day in TZS'],
            ['key' => 'min_deposit_amount', 'value' => '1000', 'type' => 'integer', 'group' => 'payment', 'description' => 'Minimum deposit amount in TZS'],
            ['key' => 'auto_payout_delay_hours', 'value' => '24', 'type' => 'integer', 'group' => 'payment', 'description' => 'Auto-payout delay after code verification (hours)'],
            ['key' => 'escrow_auto_release_days', 'value' => '7', 'type' => 'integer', 'group' => 'payment', 'description' => 'Auto-release escrow after X days of inactivity'],

            // Security Settings
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'description' => 'Maximum login attempts before lockout'],
            ['key' => 'session_timeout_minutes', 'value' => '1440', 'type' => 'integer', 'group' => 'security', 'description' => 'Session timeout in minutes'],
            ['key' => 'admin_ip_whitelist', 'value' => '[]', 'type' => 'json', 'group' => 'security', 'description' => 'Allowed IP addresses for admin panel (JSON array)'],
            ['key' => 'force_admin_2fa', 'value' => 'false', 'type' => 'boolean', 'group' => 'security', 'description' => 'Force 2FA for all admin users'],
            ['key' => 'phone_block_patterns', 'value' => '[]', 'type' => 'json', 'group' => 'security', 'description' => 'Phone number patterns to block (JSON array of regex)'],

            // Notification Settings
            ['key' => 'email_notifications_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Enable email notifications'],
            ['key' => 'sms_notifications_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Enable SMS notifications'],
            ['key' => 'admin_alert_email', 'value' => 'admin@winga.co.tz', 'type' => 'string', 'group' => 'notifications', 'description' => 'Admin alert email'],
            ['key' => 'alert_on_failed_payouts', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Send admin alert on failed payouts'],
            ['key' => 'alert_on_disputes', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Send admin alert on new disputes'],
            ['key' => 'alert_on_suspicious_activity', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Send admin alert on suspicious activity'],

            // Subscription Settings
            ['key' => 'subscriptions_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'subscription', 'description' => 'Enable subscription system'],
            ['key' => 'msingi_price', 'value' => '15000', 'type' => 'integer', 'group' => 'subscription', 'description' => 'Msingi plan price in TZS'],
            ['key' => 'kawaida_price', 'value' => '45000', 'type' => 'integer', 'group' => 'subscription', 'description' => 'Kawaida plan price in TZS'],
            ['key' => 'bora_price', 'value' => '120000', 'type' => 'integer', 'group' => 'subscription', 'description' => 'Bora plan price in TZS'],
            ['key' => 'free_max_services', 'value' => '1', 'type' => 'integer', 'group' => 'subscription', 'description' => 'Free tier max services per month'],
            ['key' => 'free_max_daily_bids', 'value' => '3', 'type' => 'integer', 'group' => 'subscription', 'description' => 'Free tier max daily bids'],
            ['key' => 'free_max_portfolio_images', 'value' => '5', 'type' => 'integer', 'group' => 'subscription', 'description' => 'Free tier max portfolio images'],

            // Smart Match Settings
            ['key' => 'msingi_boost_points', 'value' => '10', 'type' => 'integer', 'group' => 'smart_match', 'description' => 'Msingi plan search boost points'],
            ['key' => 'kawaida_boost_points', 'value' => '25', 'type' => 'integer', 'group' => 'smart_match', 'description' => 'Kawaida plan search boost points'],
            ['key' => 'bora_boost_points', 'value' => '50', 'type' => 'integer', 'group' => 'smart_match', 'description' => 'Bora plan search boost points'],
            ['key' => 'msingi_notification_delay', 'value' => '60', 'type' => 'integer', 'group' => 'smart_match', 'description' => 'Msingi plan notification delay (minutes)'],
            ['key' => 'kawaida_notification_delay', 'value' => '15', 'type' => 'integer', 'group' => 'smart_match', 'description' => 'Kawaida plan notification delay (minutes)'],
            ['key' => 'bora_notification_delay', 'value' => '0', 'type' => 'integer', 'group' => 'smart_match', 'description' => 'Bora plan notification delay (minutes)'],
            ['key' => 'max_matching_distance_km', 'value' => '100', 'type' => 'integer', 'group' => 'smart_match', 'description' => 'Maximum distance for job matching in km'],

            // Content Settings
            ['key' => 'job_approval_required', 'value' => 'true', 'type' => 'boolean', 'group' => 'content', 'description' => 'Require admin approval for new jobs'],
            ['key' => 'auto_approve_verified_users', 'value' => 'true', 'type' => 'boolean', 'group' => 'content', 'description' => 'Auto-approve jobs from verified users'],
            ['key' => 'block_phone_in_descriptions', 'value' => 'true', 'type' => 'boolean', 'group' => 'content', 'description' => 'Block phone numbers in job descriptions'],
            ['key' => 'block_urls_in_descriptions', 'value' => 'true', 'type' => 'boolean', 'group' => 'content', 'description' => 'Block URLs in job descriptions'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
