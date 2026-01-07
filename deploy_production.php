<?php

/**
 * Production Deployment Script
 * 
 * This script automatically sets up the production database and environment.
 * Run this on your production server after deploying the code.
 * 
 * Usage: php deploy_production.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Production Deployment Script ===\n\n";

// Check if we're in production environment
if (env('APP_ENV') !== 'production') {
    echo "⚠️  Warning: APP_ENV is not set to 'production'\n";
    echo "   Current APP_ENV: " . env('APP_ENV', 'not set') . "\n";
    echo "   Setting APP_ENV to production for deployment...\n";
    
    // Update .env file
    $envFile = file_get_contents('.env');
    $envFile = preg_replace('/^APP_ENV=.*$/m', 'APP_ENV=production', $envFile);
    file_put_contents('.env', $envFile);
    
    echo "✓ APP_ENV set to production\n";
}

// Clear configuration cache
echo "Clearing configuration cache...\n";
\Illuminate\Support\Facades\Artisan::call('config:clear');
echo "✓ Configuration cache cleared\n";

// Check database connection
echo "\nChecking database connection...\n";
try {
    \Illuminate\Support\Facades\DB::connection('mysql')->getPdo();
    echo "✓ MySQL connection successful\n";
} catch (\Exception $e) {
    echo "❌ MySQL connection failed: " . $e->getMessage() . "\n";
    echo "Please check your MySQL credentials in .env\n";
    exit(1);
}

// Run migrations
echo "\nRunning database migrations...\n";
try {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "✓ Migrations completed\n";
} catch (\Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Create master user if not exists
echo "\nSetting up master user...\n";
$masterUser = \App\Models\User::where('email', 'umakant171991@gmail.com')->first();
if (!$masterUser) {
    \App\Models\User::create([
        'name' => 'Umakant',
        'email' => 'umakant171991@gmail.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role' => 'master',
    ]);
    echo "✓ Master user created: umakant171991@gmail.com\n";
} else {
    echo "✓ Master user already exists\n";
}

// Create default school if not exists
echo "\nSetting up default school...\n";
$school = \App\Models\School::where('code', 'DEFAULT')->first();
if (!$school) {
    $school = \App\Models\School::create([
        'name' => 'Default School',
        'code' => 'DEFAULT',
        'address' => 'Production Server Address',
        'phone' => '+1234567890',
        'email' => 'admin@school.com',
        'status' => 'active',
    ]);
    echo "✓ Default school created\n";
} else {
    echo "✓ Default school already exists\n";
}

// Clear caches
echo "\nClearing all caches...\n";
\Illuminate\Support\Facades\Artisan::call('cache:clear');
\Illuminate\Support\Facades\Artisan::call('view:clear');
\Illuminate\Support\Facades\Artisan::call('route:clear');
echo "✓ All caches cleared\n";

// Optimize for production
echo "\nOptimizing for production...\n";
\Illuminate\Support\Facades\Artisan::call('config:cache');
\Illuminate\Support\Facades\Artisan::call('route:cache');
\Illuminate\Support\Facades\Artisan::call('view:cache');
echo "✓ Production optimization completed\n";

echo "\n=== Deployment Complete! ===\n";
echo "✓ Environment: Production\n";
echo "✓ Database: MySQL\n";
echo "✓ Master User: umakant171991@gmail.com / password\n";
echo "✓ Default School: " . ($school->name ?? 'N/A') . "\n";
echo "✓ Application optimized for production\n";
echo "\n🚀 Your application is ready for production use!\n";
echo "📝 Don't forget to change the default password after first login\n";
