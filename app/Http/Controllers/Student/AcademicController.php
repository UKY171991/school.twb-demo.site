<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\Assignment;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AcademicController extends Controller
{
    /**
     * Display academic progress overview
     */
    public function index()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $academicData = [
            'grade_history' => $this->getGradeHistory($student),
            'attendance_summary' => $this->getAttendanceSummary($student),
            'performance_analytics' => $this->getPerformanceAnalytics($student),
            'improvement_suggestions' => $this->getImprovementSuggestions($student),
            'academic_timeline' => $this->getAcademicTimeline($student)
        ];

        return view('student.academic.index', compact('student', 'academicData'));
    }

    /**
     * Display detailed grade history
     */
    public function grades(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        // Filter parameters
        $subjectId = $request->get('subject_id');
        $dateFrom = $request->get('date_from', now()->subMonths(6)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $gradesQuery = Grade::where('student_id', $student->id)
            ->with(['subject'])
            ->whereBetween('exam_date', [$dateFrom, $dateTo])
            ->orderBy('exam_date', 'desc');

        if ($subjectId) {
            $gradesQuery->where('subject_id', $subjectId);
        }

        $grades = $gradesQuery->paginate(20);
        
        // Get subjects for filter dropdown
        $subjects = Subject::whereIn('id', function($query) use ($student) {
            $query->select('subject_id')
                  ->from('grades')
                  ->where('student_id', $student->id)
                  ->distinct();
        })->get();

        $gradeAnalytics = [
            'subject_performance' => $this->getSubjectPerformance($student, $dateFrom, $dateTo),
            'grade_trends' => $this->getGradeTrends($student, $dateFrom, $dateTo),
            'performance_comparison' => $this->getPerformanceComparison($student, $dateFrom, $dateTo)
        ];

        return view('student.academic.grades', compact('student', 'grades', 'subjects', 'gradeAnalytics'));
    }

    /**
     * Display detailed attendance tracking
     */
    public function attendance(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        // Filter parameters
        $month = $request->get('month', now()->format('Y-m'));
        $year = $request->get('year', now()->year);

        $attendanceData = [
            'monthly_attendance' => $this->getMonthlyAttendance($student, $month),
            'yearly_summary' => $this->getYearlyAttendanceSummary($student, $year),
            'attendance_patterns' => $this->getAttendancePatterns($student),
            'attendance_calendar' => $this->getAttendanceCalendar($student, $month)
        ];

        return view('student.academic.attendance', compact('student', 'attendanceData'));
    }

    /**
     * Display progress reports
     */
    public function progressReports(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $reportType = $request->get('type', 'semester');
        $period = $request->get('period', now()->format('Y-m'));

        $progressData = [
            'overall_progress' => $this->getOverallProgress($student, $reportType, $period),
            'subject_progress' => $this->getSubjectProgress($student, $reportType, $period),
            'attendance_progress' => $this->getAttendanceProgress($student, $reportType, $period),
            'recommendations' => $this->getProgressRecommendations($student, $reportType, $period)
        ];

        return view('student.academic.progress-reports', compact('student', 'progressData', 'reportType', 'period'));
    }

    /**
     * Display class schedule and assignments
     */
    public function schedule(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $view = $request->get('view', 'weekly'); // daily, weekly, monthly
        $date = $request->get('date', now()->format('Y-m-d'));
        $currentDate = Carbon::parse($date);

        $scheduleData = [
            'class_schedule' => $this->getClassSchedule($student, $view, $currentDate),
            'upcoming_assignments' => $this->getUpcomingAssignments($student),
            'exam_schedule' => $this->getExamSchedule($student, $currentDate),
            'academic_calendar' => $this->getAcademicCalendar($student, $currentDate),
            'schedule_conflicts' => $this->getScheduleConflicts($student, $currentDate)
        ];

        return view('student.academic.schedule', compact('student', 'scheduleData', 'view', 'currentDate'));
    }

    /**
     * Display assignments and homework
     */
    public function assignments(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $status = $request->get('status', 'all'); // all, pending, overdue, completed
        $subject = $request->get('subject_id');
        $type = $request->get('type'); // homework, project, quiz, exam

        $assignmentsQuery = Assignment::where('class_id', $student->class_id)
            ->where('status', 'published')
            ->with(['subject', 'teacher']);

        // Apply filters
        if ($status !== 'all') {
            switch ($status) {
                case 'pending':
                    $assignmentsQuery->where('due_date', '>=', today());
                    break;
                case 'overdue':
                    $assignmentsQuery->overdue();
                    break;
                case 'completed':
                    // This would need assignment submissions to be properly implemented
                    break;
            }
        }

        if ($subject) {
            $assignmentsQuery->where('subject_id', $subject);
        }

        if ($type) {
            $assignmentsQuery->where('type', $type);
        }

        $assignments = $assignmentsQuery->orderBy('due_date', 'asc')->paginate(20);

        // Get subjects for filter dropdown
        $subjects = Subject::whereIn('id', function($query) use ($student) {
            $query->select('subject_id')
                  ->from('assignments')
                  ->where('class_id', $student->class_id)
                  ->where('status', 'published')
                  ->distinct();
        })->get();

        $assignmentStats = [
            'total_assignments' => Assignment::where('class_id', $student->class_id)->where('status', 'published')->count(),
            'pending_assignments' => Assignment::where('class_id', $student->class_id)->where('status', 'published')->where('due_date', '>=', today())->count(),
            'overdue_assignments' => Assignment::where('class_id', $student->class_id)->overdue()->count(),
            'due_this_week' => Assignment::where('class_id', $student->class_id)->dueSoon(7)->count()
        ];

        return view('student.academic.assignments', compact('student', 'assignments', 'subjects', 'assignmentStats', 'status', 'subject', 'type'));
    }

    /**
     * Get comprehensive grade history
     */
    private function getGradeHistory($student)
    {
        $grades = Grade::where('student_id', $student->id)
            ->with(['subject'])
            ->orderBy('exam_date', 'desc')
            ->limit(50)
            ->get();

        $gradesBySubject = $grades->groupBy('subject_id');
        $subjectHistory = [];

        foreach ($gradesBySubject as $subjectId => $subjectGrades) {
            $subject = $subjectGrades->first()->subject;
            $subjectHistory[] = [
                'subject_name' => $subject ? $subject->name : 'Unknown Subject',
                'subject_code' => $subject ? $subject->code : 'N/A',
                'total_exams' => $subjectGrades->count(),
                'average_grade' => round($subjectGrades->avg('percentage'), 1),
                'highest_grade' => $subjectGrades->max('percentage'),
                'lowest_grade' => $subjectGrades->min('percentage'),
                'latest_grade' => $subjectGrades->first()->percentage,
                'trend' => $this->calculateGradeTrend($subjectGrades),
                'recent_grades' => $subjectGrades->take(5)->map(function($grade) {
                    return [
                        'percentage' => $grade->percentage,
                        'exam_date' => $grade->exam_date,
                        'exam_type' => $grade->exam_type,
                        'marks_obtained' => $grade->marks_obtained,
                        'total_marks' => $grade->total_marks
                    ];
                })->toArray()
            ];
        }

        return collect($subjectHistory)->sortBy('subject_name')->values()->all();
    }

    /**
     * Get attendance summary with different time periods
     */
    private function getAttendanceSummary($student)
    {
        $currentMonth = now()->startOfMonth();
        $currentSemester = now()->startOfYear();
        $currentYear = now()->startOfYear();

        // Monthly attendance
        $monthlyAttendance = Attendance::where('student_id', $student->id)
            ->where('date', '>=', $currentMonth)
            ->get();

        // Semester attendance
        $semesterAttendance = Attendance::where('student_id', $student->id)
            ->where('date', '>=', $currentSemester)
            ->get();

        // Yearly attendance
        $yearlyAttendance = Attendance::where('student_id', $student->id)
            ->where('date', '>=', $currentYear)
            ->get();

        return [
            'monthly' => $this->calculateAttendanceStats($monthlyAttendance),
            'semester' => $this->calculateAttendanceStats($semesterAttendance),
            'yearly' => $this->calculateAttendanceStats($yearlyAttendance),
            'weekly_pattern' => $this->getWeeklyAttendancePattern($student),
            'monthly_trend' => $this->getMonthlyAttendanceTrend($student)
        ];
    }

    /**
     * Get performance analytics and insights
     */
    private function getPerformanceAnalytics($student)
    {
        $grades = Grade::where('student_id', $student->id)
            ->whereBetween('exam_date', [now()->subMonths(6), now()])
            ->get();

        if ($grades->isEmpty()) {
            return [
                'overall_performance' => 'insufficient_data',
                'performance_trend' => 'stable',
                'strengths' => [],
                'areas_for_improvement' => [],
                'grade_distribution' => []
            ];
        }

        $overallAverage = $grades->avg('percentage');
        $subjectAverages = $grades->groupBy('subject_id')->map(function($subjectGrades) {
            return [
                'average' => $subjectGrades->avg('percentage'),
                'subject' => $subjectGrades->first()->subject->name ?? 'Unknown',
                'count' => $subjectGrades->count()
            ];
        });

        $strengths = $subjectAverages->where('average', '>=', 80)->sortByDesc('average')->take(3);
        $improvements = $subjectAverages->where('average', '<', 70)->sortBy('average')->take(3);

        return [
            'overall_performance' => $this->getPerformanceLevel($overallAverage),
            'performance_trend' => $this->calculateOverallTrend($grades),
            'overall_average' => round($overallAverage, 1),
            'strengths' => $strengths->values()->all(),
            'areas_for_improvement' => $improvements->values()->all(),
            'grade_distribution' => $this->calculateGradeDistribution($grades),
            'consistency_score' => $this->calculateConsistencyScore($grades)
        ];
    }

    /**
     * Get personalized improvement suggestions
     */
    private function getImprovementSuggestions($student)
    {
        $suggestions = [];
        
        // Analyze grades
        $recentGrades = Grade::where('student_id', $student->id)
            ->where('exam_date', '>=', now()->subMonth())
            ->with('subject')
            ->get();

        $subjectPerformance = $recentGrades->groupBy('subject_id')->map(function($grades) {
            return [
                'subject' => $grades->first()->subject->name ?? 'Unknown',
                'average' => $grades->avg('percentage'),
                'count' => $grades->count()
            ];
        });

        foreach ($subjectPerformance as $performance) {
            if ($performance['average'] < 60) {
                $suggestions[] = [
                    'type' => 'academic',
                    'priority' => 'high',
                    'subject' => $performance['subject'],
                    'message' => "Focus on improving {$performance['subject']} - current average is {$performance['average']}%. Consider extra study time or seeking help from your teacher.",
                    'action' => 'study_plan'
                ];
            } elseif ($performance['average'] < 75) {
                $suggestions[] = [
                    'type' => 'academic',
                    'priority' => 'medium',
                    'subject' => $performance['subject'],
                    'message' => "Good progress in {$performance['subject']}! With some additional effort, you can reach excellence.",
                    'action' => 'practice_more'
                ];
            }
        }

        // Analyze attendance
        $attendancePercentage = $this->getAttendancePercentage($student, now()->subMonth(), now());
        if ($attendancePercentage < 80) {
            $suggestions[] = [
                'type' => 'attendance',
                'priority' => 'high',
                'subject' => 'General',
                'message' => "Your attendance is {$attendancePercentage}%. Regular attendance is crucial for academic success.",
                'action' => 'improve_attendance'
            ];
        }

        return $suggestions;
    }

    /**
     * Get academic timeline with key events
     */
    private function getAcademicTimeline($student)
    {
        $timeline = [];

        // Recent grades
        $recentGrades = Grade::where('student_id', $student->id)
            ->with('subject')
            ->orderBy('exam_date', 'desc')
            ->limit(10)
            ->get();

        foreach ($recentGrades as $grade) {
            $timeline[] = [
                'date' => $grade->exam_date,
                'type' => 'grade',
                'title' => $grade->subject->name ?? 'Unknown Subject',
                'description' => "Scored {$grade->percentage}% ({$grade->marks_obtained}/{$grade->total_marks})",
                'icon' => 'fas fa-star',
                'color' => $grade->percentage >= 80 ? 'success' : ($grade->percentage >= 60 ? 'warning' : 'danger')
            ];
        }

        // Recent attendance events
        $recentAbsences = Attendance::where('student_id', $student->id)
            ->where('status', 'absent')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentAbsences as $absence) {
            $timeline[] = [
                'date' => $absence->date,
                'type' => 'attendance',
                'title' => 'Absent',
                'description' => $absence->reason ?? 'No reason provided',
                'icon' => 'fas fa-times-circle',
                'color' => 'danger'
            ];
        }

        return collect($timeline)->sortByDesc('date')->take(15)->values()->all();
    }

    /**
     * Helper methods for detailed views
     */
    private function getSubjectPerformance($student, $dateFrom, $dateTo)
    {
        return Grade::where('student_id', $student->id)
            ->whereBetween('exam_date', [$dateFrom, $dateTo])
            ->with('subject')
            ->get()
            ->groupBy('subject_id')
            ->map(function($grades) {
                $subject = $grades->first()->subject;
                return [
                    'subject_name' => $subject ? $subject->name : 'Unknown',
                    'subject_code' => $subject ? $subject->code : 'N/A',
                    'average' => round($grades->avg('percentage'), 1),
                    'highest' => $grades->max('percentage'),
                    'lowest' => $grades->min('percentage'),
                    'count' => $grades->count(),
                    'trend' => $this->calculateGradeTrend($grades)
                ];
            })
            ->values()
            ->all();
    }

    private function getGradeTrends($student, $dateFrom, $dateTo)
    {
        $grades = Grade::where('student_id', $student->id)
            ->whereBetween('exam_date', [$dateFrom, $dateTo])
            ->orderBy('exam_date')
            ->get();

        $monthlyAverages = $grades->groupBy(function($grade) {
            return Carbon::parse($grade->exam_date)->format('Y-m');
        })->map(function($monthGrades) {
            return round($monthGrades->avg('percentage'), 1);
        });

        return $monthlyAverages->toArray();
    }

    private function getMonthlyAttendance($student, $month)
    {
        $startDate = Carbon::parse($month . '-01');
        $endDate = $startDate->copy()->endOfMonth();

        return Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get()
            ->groupBy(function($attendance) {
                return Carbon::parse($attendance->date)->format('Y-m-d');
            })
            ->map(function($dayAttendance) {
                return $dayAttendance->first();
            })
            ->values()
            ->all();
    }

    private function getAttendanceCalendar($student, $month)
    {
        $startDate = Carbon::parse($month . '-01');
        $endDate = $startDate->copy()->endOfMonth();
        
        $attendance = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy('date');

        $calendar = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $calendar[$dateStr] = [
                'date' => $dateStr,
                'day' => $current->format('j'),
                'day_name' => $current->format('l'),
                'status' => isset($attendance[$dateStr]) ? $attendance[$dateStr]->status : 'no_data',
                'is_weekend' => $current->isWeekend()
            ];
            $current->addDay();
        }

        return $calendar;
    }

    /**
     * Utility methods
     */
    private function calculateAttendanceStats($attendanceCollection)
    {
        $total = $attendanceCollection->count();
        $present = $attendanceCollection->where('status', 'present')->count();
        $absent = $attendanceCollection->where('status', 'absent')->count();
        $late = $attendanceCollection->where('status', 'late')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0
        ];
    }

    private function calculateGradeTrend($grades)
    {
        if ($grades->count() < 2) return 'stable';
        
        $recent = $grades->take(3)->avg('percentage');
        $older = $grades->skip(3)->take(3)->avg('percentage');
        
        if ($recent > $older + 5) return 'improving';
        if ($recent < $older - 5) return 'declining';
        return 'stable';
    }

    private function calculateOverallTrend($grades)
    {
        if ($grades->count() < 4) return 'stable';
        
        $recentAvg = $grades->sortByDesc('exam_date')->take(5)->avg('percentage');
        $olderAvg = $grades->sortByDesc('exam_date')->skip(5)->take(5)->avg('percentage');
        
        if ($recentAvg > $olderAvg + 3) return 'improving';
        if ($recentAvg < $olderAvg - 3) return 'declining';
        return 'stable';
    }

    private function getPerformanceLevel($average)
    {
        if ($average >= 85) return 'excellent';
        if ($average >= 75) return 'good';
        if ($average >= 65) return 'satisfactory';
        return 'needs_improvement';
    }

    private function calculateGradeDistribution($grades)
    {
        $distribution = [
            'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0
        ];

        foreach ($grades as $grade) {
            $percentage = $grade->percentage;
            if ($percentage >= 90) $distribution['A']++;
            elseif ($percentage >= 80) $distribution['B']++;
            elseif ($percentage >= 70) $distribution['C']++;
            elseif ($percentage >= 60) $distribution['D']++;
            else $distribution['F']++;
        }

        return $distribution;
    }

    private function calculateConsistencyScore($grades)
    {
        if ($grades->count() < 3) return 0;
        
        $average = $grades->avg('percentage');
        $variance = $grades->map(function($grade) use ($average) {
            return pow($grade->percentage - $average, 2);
        })->avg();
        
        $standardDeviation = sqrt($variance);
        
        // Lower standard deviation = higher consistency
        return max(0, 100 - ($standardDeviation * 2));
    }

    private function getAttendancePercentage($student, $from, $to)
    {
        $attendance = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$from, $to])
            ->get();

        $total = $attendance->count();
        $present = $attendance->where('status', 'present')->count();

        return $total > 0 ? round(($present / $total) * 100, 1) : 0;
    }

    private function getWeeklyAttendancePattern($student)
    {
        $attendance = Attendance::where('student_id', $student->id)
            ->where('date', '>=', now()->subMonth())
            ->get();

        $pattern = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($daysOfWeek as $day) {
            $dayAttendance = $attendance->filter(function($record) use ($day) {
                return Carbon::parse($record->date)->format('l') === $day;
            });

            $total = $dayAttendance->count();
            $present = $dayAttendance->where('status', 'present')->count();

            $pattern[$day] = [
                'total' => $total,
                'present' => $present,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0
            ];
        }

        return $pattern;
    }

    private function getMonthlyAttendanceTrend($student)
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $attendance = Attendance::where('student_id', $student->id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->get();

            $total = $attendance->count();
            $present = $attendance->where('status', 'present')->count();

            $months[$month->format('M Y')] = [
                'total' => $total,
                'present' => $present,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0
            ];
        }

        return $months;
    }

    /**
     * Get class schedule for different views
     */
    private function getClassSchedule($student, $view, $currentDate)
    {
        $schedules = ClassSchedule::where('class_id', $student->class_id)
            ->where('is_active', true)
            ->with(['subject', 'teacher'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        switch ($view) {
            case 'daily':
                return $this->getDailySchedule($schedules, $currentDate);
            case 'weekly':
                return $this->getWeeklySchedule($schedules, $currentDate);
            case 'monthly':
                return $this->getMonthlySchedule($schedules, $currentDate);
            default:
                return $this->getWeeklySchedule($schedules, $currentDate);
        }
    }

    /**
     * Get daily schedule
     */
    private function getDailySchedule($schedules, $date)
    {
        $dayOfWeek = strtolower($date->format('l'));
        
        return $schedules->filter(function($schedule) use ($dayOfWeek) {
            return $schedule->day_of_week === $dayOfWeek;
        })->map(function($schedule) use ($date) {
            return [
                'subject' => $schedule->subject->name ?? 'Unknown',
                'teacher' => $schedule->teacher->full_name ?? 'Unknown',
                'start_time' => $schedule->start_time ? Carbon::parse($schedule->start_time)->format('H:i') : 'N/A',
                'end_time' => $schedule->end_time ? Carbon::parse($schedule->end_time)->format('H:i') : 'N/A',
                'room' => $schedule->room_number ?? 'TBA',
                'duration' => $schedule->duration_minutes,
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('l')
            ];
        })->values()->all();
    }

    /**
     * Get weekly schedule
     */
    private function getWeeklySchedule($schedules, $date)
    {
        $startOfWeek = $date->copy()->startOfWeek();
        $weekSchedule = [];
        
        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($daysOfWeek as $index => $day) {
            $currentDay = $startOfWeek->copy()->addDays($index);
            $daySchedules = $schedules->filter(function($schedule) use ($day) {
                return $schedule->day_of_week === $day;
            });

            $weekSchedule[$day] = [
                'date' => $currentDay->format('Y-m-d'),
                'day_name' => $currentDay->format('l'),
                'day_short' => $currentDay->format('D'),
                'is_today' => $currentDay->isToday(),
                'schedules' => $daySchedules->map(function($schedule) use ($currentDay) {
                    return [
                        'subject' => $schedule->subject->name ?? 'Unknown',
                        'teacher' => $schedule->teacher->full_name ?? 'Unknown',
                        'start_time' => $schedule->start_time ? Carbon::parse($schedule->start_time)->format('H:i') : 'N/A',
                        'end_time' => $schedule->end_time ? Carbon::parse($schedule->end_time)->format('H:i') : 'N/A',
                        'room' => $schedule->room_number ?? 'TBA',
                        'duration' => $schedule->duration_minutes
                    ];
                })->values()->all()
            ];
        }
        
        return $weekSchedule;
    }

    /**
     * Get monthly schedule
     */
    private function getMonthlySchedule($schedules, $date)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        $monthSchedule = [];
        
        $current = $startOfMonth->copy();
        while ($current <= $endOfMonth) {
            $dayOfWeek = strtolower($current->format('l'));
            $daySchedules = $schedules->filter(function($schedule) use ($dayOfWeek) {
                return $schedule->day_of_week === $dayOfWeek;
            });

            if ($daySchedules->isNotEmpty()) {
                $monthSchedule[$current->format('Y-m-d')] = [
                    'date' => $current->format('Y-m-d'),
                    'day_name' => $current->format('l'),
                    'day_number' => $current->format('j'),
                    'is_today' => $current->isToday(),
                    'is_weekend' => $current->isWeekend(),
                    'schedule_count' => $daySchedules->count(),
                    'schedules' => $daySchedules->map(function($schedule) {
                        return [
                            'subject' => $schedule->subject->name ?? 'Unknown',
                            'time_slot' => $schedule->time_slot
                        ];
                    })->values()->all()
                ];
            }
            
            $current->addDay();
        }
        
        return $monthSchedule;
    }

    /**
     * Get upcoming assignments
     */
    private function getUpcomingAssignments($student)
    {
        return Assignment::where('class_id', $student->class_id)
            ->where('status', 'published')
            ->where('due_date', '>=', today())
            ->with(['subject', 'teacher'])
            ->orderBy('due_date')
            ->limit(10)
            ->get()
            ->map(function($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'subject' => $assignment->subject->name ?? 'Unknown',
                    'teacher' => $assignment->teacher->full_name ?? 'Unknown',
                    'type' => $assignment->type,
                    'due_date' => $assignment->due_date->format('Y-m-d'),
                    'due_time' => $assignment->due_time ? Carbon::parse($assignment->due_time)->format('H:i') : null,
                    'days_until_due' => $assignment->days_until_due,
                    'priority' => $assignment->getPriority(),
                    'priority_color' => $assignment->getPriorityColor(),
                    'is_overdue' => $assignment->is_overdue,
                    'total_marks' => $assignment->total_marks
                ];
            })
            ->all();
    }

    /**
     * Get exam schedule
     */
    private function getExamSchedule($student, $currentDate)
    {
        // Get exams from grades table where exam_type is 'exam', 'midterm', or 'final'
        $examTypes = ['exam', 'midterm', 'final'];
        $startDate = $currentDate->copy()->startOfMonth();
        $endDate = $currentDate->copy()->endOfMonth();

        return Grade::where('student_id', $student->id)
            ->whereIn('exam_type', $examTypes)
            ->whereBetween('exam_date', [$startDate, $endDate])
            ->with(['subject', 'teacher'])
            ->orderBy('exam_date')
            ->get()
            ->map(function($exam) {
                return [
                    'subject' => $exam->subject->name ?? 'Unknown',
                    'teacher' => $exam->teacher->full_name ?? 'Unknown',
                    'exam_type' => $exam->exam_type,
                    'exam_date' => $exam->exam_date->format('Y-m-d'),
                    'total_marks' => $exam->total_marks,
                    'duration' => '2 hours', // Default duration
                    'room' => 'TBA' // To be announced
                ];
            })
            ->all();
    }

    /**
     * Get academic calendar events
     */
    private function getAcademicCalendar($student, $currentDate)
    {
        $events = [];
        
        // Add assignment due dates
        $assignments = Assignment::where('class_id', $student->class_id)
            ->where('status', 'published')
            ->whereBetween('due_date', [$currentDate->copy()->startOfMonth(), $currentDate->copy()->endOfMonth()])
            ->with('subject')
            ->get();

        foreach ($assignments as $assignment) {
            $events[] = [
                'date' => $assignment->due_date->format('Y-m-d'),
                'title' => $assignment->title,
                'type' => 'assignment',
                'subject' => $assignment->subject->name ?? 'Unknown',
                'priority' => $assignment->getPriority(),
                'color' => $assignment->getPriorityColor()
            ];
        }

        // Add exam dates
        $exams = Grade::where('student_id', $student->id)
            ->whereIn('exam_type', ['exam', 'midterm', 'final'])
            ->whereBetween('exam_date', [$currentDate->copy()->startOfMonth(), $currentDate->copy()->endOfMonth()])
            ->with('subject')
            ->get();

        foreach ($exams as $exam) {
            $events[] = [
                'date' => $exam->exam_date->format('Y-m-d'),
                'title' => ucfirst($exam->exam_type) . ' - ' . ($exam->subject->name ?? 'Unknown'),
                'type' => 'exam',
                'subject' => $exam->subject->name ?? 'Unknown',
                'priority' => 'high',
                'color' => 'danger'
            ];
        }

        // Sort events by date
        usort($events, function($a, $b) {
            return strcmp($a['date'], $b['date']);
        });

        return $events;
    }

    /**
     * Get schedule conflicts (if any)
     */
    private function getScheduleConflicts($student, $currentDate)
    {
        // Check for overlapping class schedules
        $schedules = ClassSchedule::where('class_id', $student->class_id)
            ->where('is_active', true)
            ->get();

        $conflicts = [];
        
        foreach ($schedules as $schedule) {
            $scheduleConflicts = $schedule->getConflicts();
            if (!empty($scheduleConflicts)) {
                $conflicts[] = [
                    'schedule' => $schedule,
                    'conflicts' => $scheduleConflicts
                ];
            }
        }

        return $conflicts;
    }

    // Additional methods for progress reports would be implemented here
    private function getOverallProgress($student, $type, $period) { /* Implementation */ }
    private function getSubjectProgress($student, $type, $period) { /* Implementation */ }
    private function getAttendanceProgress($student, $type, $period) { /* Implementation */ }
    private function getProgressRecommendations($student, $type, $period) { /* Implementation */ }
    private function getPerformanceComparison($student, $dateFrom, $dateTo) { /* Implementation */ }
    private function getYearlyAttendanceSummary($student, $year) { /* Implementation */ }
    private function getAttendancePatterns($student) { /* Implementation */ }
}