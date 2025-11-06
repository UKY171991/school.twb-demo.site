<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Assignment;
use App\Models\Announcement;
use App\Models\Message;
use App\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $parent = ParentModel::where('user_id', $user->id)->first();
        
        if (!$parent) {
            return redirect()->route('login')->with('error', 'Parent profile not found.');
        }

        $parent->load(['children.user', 'children.classModel', 'children.school']);

        // Get comprehensive dashboard data
        $dashboardData = [
            'children_overview' => $this->getChildrenOverview($parent),
            'family_performance' => $parent->getFamilyAcademicPerformance(),
            'recent_activities' => $parent->getRecentChildrenActivities(14),
            'upcoming_events' => $this->getUpcomingEvents($parent),
            'communication_summary' => $this->getCommunicationSummary($parent),
            'alerts_and_notifications' => $this->getAlertsAndNotifications($parent),
        ];

        // Get quick statistics
        $statistics = [
            'total_children' => $parent->children->count(),
            'children_present_today' => $this->getChildrenPresentToday($parent),
            'unread_messages' => $this->getUnreadMessagesCount($parent),
            'urgent_alerts' => $this->getUrgentAlertsCount($parent),
        ];

        return view('parent.dashboard', compact('parent', 'dashboardData', 'statistics'));
    }

    /**
     * Get children overview with detailed academic information
     */
    private function getChildrenOverview(ParentModel $parent): array
    {
        return $parent->children->map(function($child) {
            $attendanceStats = $child->getAttendanceStatistics();
            $gradeStats = $child->getGradeStatistics();
            $academicStatus = $child->getAcademicStatus();
            
            // Get recent grades (last 5)
            $recentGrades = $child->grades()
                                 ->with('subject')
                                 ->orderBy('created_at', 'desc')
                                 ->limit(5)
                                 ->get()
                                 ->map(function($grade) {
                                     return [
                                         'subject' => $grade->subject->name ?? 'Unknown',
                                         'percentage' => $grade->calculated_percentage,
                                         'date' => $grade->created_at->format('M j'),
                                     ];
                                 });

            // Get today's attendance
            $todayAttendance = $child->attendance()
                                    ->whereDate('date', today())
                                    ->first();

            // Get upcoming assignments
            $upcomingAssignments = Assignment::where('class_id', $child->class_id)
                                           ->where('status', 'published')
                                           ->where('due_date', '>=', today())
                                           ->with('subject')
                                           ->orderBy('due_date')
                                           ->limit(3)
                                           ->get()
                                           ->map(function($assignment) {
                                               return [
                                                   'title' => $assignment->title,
                                                   'subject' => $assignment->subject->name ?? 'Unknown',
                                                   'due_date' => $assignment->due_date->format('M j'),
                                                   'days_until_due' => $assignment->days_until_due,
                                                   'priority' => $assignment->getPriority(),
                                               ];
                                           });

            return [
                'id' => $child->id,
                'student_id' => $child->student_id,
                'name' => $child->full_name,
                'photo_url' => $child->photo_url,
                'class' => $child->classModel?->full_name ?? 'Not Assigned',
                'school' => $child->school?->name ?? 'Unknown School',
                'status' => $child->status,
                'attendance_stats' => $attendanceStats,
                'grade_stats' => $gradeStats,
                'academic_status' => $academicStatus,
                'today_attendance' => $todayAttendance ? $todayAttendance->status : 'unknown',
                'recent_grades' => $recentGrades,
                'upcoming_assignments' => $upcomingAssignments,
                'needs_attention' => $academicStatus['needs_attention'],
            ];
        })->toArray();
    }

    /**
     * Get upcoming events for all children
     */
    private function getUpcomingEvents(ParentModel $parent): array
    {
        $events = [];
        
        foreach ($parent->children as $child) {
            // Upcoming assignments
            $assignments = Assignment::where('class_id', $child->class_id)
                                   ->where('status', 'published')
                                   ->whereBetween('due_date', [today(), today()->addDays(14)])
                                   ->with('subject')
                                   ->get();

            foreach ($assignments as $assignment) {
                $events[] = [
                    'type' => 'assignment',
                    'child_name' => $child->full_name,
                    'title' => $assignment->title,
                    'subject' => $assignment->subject->name ?? 'Unknown',
                    'date' => $assignment->due_date,
                    'priority' => $assignment->getPriority(),
                    'days_until' => $assignment->days_until_due,
                ];
            }

            // Upcoming exams (from grades with future exam dates)
            $upcomingExams = Grade::where('student_id', $child->id)
                                 ->whereIn('exam_type', ['exam', 'midterm', 'final'])
                                 ->whereBetween('exam_date', [today(), today()->addDays(30)])
                                 ->with('subject')
                                 ->get();

            foreach ($upcomingExams as $exam) {
                $events[] = [
                    'type' => 'exam',
                    'child_name' => $child->full_name,
                    'title' => ucfirst($exam->exam_type) . ' - ' . ($exam->subject->name ?? 'Unknown'),
                    'subject' => $exam->subject->name ?? 'Unknown',
                    'date' => $exam->exam_date,
                    'priority' => 'high',
                    'days_until' => Carbon::parse($exam->exam_date)->diffInDays(today()),
                ];
            }
        }

        // Get school announcements
        $announcements = Announcement::where('school_id', $parent->school_id)
                                   ->where('is_published', true)
                                   ->where('target_audience', 'parents')
                                   ->whereBetween('published_at', [today(), today()->addDays(7)])
                                   ->orderBy('published_at')
                                   ->get();

        foreach ($announcements as $announcement) {
            $events[] = [
                'type' => 'announcement',
                'child_name' => 'All Children',
                'title' => $announcement->title,
                'subject' => 'School Communication',
                'date' => $announcement->published_at,
                'priority' => $announcement->priority,
                'days_until' => $announcement->published_at->diffInDays(today()),
            ];
        }

        // Sort by date and return next 10 events
        usort($events, function($a, $b) {
            return $a['date'] <=> $b['date'];
        });

        return array_slice($events, 0, 10);
    }

    /**
     * Get communication summary
     */
    private function getCommunicationSummary(ParentModel $parent): array
    {
        $unreadMessages = Message::where('receiver_id', $parent->user_id)
                                ->whereNull('read_at')
                                ->count();

        $recentMessages = Message::where(function($query) use ($parent) {
                                    $query->where('sender_id', $parent->user_id)
                                          ->orWhere('receiver_id', $parent->user_id);
                                })
                                ->with(['sender', 'receiver'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        $unreadAnnouncements = Announcement::where('school_id', $parent->school_id)
                                         ->where('is_published', true)
                                         ->where('target_audience', 'parents')
                                         ->whereDoesntHave('reads', function($query) use ($parent) {
                                             $query->where('user_id', $parent->user_id);
                                         })
                                         ->count();

        return [
            'unread_messages' => $unreadMessages,
            'recent_messages' => $recentMessages,
            'unread_announcements' => $unreadAnnouncements,
            'total_communications' => $unreadMessages + $unreadAnnouncements,
        ];
    }

    /**
     * Get alerts and notifications
     */
    private function getAlertsAndNotifications(ParentModel $parent): array
    {
        $alerts = [];

        foreach ($parent->children as $child) {
            $academicStatus = $child->getAcademicStatus();
            $attendanceStats = $child->getAttendanceStatistics();
            $gradeStats = $child->getGradeStatistics();

            // Attendance alerts
            if ($attendanceStats['attendance_percentage'] < 75) {
                $alerts[] = [
                    'type' => 'attendance',
                    'severity' => 'high',
                    'child_name' => $child->full_name,
                    'message' => "Low attendance: {$attendanceStats['attendance_percentage']}%",
                    'action_needed' => true,
                ];
            }

            // Grade alerts
            if ($gradeStats['average_grade'] < 60 && $gradeStats['total_grades'] > 0) {
                $alerts[] = [
                    'type' => 'grades',
                    'severity' => 'high',
                    'child_name' => $child->full_name,
                    'message' => "Low average grade: {$gradeStats['average_grade']}%",
                    'action_needed' => true,
                ];
            }

            // Check for recent absences
            $recentAbsences = $child->attendance()
                                   ->where('status', 'absent')
                                   ->where('date', '>=', Carbon::now()->subDays(7))
                                   ->count();

            if ($recentAbsences >= 3) {
                $alerts[] = [
                    'type' => 'attendance',
                    'severity' => 'medium',
                    'child_name' => $child->full_name,
                    'message' => "Multiple recent absences: {$recentAbsences} days this week",
                    'action_needed' => true,
                ];
            }

            // Check for overdue assignments
            $overdueAssignments = Assignment::where('class_id', $child->class_id)
                                          ->where('status', 'published')
                                          ->where('due_date', '<', today())
                                          ->count();

            if ($overdueAssignments > 0) {
                $alerts[] = [
                    'type' => 'assignments',
                    'severity' => 'medium',
                    'child_name' => $child->full_name,
                    'message' => "{$overdueAssignments} overdue assignment(s)",
                    'action_needed' => false,
                ];
            }
        }

        // Sort by severity
        usort($alerts, function($a, $b) {
            $severityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            return ($severityOrder[$b['severity']] ?? 0) <=> ($severityOrder[$a['severity']] ?? 0);
        });

        return array_slice($alerts, 0, 10);
    }

    /**
     * Get count of children present today
     */
    private function getChildrenPresentToday(ParentModel $parent): int
    {
        $presentCount = 0;
        
        foreach ($parent->children as $child) {
            $todayAttendance = $child->attendance()
                                    ->whereDate('date', today())
                                    ->first();
            
            if ($todayAttendance && $todayAttendance->status === 'present') {
                $presentCount++;
            }
        }

        return $presentCount;
    }

    /**
     * Get unread messages count
     */
    private function getUnreadMessagesCount(ParentModel $parent): int
    {
        return Message::where('receiver_id', $parent->user_id)
                     ->whereNull('read_at')
                     ->count();
    }

    /**
     * Get urgent alerts count
     */
    private function getUrgentAlertsCount(ParentModel $parent): int
    {
        $urgentCount = 0;

        foreach ($parent->children as $child) {
            $attendanceStats = $child->getAttendanceStatistics();
            $gradeStats = $child->getGradeStatistics();

            // Count urgent attendance issues
            if ($attendanceStats['attendance_percentage'] < 70) {
                $urgentCount++;
            }

            // Count urgent grade issues
            if ($gradeStats['average_grade'] < 50 && $gradeStats['total_grades'] > 0) {
                $urgentCount++;
            }

            // Count recent consecutive absences
            $recentAbsences = $child->attendance()
                                   ->where('status', 'absent')
                                   ->where('date', '>=', Carbon::now()->subDays(3))
                                   ->count();

            if ($recentAbsences >= 3) {
                $urgentCount++;
            }
        }

        return $urgentCount;
    }
}
