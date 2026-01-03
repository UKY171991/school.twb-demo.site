<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Data Loss Issue Debug ===\n";

// Check current data state
echo "Current Teachers: " . \App\Models\Teacher::count() . "\n";
echo "Current Grades: " . \App\Models\Grade::count() . "\n";
echo "Current Students: " . \App\Models\Student::count() . "\n";
echo "Current Schools: " . \App\Models\School::count() . "\n";

// Check if there are any scheduled jobs or commands that might be clearing data
echo "\nChecking for potential issues:\n";

// Check if DatabaseSeeder is configured to run automatically
$databaseSeeder = file_get_contents('database/seeders/DatabaseSeeder.php');
if (strpos($databaseSeeder, 'ComprehensiveDummyDataSeeder') !== false) {
    echo "⚠️  Warning: DatabaseSeeder still references removed seeder\n";
}

// Check for any artisan commands that might be running
echo "Checking for any auto-seeding configurations...\n";

// Check the DatabaseSeeder content
echo "\nCurrent DatabaseSeeder content:\n";
echo substr($databaseSeeder, 0, 500) . "...\n";

// Check if there are any observers or models that might be deleting data
echo "\nChecking model relationships...\n";

// Test creating a teacher to see if it persists
echo "\nTesting data persistence...\n";
$testTeacher = \App\Models\Teacher::create([
    'name' => 'Test Teacher ' . time(),
    'email' => 'test' . time() . '@test.com',
    'gender' => 'male',
    'school_id' => 1,
]);

echo "Created test teacher with ID: " . $testTeacher->id . "\n";
echo "Teachers after creation: " . \App\Models\Teacher::count() . "\n";

// Check if the teacher still exists after a short delay
sleep(2);
$stillExists = \App\Models\Teacher::find($testTeacher->id);
if ($stillExists) {
    echo "✅ Test teacher still exists - data persistence OK\n";
    // Clean up
    $testTeacher->delete();
} else {
    echo "❌ Test teacher was deleted - there's a data deletion issue!\n";
}
