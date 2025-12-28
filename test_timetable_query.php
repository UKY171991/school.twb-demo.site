<?php

use App\Models\Student;
use App\Models\ExamTimetable;
use Illuminate\Support\Facades\Session;

// Mock session if needed, or just fetch first school
$school = \App\Models\School::first();
if (!$school) {
    echo "No school found.\n";
    exit;
}
$schoolId = $school->id;

echo "School ID: $schoolId\n";

// Get a student
$student = Student::where('school_id', $schoolId)->first();
if (!$student) {
    echo "No student found.\n";
    exit;
}

echo "Student: {$student->name}, Class: {$student->class}, Section: '{$student->section}'\n";

// List all timetables for this school and class
$timetables = ExamTimetable::where('school_id', $schoolId)
    ->where('class', $student->class)
    ->get();

echo "Found " . $timetables->count() . " timetable entries for class {$student->class}.\n";

foreach ($timetables as $t) {
    echo "  - Subject: {$t->subject_id}, ExamType: {$t->exam_type_id}, Year: {$t->academic_year}, Section: '{$t->section}' (IsNull: " . (is_null($t->section) ? 'Yes' : 'No') . ")\n";
}

// Simulate the query
$examTypeId = $timetables->first()->exam_type_id ?? 1;
$academicYear = $timetables->first()->academic_year ?? '2024-2025';

echo "\nTesting Query for Student Section '{$student->section}':\n";

$result = ExamTimetable::where('school_id', $schoolId)
    ->where('class', $student->class)
    ->where(function($query) use ($student) {
        $query->where('section', $student->section)
              ->orWhereNull('section')
              ->orWhere('section', '');
    })
    ->where('exam_type_id', $examTypeId)
    ->where('academic_year', $academicYear)
    ->where('is_active', true)
    ->count();

echo "Query found $result entries.\n";
