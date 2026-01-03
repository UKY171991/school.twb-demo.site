<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ExamType;
use App\Models\Grade;
use App\Models\Marksheet;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\SchoolContext::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $currentSchoolId = session('current_school_id');

        // Get current school info
        $currentSchool = $currentSchoolId ? School::find($currentSchoolId) : null;

        // Get statistics for current school
        $stats = [
            'schools' => School::active()->count(),
            'teachers' => Teacher::when($currentSchoolId, fn ($q) => $q->where('school_id', $currentSchoolId))->count(),
            'students' => Student::when($currentSchoolId, fn ($q) => $q->where('school_id', $currentSchoolId))->count(),
            'grades' => Grade::when($currentSchoolId, fn ($q) => $q->where('school_id', $currentSchoolId))->count(),
            'subjects' => Subject::when($currentSchoolId, fn ($q) => $q->where('school_id', $currentSchoolId))->count(),
            'exam_types' => ExamType::when($currentSchoolId, fn ($q) => $q->where('school_id', $currentSchoolId))->count(),
            'marksheets' => Marksheet::when($currentSchoolId, fn ($q) => $q->where('school_id', $currentSchoolId))->count(),
            'attendances_today' => Attendance::whereDate('attendance_date', today())
                ->when($currentSchoolId, function ($q) use ($currentSchoolId) {
                    $q->whereHas('student', fn ($sq) => $sq->where('school_id', $currentSchoolId));
                })->count(),
        ];

        // Recent activities
        $recentMarksheets = Marksheet::when($currentSchoolId, fn ($q) => $q->where('school_id', $currentSchoolId))
            ->with('student')
            ->latest()
            ->take(5)
            ->get();

        $recentAttendance = Attendance::whereDate('attendance_date', today())
            ->when($currentSchoolId, function ($q) use ($currentSchoolId) {
                $q->whereHas('student', fn ($sq) => $sq->where('school_id', $currentSchoolId));
            })
            ->with('student')
            ->latest()
            ->take(5)
            ->get();

        // Performance metrics
        $performanceMetrics = [
            'total_students' => $stats['students'],
            'present_today' => Attendance::whereDate('attendance_date', today())
                ->where('status', 'present')
                ->when($currentSchoolId, function ($q) use ($currentSchoolId) {
                    $q->whereHas('student', fn ($sq) => $sq->where('school_id', $currentSchoolId));
                })->count(),
            'absent_today' => Attendance::whereDate('attendance_date', today())
                ->where('status', 'absent')
                ->when($currentSchoolId, function ($q) use ($currentSchoolId) {
                    $q->whereHas('student', fn ($sq) => $sq->where('school_id', $currentSchoolId));
                })->count(),
            'pass_rate' => $this->calculatePassRate($currentSchoolId),
        ];

        // Calculate attendance percentage
        $performanceMetrics['attendance_percentage'] = $performanceMetrics['total_students'] > 0
            ? round(($performanceMetrics['present_today'] / $performanceMetrics['total_students']) * 100, 1)
            : 0;

        return view('home', compact('stats', 'currentSchool', 'recentMarksheets', 'recentAttendance', 'performanceMetrics'));
    }

    private function calculatePassRate($schoolId)
    {
        $totalMarksheets = Marksheet::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->count();

        if ($totalMarksheets == 0) {
            return 0;
        }

        $passedMarksheets = Marksheet::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('result', 'PASS')
            ->count();

        return round(($passedMarksheets / $totalMarksheets) * 100, 1);
    }
}
