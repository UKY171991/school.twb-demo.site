<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the teacher dashboard.
     */
    public function index()
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            abort(403, 'Teacher profile not found.');
        }

        $stats = [
            'classes' => ClassModel::where('teacher_id', $teacher->id)->count(),
            'students' => Student::whereIn('class_id', function($query) use ($teacher) {
                $query->select('id')
                      ->from('classes')
                      ->where('teacher_id', $teacher->id);
            })->count(),
            'subjects' => Subject::where('teacher_id', $teacher->id)->count(),
            'attendance_percentage' => $this->getTodayAttendancePercentage($teacher->id),
        ];

        $myClasses = ClassModel::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->take(5)
            ->get();

        $mySubjects = Subject::where('teacher_id', $teacher->id)->take(5)->get();

        $todaySchedule = []; // This would come from a schedule model
        $recentActivities = []; // This would come from an activities log

        return view('tc.dashboard', compact(
            'stats', 
            'myClasses', 
            'mySubjects', 
            'todaySchedule', 
            'recentActivities'
        ));
    }

    /**
     * Get dashboard statistics via AJAX.
     */
    public function getStats(Request $request)
    {
        try {
            $teacher = Auth::user()->teacher;
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found.'
                ], 403);
            }

            $stats = [
                'classes' => ClassModel::where('teacher_id', $teacher->id)->count(),
                'students' => Student::whereIn('class_id', function($query) use ($teacher) {
                    $query->select('id')
                          ->from('classes')
                          ->where('teacher_id', $teacher->id);
                })->count(),
                'subjects' => Subject::where('teacher_id', $teacher->id)->count(),
                'attendance_percentage' => $this->getTodayAttendancePercentage($teacher->id),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * Get today's attendance percentage for teacher's classes.
     */
    private function getTodayAttendancePercentage($teacherId)
    {
        $today = now()->toDateString();
        
        $totalStudents = Student::whereIn('class_id', function($query) use ($teacherId) {
            $query->select('id')
                  ->from('classes')
                  ->where('teacher_id', $teacherId);
        })->count();

        if ($totalStudents === 0) {
            return 0;
        }

        $presentStudents = Attendance::where('date', $today)
            ->whereIn('student_id', function($query) use ($teacherId) {
                $query->select('id')
                      ->from('students')
                      ->whereIn('class_id', function($subQuery) use ($teacherId) {
                          $subQuery->select('id')
                                   ->from('classes')
                                   ->where('teacher_id', $teacherId);
                      });
            })
            ->where('status', 'present')
            ->count();

        return round(($presentStudents / $totalStudents) * 100, 1);
    }
}
