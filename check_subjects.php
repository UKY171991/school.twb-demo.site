<?php

use App\Models\Student;
use App\Models\Subject;

$student = Student::where('name', 'John Doe')->first();
if ($student) {
    echo "Student: {$student->name}, GradeID: {$student->grade_id}\n";

    $subjects = Subject::where('school_id', $student->school_id)
        ->where(function ($query) use ($student) {
            $query->where('grade_id', $student->grade_id)
                ->orWhereNull('grade_id');
        })
        ->get();

    echo 'Subjects found: '.$subjects->count()."\n";
    foreach ($subjects as $s) {
        echo " - {$s->name}\n";
    }
}
