<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Current Grades Issue Debug ===\n";

// Check current session state
echo "Session School ID: '" . session('current_school_id') . "'\n";

// Check if grades exist in database
echo "All grades in database: " . \App\Models\Grade::count() . "\n";

// Check schools
echo "Schools in database: " . \App\Models\School::count() . "\n";

// Test the exact controller logic
$schoolId = session('current_school_id');
if (!$schoolId) {
    $firstSchool = \App\Models\School::first();
    if ($firstSchool) {
        $schoolId = $firstSchool->id;
        echo "Auto-selected school ID: $schoolId\n";
    }
}

if ($schoolId) {
    $query = \App\Models\Grade::with(['teacher'])->withCount('students')->where('school_id', $schoolId);
    $grades = $query->get();
    
    echo "Grades found for school $schoolId: " . $grades->count() . "\n";
    
    if ($grades->count() > 0) {
        foreach ($grades as $grade) {
            echo "- {$grade->name} (ID: {$grade->id}, Students: {$grade->students_count})\n";
        }
    } else {
        echo "No grades found - checking all grades with their school IDs:\n";
        $allGrades = \App\Models\Grade::all();
        foreach ($allGrades as $grade) {
            echo "- {$grade->name} (School ID: {$grade->school_id})\n";
        }
    }
} else {
    echo "No school found in database!\n";
}
