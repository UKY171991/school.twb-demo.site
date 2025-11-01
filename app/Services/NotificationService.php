<?php

namespace App\Services;

use App\Models\SystemNotification;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Send notification to a specific user
     */
    public static function sendToUser(
        int $userId, 
        string $title, 
        string $message, 
        string $type = 'info',
        array $options = []
    ): SystemNotification {
        return SystemNotification::notifyUser($userId, $title, $message, $type, $options);
    }

    /**
     * Send notification to multiple users
     */
    public static function sendToUsers(
        array $userIds, 
        string $title, 
        string $message, 
        string $type = 'info',
        array $options = []
    ): int {
        $count = 0;
        foreach ($userIds as $userId) {
            self::sendToUser($userId, $title, $message, $type, $options);
            $count++;
        }
        return $count;
    }

    /**
     * Send notification to all users in a school
     */
    public static function sendToSchool(
        int $schoolId, 
        string $title, 
        string $message, 
        string $type = 'info',
        array $options = []
    ): int {
        return SystemNotification::notifySchoolUsers($schoolId, $title, $message, $type, $options);
    }

    /**
     * Send notification to users by role
     */
    public static function sendToRole(
        string $role, 
        string $title, 
        string $message, 
        string $type = 'info',
        ?int $schoolId = null,
        array $options = []
    ): int {
        $query = User::where('user_type', $role)->where('is_active', true);
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        
        $users = $query->get();
        $count = 0;
        
        foreach ($users as $user) {
            self::sendToUser($user->id, $title, $message, $type, array_merge($options, [
                'school_id' => $schoolId
            ]));
            $count++;
        }
        
        return $count;
    }

    /**
     * Send notification to all super admins
     */
    public static function sendToSuperAdmins(
        string $title, 
        string $message, 
        string $type = 'info',
        array $options = []
    ): int {
        return self::sendToRole('super_admin', $title, $message, $type, null, $options);
    }

    /**
     * Send notification to all school admins
     */
    public static function sendToSchoolAdmins(
        string $title, 
        string $message, 
        string $type = 'info',
        ?int $schoolId = null,
        array $options = []
    ): int {
        return self::sendToRole('admin', $title, $message, $type, $schoolId, $options);
    }

    /**
     * Send notification to all teachers
     */
    public static function sendToTeachers(
        string $title, 
        string $message, 
        string $type = 'info',
        ?int $schoolId = null,
        array $options = []
    ): int {
        return self::sendToRole('teacher', $title, $message, $type, $schoolId, $options);
    }

    /**
     * Send notification to all students
     */
    public static function sendToStudents(
        string $title, 
        string $message, 
        string $type = 'info',
        ?int $schoolId = null,
        array $options = []
    ): int {
        return self::sendToRole('student', $title, $message, $type, $schoolId, $options);
    }

    /**
     * Send notification to all parents
     */
    public static function sendToParents(
        string $title, 
        string $message, 
        string $type = 'info',
        ?int $schoolId = null,
        array $options = []
    ): int {
        return self::sendToRole('parent', $title, $message, $type, $schoolId, $options);
    }

    /**
     * Get notifications for a user
     */
    public static function getForUser(int $userId, int $limit = 50, bool $unreadOnly = false): Collection
    {
        $query = SystemNotification::forUser($userId)
                                  ->orderBy('created_at', 'desc')
                                  ->limit($limit);
        
        if ($unreadOnly) {
            $query->unread();
        }
        
        return $query->get();
    }

    /**
     * Get unread count for user
     */
    public static function getUnreadCountForUser(int $userId): int
    {
        return SystemNotification::forUser($userId)->unread()->count();
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = SystemNotification::where('id', $notificationId)
                                        ->where('user_id', $userId)
                                        ->first();
        
        if (!$notification) {
            return false;
        }
        
        return $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for user
     */
    public static function markAllAsReadForUser(int $userId): int
    {
        return SystemNotification::markAllAsReadForUser($userId);
    }

    /**
     * Delete notification
     */
    public static function deleteNotification(int $notificationId, int $userId): bool
    {
        return SystemNotification::where('id', $notificationId)
                                ->where('user_id', $userId)
                                ->delete() > 0;
    }

    /**
     * System event notifications
     */
    public static function studentEnrolled(int $studentId, int $schoolId): void
    {
        $student = \App\Models\Student::find($studentId);
        if (!$student) return;

        // Notify school admins
        self::sendToSchoolAdmins(
            'New Student Enrolled',
            "Student {$student->full_name} has been enrolled in the school.",
            'success',
            $schoolId,
            ['action_url' => route('admin.students.show', $studentId)]
        );

        // Notify student
        if ($student->user_id) {
            self::sendToUser(
                $student->user_id,
                'Welcome to School',
                'Your enrollment has been completed successfully. Welcome to our school!',
                'success',
                ['action_url' => route('student.dashboard')]
            );
        }

        // Notify parent if exists
        if ($student->parent_id && $student->parent->user_id) {
            self::sendToUser(
                $student->parent->user_id,
                'Child Enrolled',
                "Your child {$student->full_name} has been successfully enrolled.",
                'success',
                ['action_url' => route('parent.children.show', $studentId)]
            );
        }
    }

    /**
     * Teacher assigned notification
     */
    public static function teacherAssigned(int $teacherId, int $classId, int $schoolId): void
    {
        $teacher = \App\Models\Teacher::find($teacherId);
        $class = \App\Models\ClassModel::find($classId);
        
        if (!$teacher || !$class) return;

        // Notify teacher
        if ($teacher->user_id) {
            self::sendToUser(
                $teacher->user_id,
                'Class Assignment',
                "You have been assigned to class {$class->name}.",
                'info',
                ['action_url' => route('teacher.classes.show', $classId)]
            );
        }

        // Notify school admins
        self::sendToSchoolAdmins(
            'Teacher Assignment',
            "Teacher {$teacher->full_name} has been assigned to class {$class->name}.",
            'info',
            $schoolId,
            ['action_url' => route('admin.classes.show', $classId)]
        );
    }

    /**
     * Grade entered notification
     */
    public static function gradeEntered(int $gradeId): void
    {
        $grade = \App\Models\Grade::with(['student', 'subject', 'teacher'])->find($gradeId);
        if (!$grade) return;

        // Notify student
        if ($grade->student->user_id) {
            self::sendToUser(
                $grade->student->user_id,
                'New Grade Available',
                "Your grade for {$grade->subject->name} has been updated: {$grade->grade}",
                'info',
                ['action_url' => route('student.grades')]
            );
        }

        // Notify parent
        if ($grade->student->parent_id && $grade->student->parent->user_id) {
            self::sendToUser(
                $grade->student->parent->user_id,
                'Child\'s Grade Updated',
                "{$grade->student->full_name}'s grade for {$grade->subject->name} has been updated: {$grade->grade}",
                'info',
                ['action_url' => route('parent.grades')]
            );
        }
    }

    /**
     * Attendance marked notification
     */
    public static function attendanceMarked(int $studentId, string $status, string $date): void
    {
        $student = \App\Models\Student::find($studentId);
        if (!$student) return;

        if ($status === 'absent') {
            // Notify parent about absence
            if ($student->parent_id && $student->parent->user_id) {
                self::sendToUser(
                    $student->parent->user_id,
                    'Child Absent',
                    "{$student->full_name} was marked absent on {$date}.",
                    'warning',
                    ['action_url' => route('parent.attendance')]
                );
            }
        }
    }

    /**
     * System maintenance notification
     */
    public static function systemMaintenance(string $message, \DateTime $scheduledTime): void
    {
        $allUsers = User::where('is_active', true)->get();
        
        foreach ($allUsers as $user) {
            self::sendToUser(
                $user->id,
                'System Maintenance Scheduled',
                $message . " Scheduled for: " . $scheduledTime->format('Y-m-d H:i:s'),
                'warning'
            );
        }
    }

    /**
     * School announcement
     */
    public static function schoolAnnouncement(int $schoolId, string $title, string $message): void
    {
        self::sendToSchool($schoolId, $title, $message, 'info');
    }

    /**
     * Clean old notifications (run via scheduled job)
     */
    public static function cleanOldNotifications(int $days = 90): int
    {
        return SystemNotification::cleanOldNotifications($days);
    }

    /**
     * Get notification statistics
     */
    public static function getStatistics(?int $schoolId = null): array
    {
        $query = SystemNotification::query();
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        
        return [
            'total' => $query->count(),
            'unread' => $query->clone()->unread()->count(),
            'by_type' => [
                'info' => $query->clone()->byType('info')->count(),
                'success' => $query->clone()->byType('success')->count(),
                'warning' => $query->clone()->byType('warning')->count(),
                'error' => $query->clone()->byType('error')->count(),
            ],
            'recent' => $query->clone()->recent(7)->count(),
        ];
    }
}