<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Support\ServiceProvider;

class AdminLTEServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share dynamic settings with all views
        view()->composer('*', function ($view) {
            try {
                $schoolName = SystemSetting::get('school_name', 'SchoolMS');
                $schoolLogo = SystemSetting::get('school_logo', 'vendor/adminlte/dist/img/AdminLTELogo.png');
                $schoolFavicon = SystemSetting::get('school_favicon');

                $view->with([
                    'dynamicSchoolName' => $schoolName,
                    'dynamicSchoolLogo' => $schoolLogo,
                    'dynamicSchoolFavicon' => $schoolFavicon,
                ]);
            } catch (\Exception $e) {
                // Handle case where database is not yet migrated
                $view->with([
                    'dynamicSchoolName' => 'SchoolMS',
                    'dynamicSchoolLogo' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
                    'dynamicSchoolFavicon' => null,
                ]);
            }
        });

        // Override AdminLTE configuration dynamically
        try {
            $schoolName = SystemSetting::get('school_name', 'SchoolMS');
            $schoolLogo = SystemSetting::get('school_logo');

            if ($schoolName) {
                config(['adminlte.title' => $schoolName]);
                config(['adminlte.logo' => '<b>'.$schoolName.'</b>']);
            }

            if ($schoolLogo) {
                config(['adminlte.logo_img' => $schoolLogo]);
                config(['adminlte.auth_logo.img.path' => $schoolLogo]);
            }
        } catch (\Exception $e) {
            // Ignore errors during boot
        }
    }
}
