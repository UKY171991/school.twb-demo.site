<?php

use App\Models\ExamTimetable;
use App\Models\Student;
use App\Models\Subject;

$student = Student::where('name', 'John Doe')->first();
$className = $student->grade->name; // "Grade 10"
$subject = Subject::where('grade_id', $student->grade_id)->first();

if (!$subject) {
    echo "No subject found for student grade.\n";
    exit;
}

// Create a dummy timetable
$timetable = ExamTimetable::create([
    'school_id' => $student->school_id,
    'exam_type_id' => 1, // Assuming 1 exists
    'subject_id' => $subject->id,
    'class' => $className,
    'section' => null, // Applies to all sections
    'academic_year' => '2024-2025',
    'exam_date' => now()->addDays(10),
    'start_time' => '10:00:00',
    'end_time' => '13:00:00',
    'is_active' => true,
]);

echo "Created dummy timetable ID: {$timetable->id} for Class: '{$timetable->class}'\n";

// Now test the query from AdmitCardController logic
$result = ExamTimetable::where('school_id', $student->school_id)
    ->where('class', $className) // Logic I added
    ->where(function($query) use ($student) {
        $query->where('section', $student->section)
              ->orWhereNull('section')
              ->orWhere('section', '');
    })
    ->where('exam_type_id', 1)
    ->where('academic_year', '2024-2025')
    ->count();

echo "Query found: $result entries.\n";

// Clean up
$timetable->delete();
echo "Deleted dummy timetable.\n";
