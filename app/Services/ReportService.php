<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SystemNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    /**
     * Get system overview data
     */
    public function getSystemOverviewData(Carbon $startDate, Carbon $endDate, ?array $schoolIds = null): array
    {
        $schoolQuery = School::query();
        if ($schoolIds) {
            $schoolQuery->whereIn('id', $schoolIds);
        }

        $userQuery = User::whereBetween('created_at', [$startDate, $endDate]);
        $studentQuery = Student::whereBetween('created_at', [$startDate, $endDate]);
        $teacherQuery = Teacher::whereBetween('created_at', [$startDate, $endDate]);

        if ($schoolIds) {
            $userQuery->whereIn('school_id', $schoolIds);
            $studentQuery->whereIn('school_id', $schoolIds);
            $teacherQuery->whereIn('school_id', $schoolIds);
        }

        // Basic statistics
        $totalSchools = $schoolQuery->count();
        $activeSchools = $schoolQuery->where('is_active', true)->count();
        $totalUsers = $userQuery->count();
        $totalStudents = $studentQuery->count();
        $totalTeachers = $teacherQuery->count();

        // Growth metrics
        $previousPeriodStart = $startDate->copy()->subDays($endDate->diffInDays($startDate));
        $previousPeriodEnd = $startDate->copy()->subDay();

        $previousUsers = User::whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd]);
        $previousStudents = Student::whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd]);
        $previousTeachers = Teacher::whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd]);

        if ($schoolIds) {
            $previousUsers->whereIn('school_id', $schoolIds);
            $previousStudents->whereIn('school_id', $schoolIds);
            $previousTeachers->whereIn('school_id', $schoolIds);
        }

        $previousUserCount = $previousUsers->count();
        $previousStudentCount = $previousStudents->count();
        $previousTeacherCount = $previousTeachers->count();

        // Calculate growth percentages
        $userGrowth = $previousUserCount > 0 ? (($totalUsers - $previousUserCount) / $previousUserCount) * 100 : 0;
        $studentGrowth = $previousStudentCount > 0 ? (($totalStudents - $previousStudentCount) / $previousStudentCount) * 100 : 0;
        $teacherGrowth = $previousTeacherCount > 0 ? (($totalTeachers - $previousTeacherCount) / $previousTeacherCount) * 100 : 0;

        // User type distribution
        $userTypeDistribution = User::select('user_type', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($schoolIds) {
            $userTypeDistribution->whereIn('school_id', $schoolIds);
        }

        $userTypeDistribution = $userTypeDistribution->groupBy('user_type')->get();

        // Activity metrics
        $activeUsers = User::where('last_login_at', '>=', $startDate);
        if ($schoolIds) {
            $activeUsers->whereIn('school_id', $schoolIds);
        }
        $activeUserCount = $activeUsers->count();

        return [
            'summary' => [
                'total_schools' => $totalSchools,
                'active_schools' => $activeSchools,
                'total_users' => $totalUsers,
                'total_students' => $totalStudents,
                'total_teachers' => $teacherQuery->count(),
                'active_users' => $activeUserCount
            ],
            'growth' => [
                'user_growth' => round($userGrowth, 2),
                'student_growth' => round($studentGrowth, 2),
                'teacher_growth' => round($teacherGrowth, 2)
            ],
            'user_distribution' => $userTypeDistribution->mapWithKeys(function ($item) {
                return [ucfirst($item->user_type) => $item->count];
            })->toArray(),
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'days' => $endDate->diffInDays($startDate)
            ]
        ];
    }

    /**
     * Get school performance data
     */
    public function getSchoolPerformanceData(Carbon $startDate, Carbon $endDate, ?array $schoolIds = null): array
    {
        $schoolQuery = School::with(['students', 'teachers', 'users']);
        
        if ($schoolIds) {
            $schoolQuery->whereIn('id', $schoolIds);
        }

        $schools = $schoolQuery->get()->map(function ($school) use ($startDate, $endDate) {
            $stats = $school->getStatistics();
            
            // Get period-specific data
            $periodStudents = $school->students()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            
            $periodTeachers = $school->teachers()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $periodUsers = $school->users()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Calculate ratios and metrics
            $studentTeacherRatio = $stats['total_teachers'] > 0 ? 
                round($stats['total_students'] / $stats['total_teachers'], 2) : 0;

            return [
                'id' => $school->id,
                'name' => $school->name,
                'code' => $school->code,
                'is_active' => $school->is_active,
                'total_students' => $stats['total_students'],
                'total_teachers' => $stats['total_teachers'],
                'total_users' => $stats['total_users'],
                'period_students' => $periodStudents,
                'period_teachers' => $periodTeachers,
                'period_users' => $periodUsers,
                'student_teacher_ratio' => $studentTeacherRatio,
                'performance_score' => $this->calculateSchoolPerformanceScore($school, $stats)
            ];
        });

        // Calculate system averages
        $systemAverages = [
            'avg_students_per_school' => $schools->avg('total_students'),
            'avg_teachers_per_school' => $schools->avg('total_teachers'),
            'avg_student_teacher_ratio' => $schools->avg('student_teacher_ratio'),
            'avg_performance_score' => $schools->avg('performance_score')
        ];

        return [
            'schools' => $schools->values()->toArray(),
            'system_averages' => array_map(function ($value) {
                return round($value, 2);
            }, $systemAverages),
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]
        ];
    }

    /**
     * Get user analytics data
     */
    public function getUserAnalyticsData(Carbon $startDate, Carbon $endDate, ?array $schoolIds = null, ?array $userTypes = null): array
    {
        $userQuery = User::whereBetween('created_at', [$startDate, $endDate]);

        if ($schoolIds) {
            $userQuery->whereIn('school_id', $schoolIds);
        }

        if ($userTypes) {
            $userQuery->whereIn('user_type', $userTypes);
        }

        // Registration trends
        $registrationTrends = $this->getRegistrationTrends($startDate, $endDate, $schoolIds, $userTypes);

        // Activity metrics
        $activityMetrics = $this->getUserActivityMetrics($startDate, $endDate, $schoolIds, $userTypes);

        // User type breakdown
        $userTypeBreakdown = User::select('user_type', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($schoolIds) {
            $userTypeBreakdown->whereIn('school_id', $schoolIds);
        }

        if ($userTypes) {
            $userTypeBreakdown->whereIn('user_type', $userTypes);
        }

        $userTypeBreakdown = $userTypeBreakdown->groupBy('user_type')->get();

        // School distribution
        $schoolDistribution = User::select('schools.name as school_name', DB::raw('count(users.id) as user_count'))
            ->join('schools', 'users.school_id', '=', 'schools.id')
            ->whereBetween('users.created_at', [$startDate, $endDate]);

        if ($schoolIds) {
            $schoolDistribution->whereIn('users.school_id', $schoolIds);
        }

        if ($userTypes) {
            $schoolDistribution->whereIn('users.user_type', $userTypes);
        }

        $schoolDistribution = $schoolDistribution->groupBy('schools.name')->get();

        return [
            'registration_trends' => $registrationTrends,
            'activity_metrics' => $activityMetrics,
            'user_type_breakdown' => $userTypeBreakdown->mapWithKeys(function ($item) {
                return [ucfirst($item->user_type) => $item->count];
            })->toArray(),
            'school_distribution' => $schoolDistribution->mapWithKeys(function ($item) {
                return [$item->school_name => $item->user_count];
            })->toArray(),
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]
        ];
    }

    /**
     * Get enrollment trends data
     */
    public function getEnrollmentTrendsData(Carbon $startDate, Carbon $endDate, ?array $schoolIds = null, string $period = 'monthly'): array
    {
        $trends = [];
        $labels = [];

        switch ($period) {
            case 'daily':
                $current = $startDate->copy();
                while ($current->lte($endDate)) {
                    $labels[] = $current->format('M d');
                    $trends[] = $this->getEnrollmentForPeriod($current, $current->copy()->endOfDay(), $schoolIds);
                    $current->addDay();
                }
                break;

            case 'weekly':
                $current = $startDate->copy()->startOfWeek();
                while ($current->lte($endDate)) {
                    $weekEnd = $current->copy()->endOfWeek();
                    $labels[] = $current->format('M d') . ' - ' . $weekEnd->format('M d');
                    $trends[] = $this->getEnrollmentForPeriod($current, $weekEnd, $schoolIds);
                    $current->addWeek();
                }
                break;

            case 'monthly':
                $current = $startDate->copy()->startOfMonth();
                while ($current->lte($endDate)) {
                    $monthEnd = $current->copy()->endOfMonth();
                    $labels[] = $current->format('M Y');
                    $trends[] = $this->getEnrollmentForPeriod($current, $monthEnd, $schoolIds);
                    $current->addMonth();
                }
                break;

            case 'yearly':
                $current = $startDate->copy()->startOfYear();
                while ($current->lte($endDate)) {
                    $yearEnd = $current->copy()->endOfYear();
                    $labels[] = $current->format('Y');
                    $trends[] = $this->getEnrollmentForPeriod($current, $yearEnd, $schoolIds);
                    $current->addYear();
                }
                break;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => array_column($trends, 'students'),
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.1)'
                ],
                [
                    'label' => 'Teachers',
                    'data' => array_column($trends, 'teachers'),
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)'
                ]
            ],
            'period' => $period,
            'date_range' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]
        ];
    }

    /**
     * Export system overview report
     */
    public function exportSystemOverview(Carbon $startDate, Carbon $endDate, ?array $schoolIds = null, string $format = 'excel'): Response
    {
        $data = $this->getSystemOverviewData($startDate, $endDate, $schoolIds);
        
        $filename = 'system_overview_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d');
        
        return $this->generateExport($data, $filename, $format, 'System Overview Report');
    }

    /**
     * Export school performance report
     */
    public function exportSchoolPerformance(Carbon $startDate, Carbon $endDate, ?array $schoolIds = null, string $format = 'excel'): Response
    {
        $data = $this->getSchoolPerformanceData($startDate, $endDate, $schoolIds);
        
        $filename = 'school_performance_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d');
        
        return $this->generateExport($data, $filename, $format, 'School Performance Report');
    }

    /**
     * Export user analytics report
     */
    public function exportUserAnalytics(Carbon $startDate, Carbon $endDate, ?array $schoolIds = null, ?array $userTypes = null, string $format = 'excel'): Response
    {
        $data = $this->getUserAnalyticsData($startDate, $endDate, $schoolIds, $userTypes);
        
        $filename = 'user_analytics_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d');
        
        return $this->generateExport($data, $filename, $format, 'User Analytics Report');
    }

    /**
     * Schedule automated report
     */
    public function scheduleAutomatedReport(array $data): array
    {
        // In a real implementation, this would create a database record
        // and set up a scheduled job using Laravel's task scheduler
        
        $schedule = [
            'id' => uniqid(),
            'report_type' => $data['report_type'],
            'frequency' => $data['frequency'],
            'format' => $data['format'],
            'email_recipients' => $data['email_recipients'],
            'school_ids' => $data['school_ids'] ?? null,
            'user_types' => $data['user_types'] ?? null,
            'is_active' => $data['is_active'],
            'created_by' => $data['created_by'],
            'created_at' => now(),
            'next_run_at' => $this->calculateNextRunDate($data['frequency'])
        ];

        // Store in session for demo purposes (in real app, store in database)
        $schedules = session('report_schedules', []);
        $schedules[] = $schedule;
        session(['report_schedules' => $schedules]);

        return $schedule;
    }

    /**
     * Get scheduled reports
     */
    public function getScheduledReports(): array
    {
        return session('report_schedules', []);
    }

    /**
     * Update scheduled report
     */
    public function updateScheduledReport(int $scheduleId, array $data): ?array
    {
        $schedules = session('report_schedules', []);
        
        foreach ($schedules as &$schedule) {
            if ($schedule['id'] == $scheduleId) {
                $schedule = array_merge($schedule, $data);
                $schedule['updated_at'] = now();
                
                if (isset($data['frequency'])) {
                    $schedule['next_run_at'] = $this->calculateNextRunDate($data['frequency']);
                }
                
                session(['report_schedules' => $schedules]);
                return $schedule;
            }
        }

        return null;
    }

    /**
     * Delete scheduled report
     */
    public function deleteScheduledReport(int $scheduleId): bool
    {
        $schedules = session('report_schedules', []);
        
        foreach ($schedules as $key => $schedule) {
            if ($schedule['id'] == $scheduleId) {
                unset($schedules[$key]);
                session(['report_schedules' => array_values($schedules)]);
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate school performance score
     */
    private function calculateSchoolPerformanceScore(School $school, array $stats): float
    {
        // Simple performance scoring algorithm
        $score = 0;
        
        // Student-teacher ratio score (lower is better, max 40 points)
        $ratio = $stats['total_teachers'] > 0 ? $stats['total_students'] / $stats['total_teachers'] : 0;
        if ($ratio <= 15) {
            $score += 40;
        } elseif ($ratio <= 20) {
            $score += 30;
        } elseif ($ratio <= 25) {
            $score += 20;
        } else {
            $score += 10;
        }

        // School size score (30 points)
        if ($stats['total_students'] >= 500) {
            $score += 30;
        } elseif ($stats['total_students'] >= 200) {
            $score += 25;
        } elseif ($stats['total_students'] >= 100) {
            $score += 20;
        } else {
            $score += 15;
        }

        // Activity score (30 points)
        if ($school->is_active) {
            $score += 30;
        }

        return round($score, 2);
    }

    /**
     * Get registration trends
     */
    private function getRegistrationTrends(Carbon $startDate, Carbon $endDate, ?array $schoolIds = null, ?array $userTypes = null): array
    {
        $trends = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $dayEnd = $current->copy()->endOfDay();
            
            $query = User::whereBetween('created_at', [$current, $dayEnd]);
            
            if ($schoolIds) {
                $query->whereIn('school_id', $schoolIds);
            }
            
            if ($userTypes) {
                $query->whereIn('user_type', $userTypes);
            }

            $trends[] = [
                'date' => $current->format('Y-m-d'),
                'count' => $query->count()
            ];

            $current->addDay();
        }

        return $trends;
    }

    /**
     * Get user activity metrics
     */
    private function getUserActivityMetrics(Carbon $startDate, Carbon $endDate, ?array $schoolIds = null, ?array $userTypes = null): array
    {
        $query = User::whereBetween('created_at', [$startDate, $endDate]);

        if ($schoolIds) {
            $query->whereIn('school_id', $schoolIds);
        }

        if ($userTypes) {
            $query->whereIn('user_type', $userTypes);
        }

        $totalUsers = $query->count();
        $activeUsers = $query->where('last_login_at', '>=', $startDate)->count();
        $recentlyActiveUsers = $query->where('last_login_at', '>=', Carbon::now()->subDays(7))->count();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'recently_active_users' => $recentlyActiveUsers,
            'activity_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0,
            'recent_activity_rate' => $totalUsers > 0 ? round(($recentlyActiveUsers / $totalUsers) * 100, 2) : 0
        ];
    }

    /**
     * Get enrollment for specific period
     */
    private function getEnrollmentForPeriod(Carbon $start, Carbon $end, ?array $schoolIds = null): array
    {
        $studentQuery = Student::whereBetween('created_at', [$start, $end]);
        $teacherQuery = Teacher::whereBetween('created_at', [$start, $end]);

        if ($schoolIds) {
            $studentQuery->whereIn('school_id', $schoolIds);
            $teacherQuery->whereIn('school_id', $schoolIds);
        }

        return [
            'students' => $studentQuery->count(),
            'teachers' => $teacherQuery->count()
        ];
    }

    /**
     * Generate export file
     */
    private function generateExport(array $data, string $filename, string $format, string $title): Response
    {
        switch ($format) {
            case 'csv':
                return $this->generateCSVExport($data, $filename);
            case 'pdf':
                return $this->generatePDFExport($data, $filename, $title);
            case 'excel':
            default:
                return $this->generateExcelExport($data, $filename, $title);
        }
    }

    /**
     * Generate CSV export
     */
    private function generateCSVExport(array $data, string $filename): Response
    {
        $csv = "Report Data Export\n";
        $csv .= "Generated: " . now()->format('Y-m-d H:i:s') . "\n\n";
        
        // Convert array data to CSV format
        $csv .= $this->arrayToCSV($data);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"'
        ]);
    }

    /**
     * Generate Excel export (simplified)
     */
    private function generateExcelExport(array $data, string $filename, string $title): Response
    {
        // For demo purposes, return CSV with Excel headers
        // In production, use a library like PhpSpreadsheet
        $content = $this->arrayToCSV($data);

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xls"'
        ]);
    }

    /**
     * Generate PDF export (simplified)
     */
    private function generatePDFExport(array $data, string $filename, string $title): Response
    {
        // For demo purposes, return HTML content
        // In production, use a library like DomPDF or wkhtmltopdf
        $html = "<h1>{$title}</h1>";
        $html .= "<p>Generated: " . now()->format('Y-m-d H:i:s') . "</p>";
        $html .= "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.html"'
        ]);
    }

    /**
     * Convert array to CSV format
     */
    private function arrayToCSV(array $data): string
    {
        $csv = '';
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $csv .= strtoupper($key) . "\n";
                foreach ($value as $subKey => $subValue) {
                    if (is_array($subValue)) {
                        $csv .= $subKey . "," . implode(',', $subValue) . "\n";
                    } else {
                        $csv .= $subKey . "," . $subValue . "\n";
                    }
                }
                $csv .= "\n";
            } else {
                $csv .= $key . "," . $value . "\n";
            }
        }
        
        return $csv;
    }

    /**
     * Calculate next run date based on frequency
     */
    private function calculateNextRunDate(string $frequency): Carbon
    {
        switch ($frequency) {
            case 'daily':
                return Carbon::now()->addDay();
            case 'weekly':
                return Carbon::now()->addWeek();
            case 'monthly':
                return Carbon::now()->addMonth();
            case 'quarterly':
                return Carbon::now()->addMonths(3);
            default:
                return Carbon::now()->addDay();
        }
    }
}