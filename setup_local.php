<?php

/**
 * Local Development Setup Script
 * 
 * This script automatically sets up the local development environment.
 * Run this on your local machine for initial setup.
 * 
 * Usage: php setup_local.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Local Development Setup ===\n\n";

// Ensure we're in local environment
if (env('APP_ENV') !== 'local') {
    echo "Setting up local environment...\n";
    
    // Update .env file for local development
    $envFile = file_get_contents('.env');
    $envFile = preg_replace('/^APP_ENV=.*$/m', 'APP_ENV=local', $envFile);
    $envFile = preg_replace('/^APP_DEBUG=.*$/m', 'APP_DEBUG=true', $envFile);
    file_put_contents('.env', $envFile);
    
    echo "✓ Local environment configured\n";
}

// Clear configuration cache
echo "Clearing configuration cache...\n";
\Illuminate\Support\Facades\Artisan::call('config:clear');
echo "✓ Configuration cache cleared\n";

// Ensure SQLite database file exists
if (!file_exists('database/database.sqlite')) {
    echo "Creating SQLite database file...\n";
    touch('database/database.sqlite');
    echo "✓ SQLite database created\n";
} else {
    echo "✓ SQLite database already exists\n";
}

// Run migrations
echo "\nRunning database migrations...\n";
try {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh');
    echo "✓ Migrations completed\n";
} catch (\Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Create master user
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

// Create default school
echo "\nSetting up default school...\n";
$school = \App\Models\School::where('code', 'DEFAULT')->first();
if (!$school) {
    $school = \App\Models\School::create([
        'name' => 'Default School',
        'code' => 'DEFAULT',
        'address' => 'Local Development Address',
        'phone' => '+1234567890',
        'email' => 'admin@localhost.com',
        'status' => 'active',
    ]);
    echo "✓ Default school created\n";
} else {
    echo "✓ Default school already exists\n";
}

// Create sample data for testing
echo "\nCreating sample data...\n";

// Create sample grade
$grade = \App\Models\Grade::where('school_id', $school->id)->first();
if (!$grade) {
    \App\Models\Grade::create([
        'name' => 'Class 1',
        'section' => 'A',
        'capacity' => 40,
        'status' => 'active',
        'school_id' => $school->id,
    ]);
    echo "✓ Sample grade created\n";
} else {
    echo "✓ Sample grade already exists\n";
}

// Create sample subject
$subject = \App\Models\Subject::where('school_id', $school->id)->first();
if (!$subject) {
    \App\Models\Subject::create([
        'name' => 'Mathematics',
        'code' => 'MATH101',
        'full_marks' => 100,
        'pass_marks' => 33,
        'type' => 'theory',
        'status' => 'active',
        'school_id' => $school->id,
    ]);
    echo "✓ Sample subject created\n";
} else {
    echo "✓ Sample subject already exists\n";
}

echo "\n=== Local Setup Complete! ===\n";
echo "✓ Environment: Local Development\n";
echo "✓ Database: SQLite\n";
echo "✓ Master User: umakant171991@gmail.com / password\n";
echo "✓ Default School: " . $school->name . "\n";
echo "✓ Sample data created for testing\n";
echo "\n🚀 Run 'php artisan serve' to start the development server\n";
echo "🌐 Application will be available at: http://127.0.0.1:8000\n";
echo "📝 Login with umakant171991@gmail.com / password\n";
