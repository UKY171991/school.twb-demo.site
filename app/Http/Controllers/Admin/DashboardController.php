<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseDashboardController;
use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\SystemNotification;
use App\Models\DashboardWidget;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseDashboardController
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Ensure user is admin
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        // Get dashboard data using base controller methods
        $viewData = $this->getDashboardViewData();
        
        // Add admin-specific data
        $viewData['recentActivities'] = $this->getRecentActivities();
        $viewData['pageTitle'] = 'School Administration Dashboard';
        $viewData['currentSchool'] = $this->getCurrentSchool();
        $viewData['quickActions'] = $this->getQuickActions();
        $viewData['recentStudents'] = $this->getRecentStudents();
        $viewData['upcomingEvents'] = $this->getUpcomingEvents();
        $viewData['dashboardWidgets'] = $this->getUserDashboardWidgets();
        
        return view('admin.dashboard', $viewData);
    }

    /**
     * Get dashboard statistics via AJAX.
     */
    public function getStats(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            return $this->getDashboardStatistics();
        });
    }

    /**
     * Get recent activities for the school
     */
    private function getRecentActivities()
    {
        $query = ActivityLog::with('user')->latest()->take(10);
        
        // Apply school context
        $query = $this->applySchoolContext($query);
        
        return $query->get();
    }

    /**
     * Get attendance chart data via AJAX
     */
    public function getAttendanceChartData(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            // Placeholder implementation - will be enhanced in later tasks
            return [
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                'datasets' => [
                    [
                        'label' => 'Present',
                        'data' => [85, 90, 88, 92, 87],
                        'backgroundColor' => 'rgba(40, 167, 69, 0.8)'
                    ],
                    [
                        'label' => 'Absent',
                        'data' => [15, 10, 12, 8, 13],
                        'backgroundColor' => 'rgba(220, 53, 69, 0.8)'
                    ]
                ]
            ];
        });
    }

    /**
     * Get fee trends chart data via AJAX
     */
    public function getFeeTrendsChartData(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            // Placeholder implementation
            return [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                'datasets' => [
                    [
                        'label' => 'Collected',
                        'data' => [50000, 45000, 60000, 55000, 70000],
                        'borderColor' => 'rgba(40, 167, 69, 1)',
                        'fill' => false
                    ]
                ]
            ];
        });
    }

    /**
     * Get student performance chart data via AJAX
     */
    public function getStudentPerformanceChartData(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            // Placeholder implementation
            return [
                'labels' => ['Excellent', 'Good', 'Average', 'Below Average'],
                'datasets' => [
                    [
                        'data' => [25, 35, 30, 10],
                        'backgroundColor' => [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(23, 162, 184, 0.8)',
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(220, 53, 69, 0.8)'
                        ]
                    ]
                ]
            ];
        });
    }

    /**
     * Get recent admissions via AJAX
     */
    public function getRecentAdmissions(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            // Placeholder implementation - will use actual student data in later tasks
            return [];
        });
    }

    /**
     * Get pending payments via AJAX
     */
    public function getPendingPayments(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            // Placeholder implementation
            return [];
        });
    }

    /**
     * Get latest notifications via AJAX
     */
    public function getLatestNotifications(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            // Placeholder implementation - will be enhanced with notification system
            return [];
        });
    }

    /**
     * Get activity log data via AJAX
     */
    public function getActivityLogData(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            return $this->getRecentActivities();
        });
    }

    /**
     * Get enhanced dashboard statistics for school admin
     */
    protected function getSchoolAdminStatistics(): array
    {
        $schoolId = $this->getCurrentSchoolId();
        
        // Basic counts
        $totalStudents = Student::where('school_id', $schoolId)->where('status', 'active')->count();
        $totalTeachers = Teacher::where('school_id', $schoolId)->where('is_active', true)->count();
        $totalClasses = ClassModel::where('school_id', $schoolId)->where('is_active', true)->count();
        $totalSubjects = Subject::where('school_id', $schoolId)->where('is_active', true)->count();

        // Today's attendance
        $todayAttendance = Attendance::where('school_id', $schoolId)
                                   ->where('date', Carbon::today())
                                   ->get();
        
        $presentToday = $todayAttendance->where('status', 'present')->count();
        $absentToday = $todayAttendance->where('status', 'absent')->count();
        $attendanceRate = $todayAttendance->count() > 0 ? 
            round(($presentToday / $todayAttendance->count()) * 100, 1) : 0;

        // Recent enrollments (last 30 days)
        $recentEnrollments = Student::where('school_id', $schoolId)
                                  ->where('created_at', '>=', Carbon::now()->subDays(30))
                                  ->count();

        // Grade distribution
        $recentGrades = Grade::where('school_id', $schoolId)
                           ->where('created_at', '>=', Carbon::now()->subDays(30))
                           ->get();
        
        $averageGrade = $recentGrades->avg('grade') ?? 0;

        // Notifications
        $unreadNotifications = SystemNotification::where('school_id', $schoolId)
                                                ->where('is_read', false)
                                                ->count();

        return [
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'total_classes' => $totalClasses,
            'total_subjects' => $totalSubjects,
            'present_today' => $presentToday,
            'absent_today' => $absentToday,
            'attendance_rate' => $attendanceRate,
            'recent_enrollments' => $recentEnrollments,
            'average_grade' => round($averageGrade, 2),
            'unread_notifications' => $unreadNotifications,
            'student_teacher_ratio' => $totalTeachers > 0 ? round($totalStudents / $totalTeachers, 1) : 0,
            'average_class_size' => $totalClasses > 0 ? round($totalStudents / $totalClasses, 1) : 0
        ];
    }

    /**
     * Get current school information
     */
    private function getCurrentSchool()
    {
        return $this->user->school;
    }

    /**
     * Get quick actions for school admin
     */
    private function getQuickActions(): array
    {
        return [
            [
                'title' => 'Add Student',
                'icon' => 'fas fa-user-plus',
                'url' => route('admin.students.create'),
                'color' => 'success',
                'description' => 'Enroll a new student'
            ],
            [
                'title' => 'Add Teacher',
                'icon' => 'fas fa-chalkboard-teacher',
                'url' => route('admin.teachers.create'),
                'color' => 'info',
                'description' => 'Register a new teacher'
            ],
            [
                'title' => 'Create Class',
                'icon' => 'fas fa-door-open',
                'url' => route('admin.classes.create'),
                'color' => 'warning',
                'description' => 'Set up a new class'
            ],
            [
                'title' => 'Mark Attendance',
                'icon' => 'fas fa-calendar-check',
                'url' => route('admin.attendance.create'),
                'color' => 'primary',
                'description' => 'Record student attendance'
            ],
            [
                'title' => 'View Reports',
                'icon' => 'fas fa-chart-bar',
                'url' => route('admin.reports.index'),
                'color' => 'secondary',
                'description' => 'Generate school reports'
            ],
            [
                'title' => 'Add Subject',
                'icon' => 'fas fa-book',
                'url' => route('admin.subjects.create'),
                'color' => 'danger',
                'description' => 'Create a new subject'
            ]
        ];
    }

    /**
     * Get recent students (last 10)
     */
    private function getRecentStudents()
    {
        return Student::with(['user', 'classModel'])
                     ->where('school_id', $this->getCurrentSchoolId())
                     ->latest()
                     ->limit(10)
                     ->get();
    }

    /**
     * Get upcoming events
     */
    private function getUpcomingEvents(): array
    {
        // This would typically come from an events table
        // For now, return sample data
        return [
            [
                'title' => 'Parent-Teacher Meeting',
                'date' => Carbon::now()->addDays(3),
                'type' => 'meeting',
                'description' => 'Quarterly parent-teacher conference'
            ],
            [
                'title' => 'Mid-term Examinations',
                'date' => Carbon::now()->addDays(7),
                'type' => 'exam',
                'description' => 'Mid-term exams for all classes'
            ],
            [
                'title' => 'Sports Day',
                'date' => Carbon::now()->addDays(14),
                'type' => 'event',
                'description' => 'Annual sports competition'
            ]
        ];
    }

    /**
     * Get user's dashboard widgets
     */
    private function getUserDashboardWidgets()
    {
        return DashboardWidget::where('user_id', $this->user->id)
                             ->where('is_active', true)
                             ->orderBy('position')
                             ->get();
    }

    /**
     * Get enrollment trends for charts
     */
    public function getEnrollmentTrends(Request $request): JsonResponse
    {
        return $this->handleAjaxRequest(function() {
            $schoolId = $this->getCurrentSchoolId();
            $months = [];
            $studentData = [];
            $teacherData = [];

            // Get data for last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                
                $months[] = $date->format('M Y');
                
                $studentData[] = Student::where('school_id', $schoolId)
                                      ->whereBetween('created_at', [$monthStart, $monthEnd])
                                      ->count();
                
                $teacherData[] = Teacher::where('school_id', $schoolId)
                                      ->whereBetween('created_at', [$monthStart, $monthEnd])
                                      ->count();
            }

            return [
                'months' => $months,
                'datasets' => [
                    [
                        'label' => 'Students',
                        'data' => $studentData,
                        'borderColor' => '#007bff',
                        'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                        'tension' => 0.4
                    ],
                    [
                        'label' => 'Teachers',
                        'data' => $teacherData,
                        'borderColor' => '#28a745',
                        'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                        'tension' => 0.4
                    ]
                ]
            ];
        });
    }

    /**
     * Get class performance data
     */
    public function getClassPerformance(Request $request): JsonResponse
    {
        return $this->handleAjaxRequest(function() {
            $schoolId = $this->getCurrentSchoolId();
            
            $classes = ClassModel::with(['students', 'subjects'])
                                ->where('school_id', $schoolId)
                                ->where('is_active', true)
                                ->get()
                                ->map(function ($class) {
                                    $studentCount = $class->students()->where('status', 'active')->count();
                                    $subjectCount = $class->subjects()->count();
                                    
                                    // Calculate average grade for the class
                                    $averageGrade = Grade::whereHas('student', function($q) use ($class) {
                                        $q->where('class_id', $class->id);
                                    })->avg('grade') ?? 0;

                                    return [
                                        'id' => $class->id,
                                        'name' => $class->name,
                                        'students' => $studentCount,
                                        'subjects' => $subjectCount,
                                        'average_grade' => round($averageGrade, 2),
                                        'capacity' => $class->capacity ?? 0
                                    ];
                                });

            return [
                'classes' => $classes
            ];
        });
    }

    /**
     * Get teacher workload data
     */
    public function getTeacherWorkload(Request $request): JsonResponse
    {
        return $this->handleAjaxRequest(function() {
            $schoolId = $this->getCurrentSchoolId();
            
            $teachers = Teacher::with(['user', 'subjects', 'classes'])
                              ->where('school_id', $schoolId)
                              ->where('is_active', true)
                              ->get()
                              ->map(function ($teacher) {
                                  return [
                                      'id' => $teacher->id,
                                      'name' => $teacher->user->name,
                                      'subjects' => $teacher->subjects()->count(),
                                      'classes' => $teacher->classes()->count(),
                                      'students' => $teacher->classes()->withCount('students')->get()->sum('students_count')
                                  ];
                              });

            return [
                'teachers' => $teachers
            ];
        });
    }

    /**
     * Save dashboard widget configuration
     */
    public function saveWidgetConfiguration(Request $request): JsonResponse
    {
        $request->validate([
            'widgets' => 'required|array',
            'widgets.*.widget_type' => 'required|string',
            'widgets.*.position' => 'required|integer',
            'widgets.*.is_active' => 'boolean'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            // Delete existing widgets for this user
            DashboardWidget::where('user_id', $this->user->id)->delete();

            // Create new widgets
            foreach ($request->widgets as $widgetData) {
                DashboardWidget::create([
                    'user_id' => $this->user->id,
                    'widget_type' => $widgetData['widget_type'],
                    'position' => $widgetData['position'],
                    'is_active' => $widgetData['is_active'] ?? true,
                    'configuration' => $widgetData['configuration'] ?? []
                ]);
            }

            return [
                'message' => 'Dashboard widgets updated successfully'
            ];
        });
    }
}
