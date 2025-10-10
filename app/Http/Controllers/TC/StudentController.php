<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $students = Student::with(['user', 'classModel', 'school'])
            ->whereIn('class_id', $classIds)
            ->where('is_active', true)
            ->paginate(20);

        return view('tc.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        // Ensure teacher can only view students from their classes
        $teacher = auth()->user()->teacher;
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        if (!in_array($student->class_id, $classIds->toArray())) {
            abort(403, 'Unauthorized access to this student.');
        }

        $student->load(['user', 'classModel', 'school']);
        
        // Get student's attendance summary
        $attendanceSummary = \App\Models\Attendance::where('student_id', $student->id)
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_days,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days,
                SUM(CASE WHEN status = "excused" THEN 1 ELSE 0 END) as excused_days
            ')
            ->first();

        // Get recent grades
        $recentGrades = \App\Models\Grade::with('subject')
            ->where('student_id', $student->id)
            ->orderBy('exam_date', 'desc')
            ->limit(10)
            ->get();

        return view('tc.students.show', compact('student', 'attendanceSummary', 'recentGrades'));
    }

    public function search(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $query = Student::with(['user', 'classModel'])
            ->whereIn('class_id', $classIds)
            ->where('is_active', true);

        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('student_id', 'like', '%' . $request->search . '%');
        }

        $students = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        }

        return view('tc.students.index', compact('students'));
    }
}
