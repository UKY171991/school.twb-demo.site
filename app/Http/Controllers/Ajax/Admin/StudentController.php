<?php

namespace App\Http\Controllers\Ajax\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function select(Request $request)
    {
        try {
            $students = Student::with('user')
                ->where('is_active', true)
                ->when($request->school_id, function($query, $schoolId) {
                    return $query->where('school_id', $schoolId);
                })
                ->when($request->class_id, function($query, $classId) {
                    return $query->where('class_id', $classId);
                })
                ->when($request->search, function($query, $search) {
                    return $query->whereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })->orWhere('student_id', 'like', '%' . $search . '%');
                })
                ->limit(20)
                ->get()
                ->map(function($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->user->name,
                        'email' => $student->user->email,
                        'student_id' => $student->student_id,
                        'class' => $student->classModel->name ?? 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch students: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $student = Student::with('user')->findOrFail($request->student_id);
            
            $stats = [
                'attendance_rate' => 0,
                'total_grades' => 0,
                'average_grade' => 0,
                'total_subjects' => 0
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

            // Get grade statistics
            $stats['total_grades'] = \App\Models\Grade::where('student_id', $student->id)->count();
            $stats['average_grade'] = \App\Models\Grade::where('student_id', $student->id)->avg('marks_obtained') ?? 0;
            $stats['total_subjects'] = \App\Models\Subject::where('class_id', $student->class_id)->count();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student stats: ' . $e->getMessage()
            ], 500);
        }
    }
}
