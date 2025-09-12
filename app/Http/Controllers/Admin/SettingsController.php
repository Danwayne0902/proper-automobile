<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Display the settings dashboard.
     */
    public function index()
    {
        $settings = [
            'general' => $this->getGeneralSettings(),
            'email' => $this->getEmailSettings(),
            'storage' => $this->getStorageSettings(),
            'security' => $this->getSecuritySettings(),
            'features' => $this->getFeatureSettings()
        ];

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings
        ]);
    }

    /**
     * Update system settings.
     */
    public function update(Request $request)
    {
        $category = $request->input('category');

        switch ($category) {
            case 'general':
                $this->updateGeneralSettings($request);
                break;
            case 'email':
                $this->updateEmailSettings($request);
                break;
            case 'storage':
                $this->updateStorageSettings($request);
                break;
            case 'security':
                $this->updateSecuritySettings($request);
                break;
            case 'features':
                $this->updateFeatureSettings($request);
                break;
            default:
                return back()->with('error', 'Invalid settings category.');
        }

        // Clear settings cache
        Cache::forget('app_settings');

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        // Clear different types of cache
        Cache::flush();

        // You can also clear specific caches
        // Artisan::call('cache:clear');
        // Artisan::call('config:clear');
        // Artisan::call('view:clear');
        // Artisan::call('route:clear');

        return back()->with('success', 'Application cache cleared successfully.');
    }

    private function getGeneralSettings()
    {
        return [
            'app_name' => Setting::get('app_name', config('app.name', 'Proper Automobile')),
            'app_description' => Setting::get('app_description', 'Premier luxury car dealership platform'),
            'contact_email' => Setting::get('contact_email', 'info@proper-automobile.com'),
            'contact_phone' => Setting::get('contact_phone', '+1 (555) 123-4567'),
            'address' => Setting::get('address', '123 Luxury Auto Lane, Premium City, PC 12345'),
            'business_hours' => Setting::get('business_hours', 'Mon-Fri 9AM-7PM, Sat 9AM-5PM, Sun Closed'),
            'timezone' => Setting::get('timezone', config('app.timezone', 'UTC')),
            'currency' => Setting::get('currency', 'USD'),
            'max_images_per_automobile' => (int) Setting::get('max_images_per_automobile', 10),
            'max_image_size_mb' => (int) Setting::get('max_image_size_mb', 2),
            'admin_color_palette' => Setting::get('admin_color_palette', 'Default')
        ];
    }

    private function getEmailSettings()
    {
        return [
            'mail_driver' => Setting::get('mail_driver', config('mail.default', 'smtp')),
            'smtp_host' => Setting::get('smtp_host', config('mail.mailers.smtp.host')),
            'smtp_port' => Setting::get('smtp_port', config('mail.mailers.smtp.port')),
            'smtp_username' => Setting::get('smtp_username', config('mail.mailers.smtp.username')),
            'smtp_encryption' => Setting::get('smtp_encryption', config('mail.mailers.smtp.encryption')),
            'from_name' => Setting::get('from_name', config('mail.from.name')),
            'from_address' => Setting::get('from_address', config('mail.from.address')),
            'notifications_enabled' => (bool) Setting::get('notifications_enabled', true),
            'booking_confirmations' => (bool) Setting::get('booking_confirmations', true),
            'transaction_receipts' => (bool) Setting::get('transaction_receipts', true),
            'welcome_emails' => (bool) Setting::get('welcome_emails', true)
        ];
    }

    private function getStorageSettings()
    {
        $diskUsage = $this->getDiskUsage();

        return [
            'default_disk' => Setting::get('default_disk', config('filesystems.default')),
            'public_disk_usage' => $diskUsage['public'],
            'total_images' => $this->countImages(),
            'storage_limit_gb' => (int) Setting::get('storage_limit_gb', 50), // 50GB limit
            'auto_cleanup_enabled' => (bool) Setting::get('auto_cleanup_enabled', true),
            'cleanup_days' => (int) Setting::get('cleanup_days', 90),
            'backup_enabled' => (bool) Setting::get('backup_enabled', false),
            'backup_frequency' => Setting::get('backup_frequency', 'weekly')
        ];
    }

    private function getSecuritySettings()
    {
        return [
            'force_https' => (bool) Setting::get('force_https', config('app.env') === 'production'),
            'session_lifetime' => Setting::get('session_lifetime', config('session.lifetime')),
            'password_min_length' => (int) Setting::get('password_min_length', 8),
            'require_email_verification' => (bool) Setting::get('require_email_verification', true),
            'max_login_attempts' => (int) Setting::get('max_login_attempts', 5),
            'lockout_duration' => (int) Setting::get('lockout_duration', 15), // minutes
            'two_factor_enabled' => (bool) Setting::get('two_factor_enabled', false),
            'audit_logs_enabled' => (bool) Setting::get('audit_logs_enabled', true),
            'ip_whitelist_enabled' => (bool) Setting::get('ip_whitelist_enabled', false),
            'maintenance_mode' => (bool) Setting::get('maintenance_mode', false)
        ];
    }

    private function getFeatureSettings()
    {
        return [
            'user_registration' => (bool) Setting::get('user_registration', true),
            'guest_browsing' => (bool) Setting::get('guest_browsing', true),
            'test_drive_booking' => (bool) Setting::get('test_drive_booking', true),
            'online_payments' => (bool) Setting::get('online_payments', true),
            'dealer_applications' => (bool) Setting::get('dealer_applications', true),
            'automated_emails' => (bool) Setting::get('automated_emails', true),
            'search_filters' => (bool) Setting::get('search_filters', true),
            'image_optimization' => (bool) Setting::get('image_optimization', true),
            'analytics_tracking' => (bool) Setting::get('analytics_tracking', true),
            'mobile_app_api' => (bool) Setting::get('mobile_app_api', false),
            'multi_language' => (bool) Setting::get('multi_language', false),
            'dark_mode' => (bool) Setting::get('dark_mode', false)
        ];
    }

    private function updateGeneralSettings($request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'required|string|max:500',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'business_hours' => 'required|string|max:255',
            'timezone' => 'required|string',
            'currency' => 'required|string|size:3',
            'max_images_per_automobile' => 'required|integer|min:1|max:20',
            'max_image_size_mb' => 'required|integer|min:1|max:10',
            'admin_color_palette' => 'nullable|string|max:50'
        ]);

        // Save to database
        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'general');
        }
    }

    private function updateEmailSettings($request)
    {
        $validated = $request->validate([
            'notifications_enabled' => 'boolean',
            'booking_confirmations' => 'boolean',
            'transaction_receipts' => 'boolean',
            'welcome_emails' => 'boolean'
        ]);

        // Save to database
        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'email', 'boolean');
        }
    }

    private function updateStorageSettings($request)
    {
        $validated = $request->validate([
            'auto_cleanup_enabled' => 'boolean',
            'cleanup_days' => 'required|integer|min:30|max:365',
            'backup_enabled' => 'boolean',
            'backup_frequency' => 'required|in:daily,weekly,monthly'
        ]);

        // Save to database
        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'storage', gettype($value));
        }
    }

    private function updateSecuritySettings($request)
    {
        $validated = $request->validate([
            'password_min_length' => 'required|integer|min:6|max:20',
            'require_email_verification' => 'boolean',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:5|max:60',
            'two_factor_enabled' => 'boolean',
            'audit_logs_enabled' => 'boolean',
            'ip_whitelist_enabled' => 'boolean'
        ]);

        // Save to database
        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'security', gettype($value));
        }
    }

    private function updateFeatureSettings($request)
    {
        $validated = $request->validate([
            'user_registration' => 'boolean',
            'guest_browsing' => 'boolean',
            'test_drive_booking' => 'boolean',
            'online_payments' => 'boolean',
            'dealer_applications' => 'boolean',
            'automated_emails' => 'boolean',
            'search_filters' => 'boolean',
            'image_optimization' => 'boolean',
            'analytics_tracking' => 'boolean',
            'mobile_app_api' => 'boolean',
            'multi_language' => 'boolean',
            'dark_mode' => 'boolean'
        ]);

        // Save to database
        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'features', 'boolean');
        }
    }

    private function getDiskUsage()
    {
        try {
            $publicPath = storage_path('app/public');
            $size = 0;

            if (is_dir($publicPath)) {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($publicPath)
                );

                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $size += $file->getSize();
                    }
                }
            }

            return [
                'public' => [
                    'used_bytes' => $size,
                    'used_mb' => round($size / 1024 / 1024, 2),
                    'used_gb' => round($size / 1024 / 1024 / 1024, 2)
                ]
            ];
        } catch (\Exception $e) {
            return [
                'public' => [
                    'used_bytes' => 0,
                    'used_mb' => 0,
                    'used_gb' => 0
                ]
            ];
        }
    }

    private function countImages()
    {
        try {
            $automobileImages = Storage::disk('public')->files('automobiles');
            $demoImages = Storage::disk('public')->files('demo');

            return count($automobileImages) + count($demoImages);
        } catch (\Exception $e) {
            return 0;
        }
    }
}
