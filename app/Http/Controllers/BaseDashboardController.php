<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Attendance;
use App\Models\Grade;
use Carbon\Carbon;

abstract class BaseDashboardController extends BaseController
{
    /**
     * Get dashboard statistics based on user role
     */
    protected function getDashboardStatistics(): array
    {
        return match($this->userRole) {
            'super_admin' => $this->getSuperAdminStatistics(),
            'admin' => $this->getSchoolAdminStatistics(),
            'teacher' => $this->getTeacherStatistics(),
            'student' => $this->getStudentStatistics(),
            'parent' => $this->getParentStatistics(),
            default => []
        };
    }

    /**
     * Get Super Admin statistics
     */
    protected function getSuperAdminStatistics(): array
    {
        return [
            'total_schools' => $this->getAccessibleSchools()->count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_users' => User::count(),
            'active_schools' => $this->getAccessibleSchools()->where('is_active', true)->count(),
            'recent_enrollments' => Student::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
        ];
    }

    /**
     * Get School Admin statistics
     */
    protected function getSchoolAdminStatistics(): array
    {
        if (!$this->school) {
            return [];
        }

        $studentsQuery = Student::where('school_id', $this->school->id);
        $teachersQuery = Teacher::where('school_id', $this->school->id);
        $classesQuery = ClassModel::where('school_id', $this->school->id);

        return [
            'total_students' => $studentsQuery->count(),
            'total_teachers' => $teachersQuery->count(),
            'total_classes' => $classesQuery->count(),
            'active_students' => $studentsQuery->where('status', 'active')->count(),
            'present_today' => $this->getTodayAttendanceCount('present'),
            'absent_today' => $this->getTodayAttendanceCount('absent'),
            'recent_enrollments' => $studentsQuery->where('created_at', '>=', Carbon::now()->subDays(30))->count(),
        ];
    }

    /**
     * Get Teacher statistics
     */
    protected function getTeacherStatistics(): array
    {
        $teacher = $this->user->teacher;
        if (!$teacher) {
            return [];
        }

        $myClasses = ClassModel::where('teacher_id', $teacher->id);
        $myStudents = Student::whereIn('class_id', $myClasses->pluck('id'));

        return [
            'my_classes' => $myClasses->count(),
            'my_students' => $myStudents->count(),
            'present_today' => $this->getTodayAttendanceCount('present', $myStudents->pluck('id')->toArray()),
            'absent_today' => $this->getTodayAttendanceCount('absent', $myStudents->pluck('id')->toArray()),
            'pending_grades' => $this->getPendingGradesCount($teacher->id),
            'todays_classes' => $this->getTodaysClasses($teacher->id),
        ];
    }

    /**
     * Get Student statistics
     */
    protected function getStudentStatistics(): array
    {
        $student = $this->user->student;
        if (!$student) {
            return [];
        }

        $attendanceQuery = Attendance::where('student_id', $student->id);
        $gradesQuery = Grade::where('student_id', $student->id);

        return [
            'attendance_percentage' => $this->calculateAttendancePercentage($student->id),
            'total_subjects' => $gradesQuery->distinct('subject_id')->count(),
            'average_grade' => $gradesQuery->avg('grade') ?? 0,
            'present_days' => $attendanceQuery->where('status', 'present')->count(),
            'absent_days' => $attendanceQuery->where('status', 'absent')->count(),
            'recent_grades' => $gradesQuery->orderBy('created_at', 'desc')->limit(5)->get(),
        ];
    }

    /**
     * Get Parent statistics
     */
    protected function getParentStatistics(): array
    {
        $parent = $this->user->parent;
        if (!$parent) {
            return [];
        }

        $children = Student::where('parent_id', $parent->id)->get();
        $childrenIds = $children->pluck('id')->toArray();

        return [
            'total_children' => $children->count(),
            'children_present_today' => $this->getTodayAttendanceCount('present', $childrenIds),
            'children_absent_today' => $this->getTodayAttendanceCount('absent', $childrenIds),
            'average_performance' => $this->getChildrenAverageGrades($childrenIds),
            'recent_activities' => $this->getRecentChildrenActivities($childrenIds),
        ];
    }

    /**
     * Get today's attendance count
     */
    protected function getTodayAttendanceCount(string $status, array $studentIds = null): int
    {
        $query = Attendance::where('date', Carbon::today())
                          ->where('status', $status);

        if ($this->school && !$this->user->isSuperAdmin()) {
            $query->where('school_id', $this->school->id);
        }

        if ($studentIds) {
            $query->whereIn('student_id', $studentIds);
        }

        return $query->count();
    }

    /**
     * Get pending grades count for teacher
     */
    protected function getPendingGradesCount(int $teacherId): int
    {
        // This would depend on your grading system implementation
        // For now, return 0 as placeholder
        return 0;
    }

