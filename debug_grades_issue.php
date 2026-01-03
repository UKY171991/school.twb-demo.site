<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Debug Grades Issue ===\n";

// Check session
echo "Session School ID: " . session('current_school_id') . "\n";

// Check all grades in database
echo "All grades in database: " . App\Models\Grade::count() . "\n";

// Check grades by school
$allGrades = App\Models\Grade::all();
echo "Grades by school:\n";
foreach ($allGrades as $grade) {
    echo "- Grade: {$grade->name} (School ID: {$grade->school_id})\n";
}

// Check schools
echo "Schools in database: " . App\Models\School::count() . "\n";
$schools = App\Models\School::all();
foreach ($schools as $school) {
    echo "- School: {$school->name} (ID: {$school->id})\n";
}

// Test the controller query
$schoolId = session('current_school_id');
echo "\nTesting controller query with school_id: $schoolId\n";

if ($schoolId) {
    $query = App\Models\Grade::with(['teacher'])->withCount('students')->where('school_id', $schoolId);
    $grades = $query->get();
    echo "Grades found for school $schoolId: " . $grades->count() . "\n";
    
    foreach ($grades as $grade) {
        echo "- {$grade->name} (Students: {$grade->students_count})\n";
    }
} else {
    echo "No school ID in session - this is the issue!\n";
}
