<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $teacher = auth()->user()->teacher;
        $classrooms = Classroom::where('teacher_id', $teacher->id)->with('subject')->get();
        $enrollments = Enrollment::whereHas('classroom', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with(['student.user', 'classroom.subject'])->get();

        return view('teacher.dashboard', compact('teacher', 'classrooms', 'enrollments'));
    }

    public function classroom(Classroom $classroom)
    {
        // Ensure teacher owns the classroom
        if ($classroom->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $enrollments = Enrollment::where('classroom_id', $classroom->id)->with(['student.user', 'grades'])->get();

        return view('teacher.classroom', compact('classroom', 'enrollments'));
    }

    public function addGrade(Request $request, Enrollment $enrollment)
    {
        // Ensure teacher owns the classroom
        if ($enrollment->classroom->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable',
        ]);

        Grade::create([
            'enrollment_id' => $enrollment->id,
            'grade' => $request->grade,
            'comments' => $request->comments,
        ]);

        return back()->with('success', 'Grade added successfully');
    }
}
