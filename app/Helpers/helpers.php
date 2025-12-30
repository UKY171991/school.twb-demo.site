<?php

if (!function_exists('get_app_mode')) {
    /**
     * Get the current application mode (development or production).
     * Automatically detects based on host and server address.
     * 
     * @return string
     */
    function get_app_mode()
    {
        // Check if running in console (Artisan)
        if (app()->runningInConsole()) {
            return app()->environment('local') ? 'development' : 'production';
        }

        $host = $_SERVER['HTTP_HOST'] ?? '';
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';

        // Localhost detection
        $isLocal = in_array($remoteAddr, ['127.0.0.1', '::1']) || 
                   str_contains($host, 'localhost') || 
                   str_contains($host, '127.0.0.1') ||
                   str_contains($host, '.test') ||
                   str_contains($host, 'twb-demo.site'); // Based on current project name

        // Since the user is using school.twb-demo.site, if it's running locally they might be using that domain in hosts
        // But usually "live" means the actual server.
        
        // Let's use a more standard detection plus explicit environment check
        if (app()->environment('local') || $isLocal) {
            return 'development';
        }

        return 'production';
    }
}

if (!function_exists('is_development')) {
    /**
     * Check if the application is in development mode.
     * 
     * @return bool
     */
    function is_development()
    {
        return get_app_mode() === 'development';
    }
}

if (!function_exists('is_production')) {
    /**
     * Check if the application is in production mode.
     * 
     * @return bool
     */
    function is_production()
    {
        return get_app_mode() === 'production';
    }
}