    /**
     * Get today's classes for teacher
     */
    protected function getTodaysClasses(int $teacherId): int
    {
        // This would depend on your scheduling system implementation
        // For now, return count of teacher's classes
        return ClassModel::where('teacher_id', $teacherId)->count();
    }

    /**
     * Calculate attendance percentage for student
     */
    protected function calculateAttendancePercentage(int $studentId): float
    {
        $totalDays = Attendance::where('student_id', $studentId)->count();
        if ($totalDays === 0) {
            return 0;
        }

        $presentDays = Attendance::where('student_id', $studentId)
                                ->where('status', 'present')
                                ->count();

        return round(($presentDays / $totalDays) * 100, 2);
    }

    /**
     * Get children's average grades
     */
    protected function getChildrenAverageGrades(array $childrenIds): float
    {
        if (empty($childrenIds)) {
            return 0;
        }

        return Grade::whereIn('student_id', $childrenIds)->avg('grade') ?? 0;
    }

    /**
     * Get recent children activities
     */
    protected function getRecentChildrenActivities(array $childrenIds): array
    {
        if (empty($childrenIds)) {
            return [];
        }

        // Combine recent grades and attendance
        $recentGrades = Grade::whereIn('student_id', $childrenIds)
                           ->with(['student', 'subject'])
                           ->orderBy('created_at', 'desc')
                           ->limit(5)
                           ->get()
                           ->map(function($grade) {
                               return [
                                   'type' => 'grade',
                                   'message' => "{$grade->student->first_name} received grade {$grade->grade} in {$grade->subject->name}",
                                   'date' => $grade->created_at,
                               ];
                           });

        $recentAttendance = Attendance::whereIn('student_id', $childrenIds)
                                    ->with('student')
                                    ->where('created_at', '>=', Carbon::now()->subDays(7))
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get()
                                    ->map(function($attendance) {
                                        return [
                                            'type' => 'attendance',
                                            'message' => "{$attendance->student->first_name} was {$attendance->status} on {$attendance->date->format('M d')}",
                                            'date' => $attendance->created_at,
                                        ];
                                    });

        return $recentGrades->concat($recentAttendance)
                          ->sortByDesc('date')
                          ->take(10)
                          ->values()
                          ->toArray();
    }

    /**
     * Get dashboard widgets for user
     */
    protected function getDashboardWidgets(): array
    {
        // This would be implemented when we create the DashboardWidget model
        // For now, return default widgets based on role
        return $this->getDefaultWidgets();
    }

    /**
     * Get default widgets based on user role
     */
    protected function getDefaultWidgets(): array
    {
        return match($this->userRole) {
            'super_admin' => [
                ['type' => 'schools_overview', 'position' => 1, 'size' => 'col-md-6'],
                ['type' => 'users_statistics', 'position' => 2, 'size' => 'col-md-6'],
                ['type' => 'recent_activities', 'position' => 3, 'size' => 'col-md-12'],
            ],
            'admin' => [
                ['type' => 'students_overview', 'position' => 1, 'size' => 'col-md-4'],
                ['type' => 'teachers_overview', 'position' => 2, 'size' => 'col-md-4'],
                ['type' => 'attendance_today', 'position' => 3, 'size' => 'col-md-4'],
                ['type' => 'recent_enrollments', 'position' => 4, 'size' => 'col-md-12'],
            ],
            'teacher' => [
                ['type' => 'my_classes', 'position' => 1, 'size' => 'col-md-6'],
                ['type' => 'attendance_summary', 'position' => 2, 'size' => 'col-md-6'],
                ['type' => 'todays_schedule', 'position' => 3, 'size' => 'col-md-12'],
            ],
            'student' => [
                ['type' => 'academic_overview', 'position' => 1, 'size' => 'col-md-6'],
                ['type' => 'attendance_summary', 'position' => 2, 'size' => 'col-md-6'],
                ['type' => 'recent_grades', 'position' => 3, 'size' => 'col-md-12'],
            ],
            'parent' => [
                ['type' => 'children_overview', 'position' => 1, 'size' => 'col-md-6'],
                ['type' => 'attendance_summary', 'position' => 2, 'size' => 'col-md-6'],
                ['type' => 'recent_activities', 'position' => 3, 'size' => 'col-md-12'],
            ],
            default => []
        };
    }

    /**
     * Get common dashboard view data
     */
    protected function getDashboardViewData(): array
    {
        return array_merge($this->getCommonViewData(), [
            'statistics' => $this->getDashboardStatistics(),
            'widgets' => $this->getDashboardWidgets(),
            'pageTitle' => 'Dashboard',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'active' => true]
            ]
        ]);
    }

    /**
     * Render dashboard view
     */
    protected function renderDashboard(string $view): View
    {
        return view($view, $this->getDashboardViewData());
    }
}