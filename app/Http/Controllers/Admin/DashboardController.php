<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseDashboardController;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends BaseDashboardController
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Get dashboard data using base controller methods
        $viewData = $this->getDashboardViewData();
        
        // Add admin-specific data
        $viewData['recentActivities'] = $this->getRecentActivities();
        $viewData['pageTitle'] = 'School Administration Dashboard';
        
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
}
