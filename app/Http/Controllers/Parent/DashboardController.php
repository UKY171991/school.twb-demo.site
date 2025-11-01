<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Attendance;
use App\Models\Grade;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $parent = auth()->user()->parent;
        $parent->load(['students.user', 'students.classModel', 'students.school']);

        $children = $parent->students;

        foreach ($children as $child) {
            $attendanceData = Attendance::where('student_id', $child->id)
                ->selectRaw('
                    COUNT(*) as total_days,
                    SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days
                ')
                ->first();

            $child->attendance_percentage = 0;
            if ($attendanceData && $attendanceData->total_days > 0) {
                $child->attendance_percentage = round(($attendanceData->present_days / $attendanceData->total_days) * 100, 2);
            }

            $child->average_grade = Grade::where('student_id', $child->id)->avg('marks_obtained');
            if ($child->average_grade) {
                $child->average_grade = round($child->average_grade, 2);
            }
        }

        return view('parent.dashboard', compact('parent', 'children'));
    }
}
