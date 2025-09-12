<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General settings
        Setting::set('app_name', 'Proper Automobile', 'general');
        Setting::set('app_description', 'Premier luxury car dealership platform', 'general');
        Setting::set('contact_email', 'info@proper-automobile.com', 'general');
        Setting::set('contact_phone', '+234 (703) 948-1762', 'general');
        Setting::set('address', '123 Luxury Auto Lane, Premium City, PC 12345', 'general');
        Setting::set('business_hours', 'Mon-Fri 9AM-7PM, Sat 9AM-5PM, Sun Closed', 'general');
        Setting::set('timezone', 'UTC', 'general');
        Setting::set('currency', 'USD', 'general');
        Setting::set('max_images_per_automobile', 10, 'general', 'integer');
        Setting::set('max_image_size_mb', 2, 'general', 'integer');

        // Email settings
        Setting::set('mail_driver', 'smtp', 'email');
        Setting::set('smtp_host', 'localhost', 'email');
        Setting::set('smtp_port', 587, 'email', 'integer');
        Setting::set('smtp_username', '', 'email');
        Setting::set('smtp_encryption', 'tls', 'email');
        Setting::set('from_name', 'Proper Automobile', 'email');
        Setting::set('from_address', 'info@proper-automobile.com', 'email');
        Setting::set('notifications_enabled', true, 'email', 'boolean');
        Setting::set('booking_confirmations', true, 'email', 'boolean');
        Setting::set('transaction_receipts', true, 'email', 'boolean');
        Setting::set('welcome_emails', true, 'email', 'boolean');

        // Storage settings
        Setting::set('default_disk', 'local', 'storage');
        Setting::set('storage_limit_gb', 50, 'storage', 'integer');
        Setting::set('auto_cleanup_enabled', true, 'storage', 'boolean');
        Setting::set('cleanup_days', 90, 'storage', 'integer');
        Setting::set('backup_enabled', false, 'storage', 'boolean');
        Setting::set('backup_frequency', 'weekly', 'storage');

        // Security settings
        Setting::set('force_https', false, 'security', 'boolean');
        Setting::set('session_lifetime', 120, 'security', 'integer');
        Setting::set('password_min_length', 5, 'security', 'integer');
        Setting::set('require_email_verification', true, 'security', 'boolean');
        Setting::set('max_login_attempts', 5, 'security', 'integer');
        Setting::set('lockout_duration', 15, 'security', 'integer');
        Setting::set('two_factor_enabled', false, 'security', 'boolean');
        Setting::set('audit_logs_enabled', true, 'security', 'boolean');
        Setting::set('ip_whitelist_enabled', false, 'security', 'boolean');
        Setting::set('maintenance_mode', false, 'security', 'boolean');

        // Feature settings
        Setting::set('user_registration', true, 'features', 'boolean');
        Setting::set('guest_browsing', true, 'features', 'boolean');
        Setting::set('test_drive_booking', true, 'features', 'boolean');
        Setting::set('online_payments', true, 'features', 'boolean');
        Setting::set('dealer_applications', true, 'features', 'boolean');
        Setting::set('automated_emails', true, 'features', 'boolean');
        Setting::set('search_filters', true, 'features', 'boolean');
        Setting::set('image_optimization', true, 'features', 'boolean');
        Setting::set('analytics_tracking', true, 'features', 'boolean');
        Setting::set('mobile_app_api', false, 'features', 'boolean');
        Setting::set('multi_language', false, 'features', 'boolean');
        Setting::set('dark_mode', false, 'features', 'boolean');
    }
}
