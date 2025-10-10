<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;
        
        $query = Attendance::with(['classModel'])
            ->where('student_id', $student->id);

        // Apply filters
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

        // Get attendance statistics
        $stats = [
            'total_days' => Attendance::where('student_id', $student->id)->count(),
            'present_days' => Attendance::where('student_id', $student->id)->where('status', 'present')->count(),
            'absent_days' => Attendance::where('student_id', $student->id)->where('status', 'absent')->count(),
            'late_days' => Attendance::where('student_id', $student->id)->where('status', 'late')->count(),
            'excused_days' => Attendance::where('student_id', $student->id)->where('status', 'excused')->count(),
        ];

        // Calculate attendance percentage
        $stats['attendance_percentage'] = $stats['total_days'] > 0 
            ? round(($stats['present_days'] / $stats['total_days']) * 100, 2) 
            : 0;

        // Get monthly attendance data for chart
        $monthlyData = Attendance::where('student_id', $student->id)
            ->selectRaw('MONTH(date) as month, COUNT(*) as total, SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
            ->whereYear('date', $request->year ?? date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('student.attendance.index', compact('attendances', 'stats', 'monthlyData'));
    }

    public function getMonthlyData(Request $request)
    {
        $student = auth()->user()->student;
        $year = $request->year ?? date('Y');

        $monthlyData = Attendance::where('student_id', $student->id)
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

    public function getTodayStatus()
    {
        $student = auth()->user()->student;
        
        $todayAttendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', today())
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $todayAttendance ? $todayAttendance->status : 'not_marked',
                'remarks' => $todayAttendance ? $todayAttendance->remarks : null,
                'date' => today()->format('Y-m-d')
            ]
        ]);
    }
}
