<?php
use App\Models\Student;
$student = Student::where('name', 'John Doe')->first();
if ($student) {
    echo "Name: {$student->name}\n";
    echo "Class: '{$student->class}'\n";
    echo "Section: '{$student->section}'\n";
    echo "School ID: {$student->school_id}\n";
} else {
    echo "Student 'John Doe' not found.\n";
    // List all students
    foreach(Student::all() as $s) {
        echo "Student: {$s->name}, Class: '{$s->class}'\n";
    }
}
