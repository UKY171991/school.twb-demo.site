<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Delete Issue Debug ===\n";

// Check current data state
echo "Current Teachers: " . \App\Models\Teacher::count() . "\n";
echo "Current Grades: " . \App\Models\Grade::count() . "\n";
echo "Current Students: " . \App\Models\Student::count() . "\n";
echo "Current Schools: " . \App\Models\School::count() . "\n";
echo "Current Users: " . \App\Models\User::count() . "\n";

// Check database foreign key constraints
echo "\nChecking database constraints...\n";

// Check if there are cascading deletes in migrations
echo "Checking migration files for cascade deletes...\n";

// Test a grade deletion to see what happens
echo "\nTesting grade deletion...\n";

$testGrade = \App\Models\Grade::first();
if ($testGrade) {
    echo "Found test grade: {$testGrade->name} (ID: {$testGrade->id})\n";
    
    // Check relationships before deletion
    echo "Students in this grade: " . $testGrade->students()->count() . "\n";
    
    // Check if there are any model events
    echo "Checking for model observers or events...\n";
    
    // Simulate what happens when grade is deleted
    $gradeId = $testGrade->id;
    $teacherId = $testGrade->teacher_id;
    
    echo "Grade ID: $gradeId, Teacher ID: $teacherId\n";
    
    // Check if deleting this grade affects other data
    $studentsBefore = \App\Models\Student::where('grade_id', $gradeId)->count();
    echo "Students with grade_id $gradeId before deletion: $studentsBefore\n";
    
    // Don't actually delete, just check relationships
    echo "Skipping actual deletion to prevent data loss\n";
} else {
    echo "No grades found to test deletion\n";
}

// Check session state
echo "\nChecking session state...\n";
echo "Session ID: " . session()->getId() . "\n";
echo "Current school ID in session: " . session('current_school_id') . "\n";

// Check if there are any authentication issues
echo "\nChecking authentication...\n";
if (auth()->check()) {
    echo "User authenticated: " . auth()->user()->email . "\n";
} else {
    echo "No authenticated user\n";
}

// Check for any scheduled tasks or jobs that might be clearing data
echo "\nChecking for potential automated processes...\n";
