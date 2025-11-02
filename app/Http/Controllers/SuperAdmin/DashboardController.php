<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\BaseDashboardController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SystemNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseDashboardController
{
    /**
     * Display the Super Admin dashboard
     */
    public function index(): View
    {
        // Ensure user is super admin
        if (!$this->user->isSuperAdmin()) {
            abort(403, 'Access denied. Super Admin privileges required.');
        }

        return $this->renderDashboard('superadmin.dashboard');
    }

    /**
     * Get Super Admin statistics with enhanced data
     */
    protected function getSuperAdminStatistics(): array
    {
        $schools = School::all();
        $activeSchools = $schools->where('is_active', true);
        $totalUsers = User::count();
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();

        // Calculate growth metrics
        $lastMonthUsers = User::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $lastMonthStudents = Student::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $lastMonthSchools = School::where('created_at', '>=', Carbon::now()->subMonth())->count();

        return [
            'total_schools' => $schools->count(),
            'active_schools' => $activeSchools->count(),
            'inactive_schools' => $schools->where('is_active', false)->count(),
            'total_users' => $totalUsers,
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'total_admins' => User::where('user_type', 'admin')->count(),
            'total_parents' => User::where('user_type', 'parent')->count(),
            
            // Growth metrics
            'new_users_this_month' => $lastMonthUsers,
            'new_students_this_month' => $lastMonthStudents,
            'new_schools_this_month' => $lastMonthSchools,
            
            // Performance metrics
            'average_students_per_school' => $activeSchools->count() > 0 ? round($totalStudents / $activeSchools->count(), 1) : 0,
            'average_teachers_per_school' => $activeSchools->count() > 0 ? round($totalTeachers / $activeSchools->count(), 1) : 0,
            
            // System health
            'unread_notifications' => SystemNotification::where('is_read', false)->count(),
            'recent_activity_count' => $this->getRecentActivityCount(),
        ];
    }

    /**
     * Get enrollment trends data for charts
     */
    public function getEnrollmentTrends(): JsonResponse
    {
        $months = [];
        $studentData = [];
        $teacherData = [];
        $schoolData = [];

        // Get data for last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $months[] = $date->format('M Y');
            
            $studentData[] = Student::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $teacherData[] = Teacher::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $schoolData[] = School::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        }

        return $this->ajaxSuccess([
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
                ],
                [
                    'label' => 'Schools',
                    'data' => $schoolData,
                    'borderColor' => '#ffc107',
                    'backgroundColor' => 'rgba(255, 193, 7, 0.1)',
                    'tension' => 0.4
                ]
            ]
        ]);
    }

    /**
     * Get user activity data for charts
     */
    public function getUserActivity(): JsonResponse
    {
        $days = [];
        $loginData = [];
        $registrationData = [];

        // Get data for last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            $days[] = $date->format('M d');
            
            $loginData[] = User::whereBetween('last_login_at', [$dayStart, $dayEnd])->count();
            $registrationData[] = User::whereBetween('created_at', [$dayStart, $dayEnd])->count();
        }

        return $this->ajaxSuccess([
            'days' => $days,
            'datasets' => [
                [
                    'label' => 'Daily Logins',
                    'data' => $loginData,
                    'borderColor' => '#17a2b8',
                    'backgroundColor' => 'rgba(23, 162, 184, 0.1)',
                    'tension' => 0.4
                ],
                [
                    'label' => 'New Registrations',
                    'data' => $registrationData,
                    'borderColor' => '#dc3545',
                    'backgroundColor' => 'rgba(220, 53, 69, 0.1)',
                    'tension' => 0.4
                ]
            ]
        ]);
    }

    /**
     * Get school performance data
     */
    public function getSchoolPerformance(): JsonResponse
    {
        $schools = School::with(['students', 'teachers'])
                        ->where('is_active', true)
                        ->get()
                        ->map(function ($school) {
                            $stats = $school->getStatistics();
                            $performance = $school->getPerformanceMetrics();
                            
                            return [
                                'id' => $school->id,
                                'name' => $school->name,
                                'students' => $stats['total_students'],
                                'teachers' => $stats['total_teachers'],
                                'student_teacher_ratio' => $performance['student_teacher_ratio'],
                                'attendance_rate' => $performance['attendance_rate'],
                                'status' => $school->is_active ? 'Active' : 'Inactive'
                            ];
                        });

        return $this->ajaxSuccess([
            'schools' => $schools,
            'summary' => [
                'total_schools' => $schools->count(),
                'avg_students_per_school' => $schools->avg('students'),
                'avg_teachers_per_school' => $schools->avg('teachers'),
                'avg_student_teacher_ratio' => $schools->avg('student_teacher_ratio'),
                'avg_attendance_rate' => $schools->avg('attendance_rate')
            ]
        ]);
    }

    /**
     * Get system health metrics
     */
    public function getSystemHealth(): JsonResponse
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $recentlyActiveUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(7))->count();
        
        $totalSchools = School::count();
        $activeSchools = School::where('is_active', true)->count();
        
        $unreadNotifications = SystemNotification::where('is_read', false)->count();
        $totalNotifications = SystemNotification::count();

        return $this->ajaxSuccess([
            'user_activity' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
                'recently_active' => $recentlyActiveUsers,
                'activity_rate' => $totalUsers > 0 ? round(($recentlyActiveUsers / $totalUsers) * 100, 1) : 0
            ],
            'school_status' => [
                'total' => $totalSchools,
                'active' => $activeSchools,
                'inactive' => $totalSchools - $activeSchools,
                'active_rate' => $totalSchools > 0 ? round(($activeSchools / $totalSchools) * 100, 1) : 0
            ],
            'notifications' => [
                'total' => $totalNotifications,
                'unread' => $unreadNotifications,
                'read_rate' => $totalNotifications > 0 ? round((($totalNotifications - $unreadNotifications) / $totalNotifications) * 100, 1) : 0
            ]
        ]);
    }

    /**
     * Get recent activities across all schools
     */
    public function getRecentActivities(): JsonResponse
    {
        $activities = collect();

        // Recent user registrations
        $recentUsers = User::with('school')
                          ->where('created_at', '>=', Carbon::now()->subDays(7))
                          ->orderBy('created_at', 'desc')
                          ->limit(10)
                          ->get()
                          ->map(function ($user) {
                              return [
                                  'type' => 'user_registration',
                                  'message' => "New {$user->getRoleDisplayNameAttribute()} registered: {$user->name}",
                                  'school' => $user->school ? $user->school->name : 'System',
                                  'date' => $user->created_at,
                                  'icon' => 'fas fa-user-plus',
                                  'color' => 'success'
                              ];
                          });

        // Recent school creations
        $recentSchools = School::where('created_at', '>=', Carbon::now()->subDays(7))
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get()
                              ->map(function ($school) {
                                  return [
                                      'type' => 'school_creation',
                                      'message' => "New school created: {$school->name}",
                                      'school' => $school->name,
                                      'date' => $school->created_at,
                                      'icon' => 'fas fa-school',
                                      'color' => 'primary'
                                  ];
                              });

        // Recent student enrollments
        $recentStudents = Student::with(['school', 'user'])
                                ->where('created_at', '>=', Carbon::now()->subDays(7))
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get()
                                ->map(function ($student) {
                                    return [
                                        'type' => 'student_enrollment',
                                        'message' => "New student enrolled: {$student->first_name} {$student->last_name}",
                                        'school' => $student->school ? $student->school->name : 'Unknown',
                                        'date' => $student->created_at,
                                        'icon' => 'fas fa-user-graduate',
                                        'color' => 'info'
                                    ];
                                });

        $activities = $activities->concat($recentUsers)
                               ->concat($recentSchools)
                               ->concat($recentStudents)
                               ->sortByDesc('date')
                               ->take(20)
                               ->values();

        return $this->ajaxSuccess([
            'activities' => $activities
        ]);
    }

    /**
     * Get recent activity count for statistics
     */
    private function getRecentActivityCount(): int
    {
        $recentUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $recentSchools = School::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $recentStudents = Student::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        
        return $recentUsers + $recentSchools + $recentStudents;
    }
}
