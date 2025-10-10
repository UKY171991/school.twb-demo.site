<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $student->load(['classModel', 'school']);

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

        // Get recent attendance
        $recentAttendance = \App\Models\Attendance::with('classModel')
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        // Get recent grades
        $recentGrades = \App\Models\Grade::with(['subject'])
            ->where('student_id', $student->id)
            ->orderBy('exam_date', 'desc')
            ->limit(10)
            ->get();

        // Get subjects
        $subjects = \App\Models\Subject::with('teacher.user')
            ->where('class_id', $student->class_id)
            ->where('is_active', true)
            ->get();

        // Get today's attendance status
        $todayAttendance = \App\Models\Attendance::where('student_id', $student->id)
            ->whereDate('date', today())
            ->first();

        // Get upcoming assignments/exams (sample data)
        $upcomingEvents = [
            [
                'title' => 'Mathematics Quiz',
                'subject' => 'Mathematics',
                'date' => Carbon::tomorrow(),
                'type' => 'quiz'
            ],
            [
                'title' => 'Physics Midterm',
                'subject' => 'Physics',
                'date' => Carbon::now()->addDays(3),
                'type' => 'exam'
            ],
            [
                'title' => 'Chemistry Assignment',
                'subject' => 'Chemistry',
                'date' => Carbon::now()->addDays(5),
                'type' => 'assignment'
            ]
        ];

        return view('student.dashboard', compact(
            'student',
            'attendanceSummary',
            'recentAttendance',
            'recentGrades',
            'subjects',
            'todayAttendance',
            'upcomingEvents'
        ));
    }

    public function getStats()
    {
        $student = auth()->user()->student;

        $stats = [
            'attendance_rate' => 0,
            'total_subjects' => 0,
            'average_grade' => 0,
            'total_assignments' => 0
        ];

        // Calculate attendance rate
        $attendanceData = \App\Models\Attendance::where('student_id', $student->id)
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days
            ')
            ->first();

        if ($attendanceData && $attendanceData->total_days > 0) {
            $stats['attendance_rate'] = round(($attendanceData->present_days / $attendanceData->total_days) * 100, 2);
        }

        // Get total subjects
        $stats['total_subjects'] = \App\Models\Subject::where('class_id', $student->class_id)
            ->where('is_active', true)
            ->count();

        // Get average grade
        $averageGrade = \App\Models\Grade::where('student_id', $student->id)->avg('marks_obtained');
        $stats['average_grade'] = $averageGrade ? round($averageGrade, 2) : 0;

        // Get total assignments (sample data)
        $stats['total_assignments'] = 12;

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
