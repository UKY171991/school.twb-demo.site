<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\BaseAjaxController;
use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommonController extends BaseAjaxController
{
    /**
     * Get schools for select dropdown
     */
    public function getSchools(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $query = School::where('is_active', true);
            return $this->select2Response($query, $request, 'name', 'id');
        });
    }

    /**
     * Get users for select dropdown
     */
    public function getUsers(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $userType = $request->get('user_type');
            
            $query = User::where('is_active', true);
            
            if ($userType) {
                $query->where('user_type', $userType);
            }
            
            return $this->select2Response($query, $request, 'name', 'id');
        });
    }

    /**
     * Get students for select dropdown
     */
    public function getStudents(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $classId = $request->get('class_id');
            
            $query = Student::where('status', 'active');
            
            if ($classId) {
                $query->where('class_id', $classId);
            }
            
            // Use full name for display
            $students = $query->get()->map(function($student) {
                return [
                    'id' => $student->id,
                    'text' => $student->full_name . ' (' . $student->student_id . ')'
                ];
            });
            
            return $this->successResponse('Students loaded successfully', $students);
        });
    }

    /**
     * Get teachers for select dropdown
     */
    public function getTeachers(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $subjectId = $request->get('subject_id');
            
            $query = Teacher::with('user')->whereHas('user', function($q) {
                $q->where('is_active', true);
            });
            
            if ($subjectId) {
                // Assuming teachers have subjects relationship
                $query->whereHas('subjects', function($q) use ($subjectId) {
                    $q->where('subject_id', $subjectId);
                });
            }
            
            $teachers = $query->get()->map(function($teacher) {
                return [
                    'id' => $teacher->id,
                    'text' => $teacher->user->name . ' (' . $teacher->employee_id . ')'
                ];
            });
            
            return $this->successResponse('Teachers loaded successfully', $teachers);
        });
    }

    /**
     * Get classes for select dropdown
     */
    public function getClasses(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $query = ClassModel::where('is_active', true);
            return $this->select2Response($query, $request, 'name', 'id');
        });
    }

    /**
     * Get subjects for select dropdown
     */
    public function getSubjects(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $classId = $request->get('class_id');
            
            $query = Subject::where('is_active', true);
            
            if ($classId) {
                // Assuming subjects are linked to classes
                $query->where('class_id', $classId);
            }
            
            return $this->select2Response($query, $request, 'name', 'id');
        });
    }

    /**
     * Upload file via AJAX
     */
    public function uploadFile(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $this->validateAjaxRequest($request, [
                'file' => 'required|file|max:2048', // 2MB max
                'type' => 'required|string|in:image,document,avatar'
            ]);

            $file = $request->file('file');
            $type = $request->get('type');
            
            // Define allowed extensions based on type
            $allowedExtensions = match($type) {
                'image' => ['jpg', 'jpeg', 'png', 'gif'],
                'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
                'avatar' => ['jpg', 'jpeg', 'png'],
                default => []
            };
            
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return $this->errorResponse('Invalid file type for ' . $type);
            }
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('uploads/' . $type, $filename, 'public');
            
            return $this->successResponse('File uploaded successfully', [
                'filename' => $filename,
                'path' => $path,
                'url' => asset('storage/' . $path),
                'size' => $file->getSize(),
                'original_name' => $file->getClientOriginalName()
            ]);
        });
    }

    /**
     * Delete file via AJAX
     */
    public function deleteFile(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $this->validateAjaxRequest($request, [
                'path' => 'required|string'
            ]);

            $path = $request->get('path');
            
            // Security check - ensure path is within uploads directory
            if (!str_starts_with($path, 'uploads/')) {
                return $this->errorResponse('Invalid file path');
            }
            
            if (\Storage::disk('public')->exists($path)) {
                \Storage::disk('public')->delete($path);
                return $this->successResponse('File deleted successfully');
            }
            
            return $this->notFoundResponse('File not found');
        });
    }

    /**
     * Get system notifications
     */
    public function getNotifications(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $limit = $request->get('limit', 10);
            $unreadOnly = $request->get('unread_only', false);
            
            $notifications = \App\Services\NotificationService::getForUser(
                $this->user->id, 
                $limit, 
                $unreadOnly
            );
            
            return $this->successResponse('Notifications loaded successfully', $notifications);
        });
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead(Request $request, int $notificationId): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($notificationId) {
            $success = \App\Services\NotificationService::markAsRead($notificationId, $this->user->id);
            
            if ($success) {
                return $this->successResponse('Notification marked as read');
            } else {
                return $this->notFoundResponse('Notification not found');
            }
        });
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() {
            $count = \App\Services\NotificationService::markAllAsReadForUser($this->user->id);
            
            return $this->successResponse("Marked {$count} notifications as read");
        });
    }

    /**
     * Get notification count
     */
    public function getNotificationCount(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() {
            $count = \App\Services\NotificationService::getUnreadCountForUser($this->user->id);
            
            return $this->successResponse('Notification count retrieved', ['count' => $count]);
        });
    }

    /**
     * Get dashboard widget data
     */
    public function getWidgetData(Request $request): JsonResponse
    {
        $ajaxCheck = $this->requireAjaxRequest($request);
        if ($ajaxCheck) return $ajaxCheck;

        return $this->handleAjaxRequest(function() use ($request) {
            $this->validateAjaxRequest($request, [
                'widget_type' => 'required|string'
            ]);

            $widgetType = $request->get('widget_type');
            
            // Get widget data based on type and user role
            $data = match($widgetType) {
                'recent_activities' => $this->getRecentActivitiesWidget(),
                'statistics_overview' => $this->getStatisticsOverviewWidget(),
                'attendance_summary' => $this->getAttendanceSummaryWidget(),
                default => []
            };
            
            return $this->successResponse('Widget data loaded successfully', $data);
        });
    }

    /**
     * Get recent activities widget data
     */
    private function getRecentActivitiesWidget(): array
    {
        // Placeholder implementation
        return [
            'activities' => [
                ['message' => 'New student enrolled', 'time' => '2 hours ago'],
                ['message' => 'Grade updated for Math class', 'time' => '4 hours ago'],
                ['message' => 'Attendance marked for today', 'time' => '6 hours ago'],
            ]
        ];
    }

    /**
     * Get statistics overview widget data
     */
    private function getStatisticsOverviewWidget(): array
    {
        // Use base controller method to get statistics
        return $this->getDashboardStatistics();
    }

    /**
     * Get attendance summary widget data
     */
    private function getAttendanceSummaryWidget(): array
    {
        // Placeholder implementation
        return [
            'present_today' => 85,
            'absent_today' => 15,
            'total_students' => 100,
            'attendance_percentage' => 85
        ];
    }
}