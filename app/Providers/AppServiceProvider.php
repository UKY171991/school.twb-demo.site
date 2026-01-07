<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();

        // Automatically set debug mode based on environment
        if (function_exists('is_development')) {
            config(['app.debug' => is_development()]);

            // Share environment mode with all views
            view()->share('appMode', get_app_mode());
        }

        Gate::define('manage-users', function (User $user) {
            return in_array($user->role, ['master', 'admin']);
        });
    }
}
