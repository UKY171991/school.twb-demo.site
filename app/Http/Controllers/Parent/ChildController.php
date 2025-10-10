<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public function index()
    {
        $parent = auth()->user()->parent;
        $children = $parent->students()->with(['user', 'classModel', 'school'])->get();

        return view('parent.children.index', compact('children'));
    }

    public function show(Student $student)
    {
        // Ensure parent can only view their own children
        $parent = auth()->user()->parent;
        if (!$parent->students->contains($student->id)) {
            abort(403, 'Unauthorized access to this student.');
        }

        $student->load(['user', 'classModel', 'school']);

        // Get attendance summary
        $attendanceSummary = \App\Models\Attendance::where('student_id', $student->id)
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_days,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days,
                SUM(CASE WHEN status = "excused" THEN 1 ELSE 0 END) as excused_days
            ')
            ->first();

        // Calculate attendance percentage
        $attendancePercentage = $attendanceSummary && $attendanceSummary->total_days > 0 
            ? round(($attendanceSummary->present_days / $attendanceSummary->total_days) * 100, 2) 
            : 0;

        // Get recent attendance
        $recentAttendance = \App\Models\Attendance::with('classModel')
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(20)
            ->get();

        // Get recent grades
        $recentGrades = \App\Models\Grade::with(['subject'])
            ->where('student_id', $student->id)
            ->orderBy('exam_date', 'desc')
            ->limit(20)
            ->get();

        // Get subjects
        $subjects = \App\Models\Subject::with('teacher.user')
            ->where('class_id', $student->class_id)
            ->where('is_active', true)
            ->get();

        // Get grade statistics
        $gradeStats = [
            'total_grades' => \App\Models\Grade::where('student_id', $student->id)->count(),
            'average_grade' => \App\Models\Grade::where('student_id', $student->id)->avg('marks_obtained'),
            'highest_grade' => \App\Models\Grade::where('student_id', $student->id)->max('marks_obtained'),
            'lowest_grade' => \App\Models\Grade::where('student_id', $student->id)->min('marks_obtained'),
        ];

        // Get grades by subject
        $gradesBySubject = \App\Models\Grade::with('subject')
            ->where('student_id', $student->id)
            ->selectRaw('subject_id, AVG(marks_obtained) as average_marks, COUNT(*) as total_exams')
            ->groupBy('subject_id')
            ->get();

        // Get monthly attendance data
        $monthlyAttendance = \App\Models\Attendance::where('student_id', $student->id)
            ->selectRaw('MONTH(date) as month, COUNT(*) as total, SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
            ->whereYear('date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get monthly grade data
        $monthlyGrades = \App\Models\Grade::where('student_id', $student->id)
            ->selectRaw('MONTH(exam_date) as month, AVG(marks_obtained) as average_marks, COUNT(*) as total_exams')
            ->whereYear('exam_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('parent.children.show', compact(
            'student',
            'attendanceSummary',
            'attendancePercentage',
            'recentAttendance',
            'recentGrades',
            'subjects',
            'gradeStats',
            'gradesBySubject',
            'monthlyAttendance',
            'monthlyGrades'
        ));
    }

    public function getChildAttendance(Request $request)
    {
        $parent = auth()->user()->parent;
        $student = Student::where('id', $request->student_id)
            ->whereIn('id', $parent->students->pluck('id'))
            ->firstOrFail();

        $query = \App\Models\Attendance::with(['classModel'])
            ->where('student_id', $student->id);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->month) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->year) {
            $query->whereYear('date', $request->year);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    public function getChildGrades(Request $request)
    {
        $parent = auth()->user()->parent;
        $student = Student::where('id', $request->student_id)
            ->whereIn('id', $parent->students->pluck('id'))
            ->firstOrFail();

        $query = \App\Models\Grade::with(['subject'])
            ->where('student_id', $student->id);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->month) {
            $query->whereMonth('exam_date', $request->month);
        }

        if ($request->year) {
            $query->whereYear('exam_date', $request->year);
        }

        $grades = $query->orderBy('exam_date', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }
}
