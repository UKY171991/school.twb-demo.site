<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SystemNotification;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class ReportController extends BaseController
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        parent::__construct();
        $this->reportService = $reportService;
    }

    /**
     * Display the Super Admin reports dashboard
     */
    public function index(): View
    {
        // Ensure user is super admin
        if (!$this->user->isSuperAdmin()) {
            abort(403, 'Access denied. Super Admin privileges required.');
        }

        $data = [
            'page_title' => 'System Reports',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('superadmin.dashboard')],
                ['title' => 'Reports', 'url' => null]
            ],
            'schools' => School::select('id', 'name')->orderBy('name')->get(),
            'report_types' => $this->getAvailableReportTypes(),
            'date_ranges' => $this->getDateRangeOptions()
        ];

        return view('superadmin.reports.index', $data);
    }

    /**
     * Get system overview report data
     */
    public function getSystemOverview(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subYear();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $schoolIds = $request->school_ids;

        $data = $this->reportService->getSystemOverviewData($startDate, $endDate, $schoolIds);

        return $this->ajaxSuccess($data);
    }

    /**
     * Get school performance report
     */
    public function getSchoolPerformance(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subYear();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $schoolIds = $request->school_ids;

        $data = $this->reportService->getSchoolPerformanceData($startDate, $endDate, $schoolIds);

        return $this->ajaxSuccess($data);
    }

    /**
     * Get user analytics report
     */
    public function getUserAnalytics(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id',
            'user_types' => 'nullable|array',
            'user_types.*' => 'in:admin,teacher,student,parent'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subYear();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $schoolIds = $request->school_ids;
        $userTypes = $request->user_types;

        $data = $this->reportService->getUserAnalyticsData($startDate, $endDate, $schoolIds, $userTypes);

        return $this->ajaxSuccess($data);
    }

    /**
     * Get enrollment trends report
     */
    public function getEnrollmentTrends(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id',
            'period' => 'nullable|in:daily,weekly,monthly,yearly'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subYear();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $schoolIds = $request->school_ids;
        $period = $request->period ?? 'monthly';

        $data = $this->reportService->getEnrollmentTrendsData($startDate, $endDate, $schoolIds, $period);

        return $this->ajaxSuccess($data);
    }

    /**
     * Export system overview report
     */
    public function exportSystemOverview(Request $request): Response
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id',
            'format' => 'required|in:excel,pdf,csv'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subYear();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $schoolIds = $request->school_ids;
        $format = $request->format;

        return $this->reportService->exportSystemOverview($startDate, $endDate, $schoolIds, $format);
    }

    /**
     * Export school performance report
     */
    public function exportSchoolPerformance(Request $request): Response
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id',
            'format' => 'required|in:excel,pdf,csv'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subYear();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $schoolIds = $request->school_ids;
        $format = $request->format;

        return $this->reportService->exportSchoolPerformance($startDate, $endDate, $schoolIds, $format);
    }

    /**
     * Export user analytics report
     */
    public function exportUserAnalytics(Request $request): Response
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id',
            'user_types' => 'nullable|array',
            'user_types.*' => 'in:admin,teacher,student,parent',
            'format' => 'required|in:excel,pdf,csv'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subYear();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $schoolIds = $request->school_ids;
        $userTypes = $request->user_types;
        $format = $request->format;

        return $this->reportService->exportUserAnalytics($startDate, $endDate, $schoolIds, $userTypes, $format);
    }

    /**
     * Schedule automated report
     */
    public function scheduleReport(Request $request): JsonResponse
    {
        $request->validate([
            'report_type' => 'required|in:system_overview,school_performance,user_analytics,enrollment_trends',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly',
            'format' => 'required|in:excel,pdf,csv',
            'email_recipients' => 'required|array',
            'email_recipients.*' => 'email',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id',
            'user_types' => 'nullable|array',
            'user_types.*' => 'in:admin,teacher,student,parent',
            'is_active' => 'boolean'
        ]);

        $schedule = $this->reportService->scheduleAutomatedReport([
            'report_type' => $request->report_type,
            'frequency' => $request->frequency,
            'format' => $request->format,
            'email_recipients' => $request->email_recipients,
            'school_ids' => $request->school_ids,
            'user_types' => $request->user_types,
            'is_active' => $request->is_active ?? true,
            'created_by' => $this->user->id
        ]);

        return $this->ajaxSuccess([
            'message' => 'Automated report scheduled successfully',
            'schedule' => $schedule
        ]);
    }

    /**
     * Get scheduled reports
     */
    public function getScheduledReports(): JsonResponse
    {
        $schedules = $this->reportService->getScheduledReports();

        return $this->ajaxSuccess([
            'schedules' => $schedules
        ]);
    }

    /**
     * Update scheduled report
     */
    public function updateScheduledReport(Request $request, int $scheduleId): JsonResponse
    {
        $request->validate([
            'frequency' => 'nullable|in:daily,weekly,monthly,quarterly',
            'format' => 'nullable|in:excel,pdf,csv',
            'email_recipients' => 'nullable|array',
            'email_recipients.*' => 'email',
            'school_ids' => 'nullable|array',
            'school_ids.*' => 'exists:schools,id',
            'user_types' => 'nullable|array',
            'user_types.*' => 'in:admin,teacher,student,parent',
            'is_active' => 'boolean'
        ]);

        $schedule = $this->reportService->updateScheduledReport($scheduleId, $request->only([
            'frequency', 'format', 'email_recipients', 'school_ids', 'user_types', 'is_active'
        ]));

        if (!$schedule) {
            return $this->ajaxError('Scheduled report not found or access denied');
        }

        return $this->ajaxSuccess([
            'message' => 'Scheduled report updated successfully',
            'schedule' => $schedule
        ]);
    }

    /**
     * Delete scheduled report
     */
    public function deleteScheduledReport(int $scheduleId): JsonResponse
    {
        $deleted = $this->reportService->deleteScheduledReport($scheduleId);

        if (!$deleted) {
            return $this->ajaxError('Scheduled report not found or access denied');
        }

        return $this->ajaxSuccess([
            'message' => 'Scheduled report deleted successfully'
        ]);
    }

    /**
     * Get available report types
     */
    private function getAvailableReportTypes(): array
    {
        return [
            'system_overview' => [
                'name' => 'System Overview',
                'description' => 'Comprehensive system statistics and metrics',
                'icon' => 'fas fa-chart-pie'
            ],
            'school_performance' => [
                'name' => 'School Performance',
                'description' => 'Individual school performance metrics and comparisons',
                'icon' => 'fas fa-school'
            ],
            'user_analytics' => [
                'name' => 'User Analytics',
                'description' => 'User registration, activity, and engagement metrics',
                'icon' => 'fas fa-users'
            ],
            'enrollment_trends' => [
                'name' => 'Enrollment Trends',
                'description' => 'Student and teacher enrollment patterns over time',
                'icon' => 'fas fa-chart-line'
            ]
        ];
    }

    /**
     * Get date range options
     */
    private function getDateRangeOptions(): array
    {
        return [
            'last_7_days' => [
                'name' => 'Last 7 Days',
                'start_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d')
            ],
            'last_30_days' => [
                'name' => 'Last 30 Days',
                'start_date' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d')
            ],
            'last_3_months' => [
                'name' => 'Last 3 Months',
                'start_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d')
            ],
            'last_6_months' => [
                'name' => 'Last 6 Months',
                'start_date' => Carbon::now()->subMonths(6)->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d')
            ],
            'last_year' => [
                'name' => 'Last Year',
                'start_date' => Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d')
            ],
            'current_year' => [
                'name' => 'Current Year',
                'start_date' => Carbon::now()->startOfYear()->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d')
            ]
        ];
    }
}