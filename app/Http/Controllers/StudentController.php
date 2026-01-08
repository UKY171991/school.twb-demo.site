<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = auth()->user()->student;
        $enrollments = Enrollment::where('student_id', $student->id)->with(['classroom.teacher.user', 'classroom.subject'])->get();
        $grades = Grade::whereHas('enrollment', function($q) use ($student) {
            $q->where('student_id', $student->id);
        })->with(['enrollment.classroom.subject'])->get();

        return view('student.dashboard', compact('student', 'enrollments', 'grades'));
    }
}
