<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all grades
$grades = App\Models\Grade::all(['id', 'name']);

echo "Grades in database:\n";
foreach ($grades as $grade) {
    echo "ID: {$grade->id}, Name: {$grade->name}\n";
    echo "Edit URL: " . route('grades.edit', $grade->id) . "\n";
    echo "---\n";
}

if ($grades->isEmpty()) {
    echo "No grades found in database.\n";
}
