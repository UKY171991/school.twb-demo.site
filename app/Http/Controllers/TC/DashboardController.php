<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends BaseController
{
    /**
     * Display teacher dashboard
     */
    public function index(): View
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            abort(403, 'Teacher profile not found.');
        }

        $data = [
            'page_title' => 'Teacher Dashboard',
            'teacher' => $teacher,
            'statistics' => $this->getTeacherStatistics($teacher),
            'todayClasses' => $this->getTodayClasses($teacher),
            'recentGrades' => $this->getRecentGrades($teacher),
            'pendingTasks' => $this->getPendingTasks($teacher),
            'studentAlerts' => $this->getStudentAlerts($teacher),
            'upcomingSchedule' => $this->getUpcomingSchedule($teacher),
            'attendanceSummary' => $this->getAttendanceSummary($teacher)
        ];

        return view('tc.dashboard', $data);
    }

    /**
     * Get teacher statistics for dashboard
     */
    public function getStats(Request $request): JsonResponse
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return $this->ajaxError('Teacher profile not found.');
        }

        return $this->handleAjaxRequest(function() use ($teacher) {
            return [
                'statistics' => $this->getTeacherStatistics($teacher),
                'today_classes' => $this->getTodayClasses($teacher),
                'pending_tasks' => $this->getPendingTasks($teacher),
                'student_alerts' => $this->getStudentAlerts($teacher)
            ];
        });
    }

    /**
     * Quick attendance marking
     */
    public function quickAttendance(Request $request): JsonResponse
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            return $this->ajaxError('Teacher profile not found.');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late'
        ]);

        return $this->handleAjaxRequest(function() use ($request, $teacher) {
            $class = ClassModel::where('id', $request->class_id)
                              ->where('teacher_id', $teacher->id)
                              ->first();

            if (!$class) {
                throw new \Exception('Class not found or not assigned to you.');
            }

            $date = Carbon::parse($request->date);
            $attendanceData = $request->attendance;
            $markedCount = 0;

            foreach ($attendanceData as $record) {
                $attendance = Attendance::updateOrCreate([
                    'school_id' => $teacher->school_id,
                    'student_id' => $record['student_id'],
                    'class_id' => $class->id,
                    'date' => $date
                ], [
                    'status' => $record['status'],
                    'check_in_time' => $record['status'] === 'present' ? now() : null,
                    'remarks' => $record['remarks'] ?? null
                ]);

                $markedCount++;
            }

            return [
                'message' => "Attendance marked for {$markedCount} students",
                'marked_count' => $markedCount,
                'class' => $class->full_name,
                'date' => $date->format('Y-m-d')
            ];
        });
    }

    /**
     * Get teacher statistics
     */
    private function getTeacherStatistics($teacher): array
    {
        $classes = ClassModel::where('teacher_id', $teacher->id)->get();
        $classIds = $classes->pluck('id');
        $studentIds = Student::whereIn('class_id', $classIds)->pluck('id');

        $totalStudents = $studentIds->count();
        $totalSubjects = Subject::where('teacher_id', $teacher->id)->count();
        $totalGrades = Grade::whereIn('student_id', $studentIds)->count();
        
        // Today's attendance
        $todayAttendance = Attendance::whereIn('student_id', $studentIds)
                                   ->whereDate('date', today())
                                   ->count();

        // This week's grades
        $weeklyGrades = Grade::whereIn('student_id', $studentIds)
                           ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                           ->count();

        // Pending tasks
        $pendingAttendance = $this->getPendingAttendanceCount($teacher);
        $pendingGrades = $this->getPendingGradesCount($teacher);

        return [
            'total_classes' => $classes->count(),
            'total_students' => $totalStudents,
            'total_subjects' => $totalSubjects,
            'total_grades' => $totalGrades,
            'today_attendance' => $todayAttendance,
            'weekly_grades' => $weeklyGrades,
            'pending_attendance' => $pendingAttendance,
            'pending_grades' => $pendingGrades,
            'active_classes' => $classes->where('is_active', true)->count(),
            'average_class_size' => $classes->count() > 0 ? round($totalStudents / $classes->count(), 1) : 0
        ];
    }

    /**
     * Get today's classes
     */
    private function getTodayClasses($teacher): array
    {
        $today = Carbon::today();
        $dayOfWeek = strtolower($today->format('l'));

        $schedules = ClassSchedule::where('teacher_id', $teacher->id)
                                 ->where('day_of_week', $dayOfWeek)
                                 ->where('is_active', true)
                                 ->with(['class.students', 'subject'])
                                 ->orderBy('start_time')
                                 ->get();

        return $schedules->map(function($schedule) use ($today) {
            $attendanceMarked = Attendance::where('class_id', $schedule->class_id)
                                        ->whereDate('date', $today)
                                        ->exists();

            return [
                'schedule' => $schedule,
                'class' => $schedule->class,
                'subject' => $schedule->subject,
                'student_count' => $schedule->class->students->count(),
                'time_slot' => $schedule->time_slot,
                'room' => $schedule->room_number,
                'attendance_marked' => $attendanceMarked,
                'is_current' => $this->isCurrentClass($schedule),
                'is_upcoming' => $this->isUpcomingClass($schedule)
            ];
        })->toArray();
    }

    /**
     * Get recent grades
     */
    private function getRecentGrades($teacher): array
    {
        $classIds = ClassModel::where('teacher_id', $teacher->id)->pluck('id');
        $studentIds = Student::whereIn('class_id', $classIds)->pluck('id');

        return Grade::whereIn('student_id', $studentIds)
                   ->with(['student.user', 'subject'])
                   ->orderBy('created_at', 'desc')
                   ->limit(10)
                   ->get()
                   ->map(function($grade) {
                       return [
                           'student_name' => $grade->student->full_name,
                           'subject' => $grade->subject->name,
                           'marks_obtained' => $grade->marks_obtained,
                           'total_marks' => $grade->total_marks,
                           'percentage' => round(($grade->marks_obtained / $grade->total_marks) * 100, 2),
                           'exam_type' => $grade->exam_type,
                           'exam_date' => $grade->exam_date?->format('M d, Y'),
                           'created_at' => $grade->created_at->format('M d, Y H:i')
                       ];
                   })
                   ->toArray();
    }

    /**
     * Get pending tasks
     */
    private function getPendingTasks($teacher): array
    {
        $tasks = [];

        // Pending attendance for today
        $pendingAttendance = $this->getPendingAttendanceCount($teacher);
        if ($pendingAttendance > 0) {
            $tasks[] = [
                'type' => 'attendance',
                'title' => 'Mark Today\'s Attendance',
                'description' => "{$pendingAttendance} classes need attendance marking",
                'count' => $pendingAttendance,
                'priority' => 'high',
                'action_url' => route('teacher.attendance.create'),
                'icon' => 'fas fa-calendar-check',
                'color' => 'danger'
            ];
        }

        // Pending grade entries
        $pendingGrades = $this->getPendingGradesCount($teacher);
        if ($pendingGrades > 0) {
            $tasks[] = [
                'type' => 'grades',
                'title' => 'Enter Pending Grades',
                'description' => "{$pendingGrades} assessments need grading",
                'count' => $pendingGrades,
                'priority' => 'medium',
                'action_url' => route('teacher.grades.create'),
                'icon' => 'fas fa-star',
                'color' => 'warning'
            ];
        }

        // Upcoming exams/assessments
        $upcomingExams = $this->getUpcomingExams($teacher);
        if ($upcomingExams > 0) {
            $tasks[] = [
                'type' => 'exams',
                'title' => 'Upcoming Assessments',
                'description' => "{$upcomingExams} assessments scheduled this week",
                'count' => $upcomingExams,
                'priority' => 'medium',
                'action_url' => route('teacher.schedule'),
                'icon' => 'fas fa-clipboard-list',
                'color' => 'info'
            ];
        }

        return $tasks;
    }

    /**
     * Get student alerts
     */
    private function getStudentAlerts($teacher): array
    {
        $alerts = [];
        $classIds = ClassModel::where('teacher_id', $teacher->id)->pluck('id');
        $studentIds = Student::whereIn('class_id', $classIds)->pluck('id');

        // Low attendance students
        $lowAttendanceStudents = $this->getLowAttendanceStudents($studentIds);
        foreach ($lowAttendanceStudents as $student) {
            $alerts[] = [
                'type' => 'attendance',
                'title' => 'Low Attendance Alert',
                'student_name' => $student['name'],
                'class_name' => $student['class'],
                'description' => "Attendance rate: {$student['attendance_rate']}%",
                'severity' => 'warning',
                'action_required' => true
            ];
        }

        // Poor performance students
        $poorPerformanceStudents = $this->getPoorPerformanceStudents($studentIds);
        foreach ($poorPerformanceStudents as $student) {
            $alerts[] = [
                'type' => 'performance',
                'title' => 'Academic Performance Alert',
                'student_name' => $student['name'],
                'class_name' => $student['class'],
                'description' => "Average grade: {$student['average_grade']}%",
                'severity' => 'danger',
                'action_required' => true
            ];
        }

        return array_slice($alerts, 0, 10); // Limit to 10 alerts
    }

    /**
     * Get upcoming schedule
     */
    private function getUpcomingSchedule($teacher): array
    {
        $tomorrow = Carbon::tomorrow();
        $dayOfWeek = strtolower($tomorrow->format('l'));

        return ClassSchedule::where('teacher_id', $teacher->id)
                           ->where('day_of_week', $dayOfWeek)
                           ->where('is_active', true)
                           ->with(['class', 'subject'])
                           ->orderBy('start_time')
                           ->get()
                           ->map(function($schedule) {
                               return [
                                   'class' => $schedule->class->full_name,
                                   'subject' => $schedule->subject->name,
                                   'time_slot' => $schedule->time_slot,
                                   'room' => $schedule->room_number,
                                   'student_count' => $schedule->class->students->count()
                               ];
                           })
                           ->toArray();
    }

    /**
     * Get attendance summary
     */
    private function getAttendanceSummary($teacher): array
    {
        $classIds = ClassModel::where('teacher_id', $teacher->id)->pluck('id');
        $studentIds = Student::whereIn('class_id', $classIds)->pluck('id');

        $thisWeek = Attendance::whereIn('student_id', $studentIds)
                             ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
                             ->get();

        $totalRecords = $thisWeek->count();
        $presentCount = $thisWeek->where('status', 'present')->count();
        $absentCount = $thisWeek->where('status', 'absent')->count();
        $lateCount = $thisWeek->where('status', 'late')->count();

        return [
            'total_records' => $totalRecords,
            'present_count' => $presentCount,
            'absent_count' => $absentCount,
            'late_count' => $lateCount,
            'attendance_rate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0
        ];
    }

    /**
     * Helper methods
     */
    private function getPendingAttendanceCount($teacher): int
    {
        $today = Carbon::today();
        $dayOfWeek = strtolower($today->format('l'));

        $todayClasses = ClassSchedule::where('teacher_id', $teacher->id)
                                   ->where('day_of_week', $dayOfWeek)
                                   ->where('is_active', true)
                                   ->pluck('class_id');

        $markedClasses = Attendance::whereIn('class_id', $todayClasses)
                                 ->whereDate('date', $today)
                                 ->distinct('class_id')
                                 ->count('class_id');

        return $todayClasses->count() - $markedClasses;
    }

    private function getPendingGradesCount($teacher): int
    {
        // This would typically check for scheduled assessments without grades
        // For now, return a placeholder
        return 0;
    }

    private function getUpcomingExams($teacher): int
    {
        // This would check for scheduled exams/assessments
        // For now, return a placeholder
        return 0;
    }

    private function getLowAttendanceStudents($studentIds): array
    {
        // Get students with attendance rate below 75%
        $students = Student::whereIn('id', $studentIds)
                          ->with(['user', 'class'])
                          ->get();

        $lowAttendanceStudents = [];
        
        foreach ($students as $student) {
            $attendance = Attendance::where('student_id', $student->id)
                                  ->whereBetween('date', [now()->subMonth(), now()])
                                  ->get();

            if ($attendance->count() > 0) {
                $attendanceRate = ($attendance->where('status', 'present')->count() / $attendance->count()) * 100;
                
                if ($attendanceRate < 75) {
                    $lowAttendanceStudents[] = [
                        'name' => $student->full_name,
                        'class' => $student->class->full_name ?? 'N/A',
                        'attendance_rate' => round($attendanceRate, 1)
                    ];
                }
            }
        }

        return array_slice($lowAttendanceStudents, 0, 5);
    }

    private function getPoorPerformanceStudents($studentIds): array
    {
        // Get students with average grade below 60%
        $students = Student::whereIn('id', $studentIds)
                          ->with(['user', 'class'])
                          ->get();

        $poorPerformanceStudents = [];
        
        foreach ($students as $student) {
            $grades = Grade::where('student_id', $student->id)
                          ->whereBetween('created_at', [now()->subMonth(), now()])
                          ->get();

            if ($grades->count() > 0) {
                $averageGrade = $grades->avg('marks_obtained');
                
                if ($averageGrade < 60) {
                    $poorPerformanceStudents[] = [
                        'name' => $student->full_name,
                        'class' => $student->class->full_name ?? 'N/A',
                        'average_grade' => round($averageGrade, 1)
                    ];
                }
            }
        }

        return array_slice($poorPerformanceStudents, 0, 5);
    }

    private function isCurrentClass($schedule): bool
    {
        $now = Carbon::now();
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        
        return $now->between($startTime, $endTime);
    }

    private function isUpcomingClass($schedule): bool
    {
        $now = Carbon::now();
        $startTime = Carbon::parse($schedule->start_time);
        
        return $startTime->gt($now) && $startTime->diffInHours($now) <= 2;
    }
}
