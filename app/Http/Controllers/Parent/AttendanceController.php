<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $parent = auth()->user()->parent;
        $children = $parent->students;

        $query = Attendance::with(['student.user', 'classModel'])
            ->whereIn('student_id', $children->pluck('id'));

        // Apply filters
        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

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

        // Get attendance statistics for all children
        $attendanceStats = [];
        foreach ($children as $child) {
            $stats = Attendance::where('student_id', $child->id)
                ->selectRaw('
                    COUNT(*) as total_days,
                    SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days,
                    SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_days,
                    SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days,
                    SUM(CASE WHEN status = "excused" THEN 1 ELSE 0 END) as excused_days
                ')
                ->first();

            $attendanceStats[$child->id] = [
                'student_name' => $child->user->name,
                'total_days' => $stats->total_days ?? 0,
                'present_days' => $stats->present_days ?? 0,
                'absent_days' => $stats->absent_days ?? 0,
                'late_days' => $stats->late_days ?? 0,
                'excused_days' => $stats->excused_days ?? 0,
                'attendance_rate' => $stats && $stats->total_days > 0 
                    ? round(($stats->present_days / $stats->total_days) * 100, 2) 
                    : 0
            ];
        }

        // Get monthly attendance data for chart
        $monthlyData = Attendance::whereIn('student_id', $children->pluck('id'))
            ->selectRaw('MONTH(date) as month, COUNT(*) as total, SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
            ->whereYear('date', $request->year ?? date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('parent.attendance.index', compact('attendances', 'children', 'attendanceStats', 'monthlyData'));
    }

    public function getMonthlyData(Request $request)
    {
        $parent = auth()->user()->parent;
        $children = $parent->students;
        $year = $request->year ?? date('Y');

        $monthlyData = Attendance::whereIn('student_id', $children->pluck('id'))
            ->selectRaw('MONTH(date) as month, COUNT(*) as total, SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Fill missing months with zero data
        $fullYearData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyData->firstWhere('month', $i);
            $fullYearData[] = [
                'month' => $i,
                'total' => $monthData ? $monthData->total : 0,
                'present' => $monthData ? $monthData->present : 0,
                'percentage' => $monthData && $monthData->total > 0 
                    ? round(($monthData->present / $monthData->total) * 100, 2) 
                    : 0
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $fullYearData
        ]);
    }

    public function getChildAttendanceStats(Request $request)
    {
        $parent = auth()->user()->parent;
        $student = \App\Models\Student::where('id', $request->student_id)
            ->whereIn('id', $parent->students->pluck('id'))
            ->firstOrFail();

        $stats = Attendance::where('student_id', $student->id)
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_days,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days,
                SUM(CASE WHEN status = "excused" THEN 1 ELSE 0 END) as excused_days
            ')
            ->first();

        $attendanceRate = $stats && $stats->total_days > 0 
            ? round(($stats->present_days / $stats->total_days) * 100, 2) 
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'student_name' => $student->user->name,
                'total_days' => $stats->total_days ?? 0,
                'present_days' => $stats->present_days ?? 0,
                'absent_days' => $stats->absent_days ?? 0,
                'late_days' => $stats->late_days ?? 0,
                'excused_days' => $stats->excused_days ?? 0,
                'attendance_rate' => $attendanceRate
            ]
        ]);
    }
}
