<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Notification;
use App\Models\Subject;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get the student record
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        // Get dashboard data
        $dashboardData = $this->getDashboardData($student);

        return view('student.dashboard', compact('student', 'dashboardData'));
    }

    /**
     * Get comprehensive dashboard data for the student
     */
    private function getDashboardData($student)
    {
        return [
            'academic_overview' => $this->getAcademicOverview($student),
            'current_grades' => $this->getCurrentGrades($student),
            'attendance_summary' => $this->getAttendanceSummary($student),
            'class_schedule' => $this->getClassSchedule($student),
            'upcoming_events' => $this->getUpcomingEvents($student),
            'recent_notifications' => $this->getRecentNotifications($student),
            'performance_metrics' => $this->getPerformanceMetrics($student),
            'quick_stats' => $this->getQuickStats($student)
        ];
    }

    /**
     * Get academic overview including current semester info
     */
    private function getAcademicOverview($student)
    {
        $class = ClassModel::find($student->class_id);
        
        return [
            'class_name' => $class ? $class->full_name : 'Not Assigned',
            'school_name' => $student->school->name ?? 'Unknown School',
            'student_id' => $student->student_id,
            'enrollment_date' => $student->created_at,
            'academic_year' => now()->year . '-' . (now()->year + 1),
            'current_semester' => $this->getCurrentSemester(),
            'class_teacher' => $class && $class->teacher ? $class->teacher->full_name : 'Not Assigned'
        ];
    }

    /**
     * Get current grades for all subjects
     */
    private function getCurrentGrades($student)
    {
        $grades = Grade::where('student_id', $student->id)
            ->with(['subject'])
            ->whereBetween('exam_date', [now()->subMonth(3), now()])
            ->orderBy('exam_date', 'desc')
            ->get()
            ->groupBy('subject_id');

        $gradesSummary = [];
        
        foreach ($grades as $subjectId => $subjectGrades) {
            $subject = $subjectGrades->first()->subject;
            $latestGrade = $subjectGrades->first();
            $averageGrade = $subjectGrades->avg('percentage');
            
            $gradesSummary[] = [
                'subject_name' => $subject ? $subject->name : 'Unknown Subject',
                'subject_code' => $subject ? $subject->code : 'N/A',
                'latest_grade' => $latestGrade->percentage,
                'latest_exam_date' => $latestGrade->exam_date,
                'average_grade' => round($averageGrade, 1),
                'total_exams' => $subjectGrades->count(),
                'grade_trend' => $this->calculateGradeTrend($subjectGrades),
                'letter_grade' => $this->getLetterGrade($averageGrade)
            ];
        }

        return collect($gradesSummary)->sortBy('subject_name')->values()->all();
    }

    /**
     * Get attendance summary for current month and semester
     */
    private function getAttendanceSummary($student)
    {
        $currentMonth = now()->startOfMonth();
        $currentSemester = now()->startOfYear(); // Simplified - could be more complex

        // Current month attendance
        $monthlyAttendance = Attendance::where('student_id', $student->id)
            ->where('date', '>=', $currentMonth)
            ->get();

        $monthlyPresent = $monthlyAttendance->where('status', 'present')->count();
        $monthlyTotal = $monthlyAttendance->count();
        $monthlyPercentage = $monthlyTotal > 0 ? ($monthlyPresent / $monthlyTotal) * 100 : 0;

        // Semester attendance
        $semesterAttendance = Attendance::where('student_id', $student->id)
            ->where('date', '>=', $currentSemester)
            ->get();

        $semesterPresent = $semesterAttendance->where('status', 'present')->count();
        $semesterTotal = $semesterAttendance->count();
        $semesterPercentage = $semesterTotal > 0 ? ($semesterPresent / $semesterTotal) * 100 : 0;

        // Recent attendance (last 10 days)
        $recentAttendance = Attendance::where('student_id', $student->id)
            ->where('date', '>=', now()->subDays(10))
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return [
            'monthly' => [
                'present' => $monthlyPresent,
                'total' => $monthlyTotal,
                'percentage' => round($monthlyPercentage, 1),
                'absent' => $monthlyTotal - $monthlyPresent
            ],
            'semester' => [
                'present' => $semesterPresent,
                'total' => $semesterTotal,
                'percentage' => round($semesterPercentage, 1),
                'absent' => $semesterTotal - $semesterPresent
            ],
            'recent_days' => $recentAttendance->map(function($attendance) {
                return [
                    'date' => $attendance->date,
                    'status' => $attendance->status,
                    'day_name' => Carbon::parse($attendance->date)->format('l')
                ];
            })->toArray(),
            'attendance_status' => $this->getAttendanceStatus($semesterPercentage)
        ];
    }

    /**
     * Get class schedule for current week
     */
    private function getClassSchedule($student)
    {
        $class = ClassModel::find($student->class_id);
        
        if (!$class) {
            return [];
        }

        // Get subjects for the class
        $subjects = $class->subjects()->get();
        
        // Generate a sample weekly schedule (in a real app, this would come from a schedule table)
        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $timeSlots = ['08:00-09:00', '09:00-10:00', '10:30-11:30', '11:30-12:30', '13:30-14:30'];
        
        $schedule = [];
        
        foreach ($weekDays as $day) {
            $daySchedule = [];
            foreach ($timeSlots as $index => $timeSlot) {
                if ($subjects->count() > 0) {
                    $subject = $subjects->get($index % $subjects->count());
                    $daySchedule[] = [
                        'time' => $timeSlot,
                        'subject' => $subject->name,
                        'subject_code' => $subject->code,
                        'teacher' => $class->teacher ? $class->teacher->full_name : 'TBA',
                        'room' => $class->room_number ?? 'TBA'
                    ];
                }
            }
            $schedule[$day] = $daySchedule;
        }

        return [
            'weekly_schedule' => $schedule,
            'today_schedule' => $schedule[now()->format('l')] ?? [],
            'next_class' => $this->getNextClass($schedule),
            'total_periods_today' => count($schedule[now()->format('l')] ?? [])
        ];
    }

    /**
     * Get upcoming events and important dates
     */
    private function getUpcomingEvents($student)
    {
        // In a real application, this would come from an events/calendar table
        // For now, we'll generate some sample upcoming events
        
        $events = [
            [
                'title' => 'Mathematics Quiz',
                'date' => now()->addDays(2),
                'type' => 'exam',
                'description' => 'Chapter 5: Algebra',
                'priority' => 'high'
            ],
            [
                'title' => 'Science Project Submission',
                'date' => now()->addDays(5),
                'type' => 'assignment',
                'description' => 'Solar System Model',
                'priority' => 'medium'
            ],
            [
                'title' => 'Parent-Teacher Meeting',
                'date' => now()->addDays(7),
                'type' => 'meeting',
                'description' => 'Quarterly Progress Review',
                'priority' => 'medium'
            ],
            [
                'title' => 'Sports Day',
                'date' => now()->addDays(10),
                'type' => 'event',
                'description' => 'Annual Sports Competition',
                'priority' => 'low'
            ]
        ];

        return collect($events)->sortBy('date')->take(5)->values()->all();
    }

    /**
     * Get recent notifications for the student
     */
    private function getRecentNotifications($student)
    {
        return Notification::where('user_id', $student->user_id)
            ->where('school_id', $student->school_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at,
                    'time_ago' => $notification->created_at->diffForHumans()
                ];
            })->toArray();
    }

    /**
     * Get performance metrics and analytics
     */
    private function getPerformanceMetrics($student)
    {
        $grades = Grade::where('student_id', $student->id)
            ->whereBetween('exam_date', [now()->subMonth(3), now()])
            ->get();

        if ($grades->isEmpty()) {
            return [
                'overall_average' => 0,
                'grade_trend' => 'stable',
                'strongest_subject' => 'N/A',
                'needs_improvement' => 'N/A',
                'performance_status' => 'insufficient_data'
            ];
        }

        $overallAverage = $grades->avg('percentage');
        $subjectAverages = $grades->groupBy('subject_id')->map(function($subjectGrades) {
            return [
                'average' => $subjectGrades->avg('percentage'),
                'subject' => $subjectGrades->first()->subject->name ?? 'Unknown'
            ];
        });

        $strongestSubject = $subjectAverages->sortByDesc('average')->first();
        $weakestSubject = $subjectAverages->sortBy('average')->first();

        return [
            'overall_average' => round($overallAverage, 1),
            'grade_trend' => $this->calculateOverallTrend($grades),
            'strongest_subject' => $strongestSubject['subject'] ?? 'N/A',
            'needs_improvement' => $weakestSubject['subject'] ?? 'N/A',
            'performance_status' => $this->getPerformanceStatus($overallAverage),
            'total_exams' => $grades->count()
        ];
    }

    /**
     * Get quick statistics for dashboard cards
     */
    private function getQuickStats($student)
    {
        $class = ClassModel::find($student->class_id);
        $totalSubjects = $class ? $class->subjects()->count() : 0;
        
        $recentGrades = Grade::where('student_id', $student->id)
            ->where('exam_date', '>=', now()->subWeek())
            ->count();

        $monthlyAttendance = Attendance::where('student_id', $student->id)
            ->where('date', '>=', now()->startOfMonth())
            ->where('status', 'present')
            ->count();

        $unreadNotifications = Notification::where('user_id', $student->user_id)
            ->where('is_read', false)
            ->count();

        return [
            'total_subjects' => $totalSubjects,
            'recent_grades' => $recentGrades,
            'monthly_attendance' => $monthlyAttendance,
            'unread_notifications' => $unreadNotifications
        ];
    }

    /**
     * Helper methods
     */
    private function getCurrentSemester()
    {
        $month = now()->month;
        return $month >= 1 && $month <= 6 ? 'Spring Semester' : 'Fall Semester';
    }

    private function calculateGradeTrend($grades)
    {
        if ($grades->count() < 2) return 'stable';
        
        $recent = $grades->take(3)->avg('percentage');
        $older = $grades->skip(3)->avg('percentage');
        
        if ($recent > $older + 5) return 'improving';
        if ($recent < $older - 5) return 'declining';
        return 'stable';
    }

    private function calculateOverallTrend($grades)
    {
        if ($grades->count() < 4) return 'stable';
        
        $recentAvg = $grades->sortByDesc('exam_date')->take(2)->avg('percentage');
        $olderAvg = $grades->sortByDesc('exam_date')->skip(2)->take(2)->avg('percentage');
        
        if ($recentAvg > $olderAvg + 3) return 'improving';
        if ($recentAvg < $olderAvg - 3) return 'declining';
        return 'stable';
    }

    private function getLetterGrade($percentage)
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    private function getAttendanceStatus($percentage)
    {
        if ($percentage >= 90) return 'excellent';
        if ($percentage >= 80) return 'good';
        if ($percentage >= 70) return 'satisfactory';
        return 'needs_improvement';
    }

    private function getPerformanceStatus($average)
    {
        if ($average >= 85) return 'excellent';
        if ($average >= 75) return 'good';
        if ($average >= 65) return 'satisfactory';
        return 'needs_improvement';
    }

    private function getNextClass($schedule)
    {
        $today = now()->format('l');
        $currentTime = now()->format('H:i');
        
        if (!isset($schedule[$today])) return null;
        
        foreach ($schedule[$today] as $class) {
            $classTime = explode('-', $class['time'])[0];
            if ($classTime > $currentTime) {
                return $class;
            }
        }
        
        return null;
    }
}