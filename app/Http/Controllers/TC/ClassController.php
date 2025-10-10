<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $classes = ClassModel::with(['school', 'students.user'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        return view('tc.classes.index', compact('classes'));
    }

    public function show(ClassModel $class)
    {
        // Ensure teacher can only view their own classes
        $teacher = auth()->user()->teacher;
        if ($class->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this class.');
        }

        $class->load(['school', 'students.user', 'subjects']);
        
        // Get attendance statistics
        $attendanceStats = [
            'total_students' => $class->students->count(),
            'present_today' => \App\Models\Attendance::where('class_id', $class->id)
                ->whereDate('date', today())
                ->where('status', 'present')
                ->count(),
            'absent_today' => \App\Models\Attendance::where('class_id', $class->id)
                ->whereDate('date', today())
                ->where('status', 'absent')
                ->count(),
        ];

        return view('tc.classes.show', compact('class', 'attendanceStats'));
    }

    public function getClassStudents(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $students = Student::with('user')
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }
}
