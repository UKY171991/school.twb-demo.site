<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $parent = auth()->user()->parent;
        $parent->load(['students.user', 'students.classModel', 'students.school']);

        $children = $parent->students;

        // Get attendance summary for all children
        $attendanceSummary = [];
        $gradesSummary = [];
        $recentActivities = [];

        foreach ($children as $child) {
            // Attendance data
            $attendanceData = \App\Models\Attendance::where('student_id', $child->id)
                ->selectRaw('
                    COUNT(*) as total_days,
                    SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days,
                    SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_days
                ')
                ->first();

            $attendanceSummary[$child->id] = [
                'student_name' => $child->user->name,
                'total_days' => $attendanceData->total_days ?? 0,
                'present_days' => $attendanceData->present_days ?? 0,
                'absent_days' => $attendanceData->absent_days ?? 0,
                'attendance_rate' => $attendanceData && $attendanceData->total_days > 0 
                    ? round(($attendanceData->present_days / $attendanceData->total_days) * 100, 2) 
                    : 0
            ];

            // Grades data
            $gradeData = \App\Models\Grade::where('student_id', $child->id)
                ->selectRaw('AVG(marks_obtained) as average_grade, COUNT(*) as total_grades')
                ->first();

            $gradesSummary[$child->id] = [
                'student_name' => $child->user->name,
                'average_grade' => $gradeData ? round($gradeData->average_grade, 2) : 0,
                'total_grades' => $gradeData->total_grades ?? 0
            ];

            // Recent attendance
            $recentAttendance = \App\Models\Attendance::with('classModel')
                ->where('student_id', $child->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();

            foreach ($recentAttendance as $attendance) {
                $recentActivities[] = [
                    'type' => 'attendance',
                    'student_name' => $child->user->name,
                    'date' => $attendance->date,
                    'status' => $attendance->status,
                    'class' => $attendance->classModel->name ?? 'N/A'
                ];
            }

            // Recent grades
            $recentGrades = \App\Models\Grade::with('subject')
                ->where('student_id', $child->id)
                ->orderBy('exam_date', 'desc')
                ->limit(5)
                ->get();

            foreach ($recentGrades as $grade) {
                $recentActivities[] = [
                    'type' => 'grade',
                    'student_name' => $child->user->name,
                    'date' => $grade->exam_date,
                    'subject' => $grade->subject->name ?? 'N/A',
                    'marks' => $grade->marks_obtained . '/' . $grade->total_marks,
                    'grade' => $grade->grade
                ];
            }
        }

        // Sort recent activities by date
        usort($recentActivities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $recentActivities = array_slice($recentActivities, 0, 10);

        // Get today's attendance status for all children
        $todayAttendance = [];
        foreach ($children as $child) {
            $todayData = \App\Models\Attendance::where('student_id', $child->id)
                ->whereDate('date', today())
                ->first();

            $todayAttendance[$child->id] = [
                'student_name' => $child->user->name,
                'status' => $todayData ? $todayData->status : 'not_marked',
                'remarks' => $todayData ? $todayData->remarks : null
            ];
        }

        return view('parent.dashboard', compact(
            'parent',
            'children',
            'attendanceSummary',
            'gradesSummary',
            'recentActivities',
            'todayAttendance'
        ));
    }

    public function getStats()
    {
        $parent = auth()->user()->parent;
        $children = $parent->students;

        $stats = [
            'total_children' => $children->count(),
            'average_attendance' => 0,
            'average_grade' => 0,
            'total_activities' => 0
        ];

        if ($children->count() > 0) {
            $totalAttendanceRate = 0;
            $totalGrade = 0;
            $totalActivities = 0;

            foreach ($children as $child) {
                // Calculate attendance rate
                $attendanceData = \App\Models\Attendance::where('student_id', $child->id)
                    ->selectRaw('
                        COUNT(*) as total_days,
                        SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days
                    ')
                    ->first();

                if ($attendanceData && $attendanceData->total_days > 0) {
                    $totalAttendanceRate += ($attendanceData->present_days / $attendanceData->total_days) * 100;
                }

                // Calculate average grade
                $averageGrade = \App\Models\Grade::where('student_id', $child->id)->avg('marks_obtained');
                if ($averageGrade) {
                    $totalGrade += $averageGrade;
                }

                // Count activities
                $totalActivities += \App\Models\Attendance::where('student_id', $child->id)->count();
                $totalActivities += \App\Models\Grade::where('student_id', $child->id)->count();
            }

            $stats['average_attendance'] = round($totalAttendanceRate / $children->count(), 2);
            $stats['average_grade'] = round($totalGrade / $children->count(), 2);
            $stats['total_activities'] = $totalActivities;
        }

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
