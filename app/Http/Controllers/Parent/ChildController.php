<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChildController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $parent = ParentModel::where('user_id', $user->id)->first();
        
        if (!$parent) {
            return redirect()->route('login')->with('error', 'Parent profile not found.');
        }

        $children = $parent->children()->with(['user', 'classModel', 'school'])->get();

        // Get summary data for each child
        $childrenData = $children->map(function($child) {
            $attendanceStats = $child->getAttendanceStatistics();
            $gradeStats = $child->getGradeStatistics();
            $academicStatus = $child->getAcademicStatus();
            
            return [
                'child' => $child,
                'attendance_stats' => $attendanceStats,
                'grade_stats' => $gradeStats,
                'academic_status' => $academicStatus,
                'recent_alerts' => $this->getRecentAlertsForChild($child),
                'upcoming_events' => $this->getUpcomingEventsForChild($child),
            ];
        });

        return view('parent.children.index', compact('childrenData', 'parent'));
    }

    public function show(Student $student)
    {
        $user = auth()->user();
        $parent = ParentModel::where('user_id', $user->id)->first();
        
        if (!$parent) {
            return redirect()->route('login')->with('error', 'Parent profile not found.');
        }

        // Ensure parent can only view their own children
        if (!$parent->children->contains($student->id)) {
            abort(403, 'Unauthorized access to this student.');
        }

        $student->load(['user', 'classModel', 'school', 'subjects']);

        // Get comprehensive monitoring data
        $monitoringData = [
            'academic_overview' => $this->getAcademicOverview($student),
            'attendance_analysis' => $this->getAttendanceAnalysis($student),
            'grade_tracking' => $this->getGradeTracking($student),
            'performance_trends' => $this->getPerformanceTrends($student),
            'behavioral_insights' => $this->getBehavioralInsights($student),
            'academic_alerts' => $this->getAcademicAlerts($student),
            'upcoming_events' => $this->getUpcomingEventsForChild($student),
            'teacher_feedback' => $this->getTeacherFeedback($student),
        ];

        return view('parent.children.show', compact('student', 'parent', 'monitoringData'));
    }

    public function getChildAttendance(Request $request)
    {
        $parent = auth()->user()->parent;
        $student = Student::where('id', $request->student_id)
            ->whereIn('id', $parent->students->pluck('id'))
            ->firstOrFail();

        $query = \App\Models\Attendance::with(['classModel'])
            ->where('student_id', $student->id);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->month) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->year) {
            $query->whereYear('date', $request->year);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    public function getChildGrades(Request $request)
    {
        $parent = auth()->user()->parent;
        $student = Student::where('id', $request->student_id)
            ->whereIn('id', $parent->students->pluck('id'))
            ->firstOrFail();

        $query = \App\Models\Grade::with(['subject'])
            ->where('student_id', $student->id);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->month) {
            $query->whereMonth('exam_date', $request->month);
        }

        if ($request->year) {
            $query->whereYear('exam_date', $request->year);
        }

        $grades = $query->orderBy('exam_date', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    /**
     * Get detailed attendance analysis for a child
     */
    public function attendanceAnalysis(Student $student)
    {
        $user = auth()->user();
        $parent = ParentModel::where('user_id', $user->id)->first();
        
        if (!$parent || !$parent->children->contains($student->id)) {
            abort(403, 'Unauthorized access.');
        }

        $attendanceData = $this->getAttendanceAnalysis($student);
        
        return view('parent.children.attendance-analysis', compact('student', 'attendanceData'));
    }

    /**
     * Get detailed grade tracking for a child
     */
    public function gradeTracking(Student $student)
    {
        $user = auth()->user();
        $parent = ParentModel::where('user_id', $user->id)->first();
        
        if (!$parent || !$parent->children->contains($student->id)) {
            abort(403, 'Unauthorized access.');
        }

        $gradeData = $this->getGradeTracking($student);
        
        return view('parent.children.grade-tracking', compact('student', 'gradeData'));
    }

    /**
     * Get performance trends analysis
     */
    public function performanceTrends(Student $student)
    {
        $user = auth()->user();
        $parent = ParentModel::where('user_id', $user->id)->first();
        
        if (!$parent || !$parent->children->contains($student->id)) {
            abort(403, 'Unauthorized access.');
        }

        $trendsData = $this->getPerformanceTrends($student);
        
        return view('parent.children.performance-trends', compact('student', 'trendsData'));
    }

    /**
     * Get academic overview for a child
     */
    private function getAcademicOverview(Student $student): array
    {
        $attendanceStats = $student->getAttendanceStatistics();
        $gradeStats = $student->getGradeStatistics();
        $academicStatus = $student->getAcademicStatus();

        // Get current semester/term performance
        $currentTermGrades = Grade::where('student_id', $student->id)
            ->where('exam_date', '>=', Carbon::now()->startOfMonth()->subMonths(3))
            ->with('subject')
            ->get();

        $subjectPerformance = $currentTermGrades->groupBy('subject_id')->map(function($grades) {
            $subject = $grades->first()->subject;
            return [
                'subject_name' => $subject ? $subject->name : 'Unknown',
                'average' => round($grades->avg('calculated_percentage'), 1),
                'count' => $grades->count(),
                'trend' => $this->calculateSubjectTrend($grades),
                'latest_grade' => $grades->sortByDesc('exam_date')->first()->calculated_percentage ?? 0,
            ];
        });

        return [
            'attendance_stats' => $attendanceStats,
            'grade_stats' => $gradeStats,
            'academic_status' => $academicStatus,
            'subject_performance' => $subjectPerformance->values()->all(),
            'overall_trend' => $this->calculateOverallTrend($currentTermGrades),
            'strengths' => $subjectPerformance->where('average', '>=', 80)->values()->all(),
            'areas_for_improvement' => $subjectPerformance->where('average', '<', 70)->values()->all(),
        ];
    }

    /**
     * Get comprehensive attendance analysis
     */
    private function getAttendanceAnalysis(Student $student): array
    {
        // Get attendance data for different periods
        $thisMonth = Attendance::where('student_id', $student->id)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->get();

        $lastMonth = Attendance::where('student_id', $student->id)
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->get();

        $thisYear = Attendance::where('student_id', $student->id)
            ->whereYear('date', Carbon::now()->year)
            ->get();

        // Calculate patterns
        $weeklyPattern = $this->getWeeklyAttendancePattern($student);
        $monthlyTrend = $this->getMonthlyAttendanceTrend($student);
        $absenceReasons = $this->getAbsenceReasons($student);

        // Identify concerning patterns
        $concerningPatterns = $this->identifyAttendanceConcerns($student);

        return [
            'current_month' => $this->calculateAttendanceStats($thisMonth),
            'last_month' => $this->calculateAttendanceStats($lastMonth),
            'year_to_date' => $this->calculateAttendanceStats($thisYear),
            'weekly_pattern' => $weeklyPattern,
            'monthly_trend' => $monthlyTrend,
            'absence_reasons' => $absenceReasons,
            'concerning_patterns' => $concerningPatterns,
            'attendance_calendar' => $this->getAttendanceCalendar($student),
        ];
    }

    /**
     * Get comprehensive grade tracking
     */
    private function getGradeTracking(Student $student): array
    {
        $grades = Grade::where('student_id', $student->id)
            ->with(['subject', 'teacher'])
            ->orderBy('exam_date', 'desc')
            ->get();

        // Group by subject for detailed analysis
        $subjectAnalysis = $grades->groupBy('subject_id')->map(function($subjectGrades) {
            $subject = $subjectGrades->first()->subject;
            $sortedGrades = $subjectGrades->sortBy('exam_date');
            
            return [
                'subject_name' => $subject ? $subject->name : 'Unknown',
                'subject_code' => $subject ? $subject->code : 'N/A',
                'total_assessments' => $subjectGrades->count(),
                'average_grade' => round($subjectGrades->avg('calculated_percentage'), 1),
                'highest_grade' => $subjectGrades->max('calculated_percentage'),
                'lowest_grade' => $subjectGrades->min('calculated_percentage'),
                'improvement_trend' => $this->calculateImprovementTrend($sortedGrades),
                'consistency_score' => $this->calculateConsistencyScore($subjectGrades),
                'recent_performance' => $subjectGrades->take(5)->map(function($grade) {
                    return [
                        'percentage' => $grade->calculated_percentage,
                        'exam_type' => $grade->exam_type,
                        'date' => $grade->exam_date,
                        'marks' => $grade->marks_obtained . '/' . $grade->total_marks,
                    ];
                })->values()->all(),
            ];
        });

        // Grade distribution analysis
        $gradeDistribution = $this->calculateGradeDistribution($grades);
        
        // Performance by exam type
        $examTypePerformance = $grades->groupBy('exam_type')->map(function($typeGrades, $type) {
            return [
                'exam_type' => $type,
                'average' => round($typeGrades->avg('calculated_percentage'), 1),
                'count' => $typeGrades->count(),
            ];
        });

        return [
            'subject_analysis' => $subjectAnalysis->values()->all(),
            'grade_distribution' => $gradeDistribution,
            'exam_type_performance' => $examTypePerformance->values()->all(),
            'overall_trend' => $this->calculateOverallGradeTrend($grades),
            'performance_alerts' => $this->identifyPerformanceAlerts($student, $subjectAnalysis),
        ];
    }

    /**
     * Get performance trends over time
     */
    private function getPerformanceTrends(Student $student): array
    {
        // Get data for the last 12 months
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
            $monthlyGrades = Grade::where('student_id', $student->id)
                ->whereMonth('exam_date', $month->month)
                ->whereYear('exam_date', $month->year)
                ->get();

            $monthlyAttendance = Attendance::where('student_id', $student->id)
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->get();

            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'month_key' => $month->format('Y-m'),
                'average_grade' => $monthlyGrades->count() > 0 ? round($monthlyGrades->avg('calculated_percentage'), 1) : null,
                'attendance_rate' => $this->calculateAttendanceStats($monthlyAttendance)['percentage'],
                'total_assessments' => $monthlyGrades->count(),
                'total_school_days' => $monthlyAttendance->count(),
            ];
        }

        // Calculate trends
        $gradeTrend = $this->calculateTrendDirection($monthlyData, 'average_grade');
        $attendanceTrend = $this->calculateTrendDirection($monthlyData, 'attendance_rate');

        return [
            'monthly_data' => $monthlyData,
            'grade_trend' => $gradeTrend,
            'attendance_trend' => $attendanceTrend,
            'correlation_analysis' => $this->analyzeGradeAttendanceCorrelation($monthlyData),
            'seasonal_patterns' => $this->identifySeasonalPatterns($monthlyData),
        ];
    }

    /**
     * Get behavioral insights
     */
    private function getBehavioralInsights(Student $student): array
    {
        // Analyze attendance patterns for behavioral insights
        $recentAbsences = Attendance::where('student_id', $student->id)
            ->where('status', 'absent')
            ->where('date', '>=', Carbon::now()->subDays(30))
            ->orderBy('date', 'desc')
            ->get();

        $lateArrivals = Attendance::where('student_id', $student->id)
            ->where('status', 'late')
            ->where('date', '>=', Carbon::now()->subDays(30))
            ->count();

        // Analyze grade submission patterns
        $lateSubmissions = $this->analyzeLateSubmissions($student);
        
        // Identify patterns
        $behavioralPatterns = [];
        
        if ($recentAbsences->count() >= 5) {
            $behavioralPatterns[] = [
                'type' => 'attendance_concern',
                'severity' => 'high',
                'description' => 'Frequent absences in the last 30 days',
                'count' => $recentAbsences->count(),
                'recommendation' => 'Consider discussing attendance patterns with the child and school counselor',
            ];
        }

        if ($lateArrivals >= 5) {
            $behavioralPatterns[] = [
                'type' => 'punctuality_concern',
                'severity' => 'medium',
                'description' => 'Frequent late arrivals',
                'count' => $lateArrivals,
                'recommendation' => 'Review morning routine and transportation arrangements',
            ];
        }

        return [
            'recent_absences' => $recentAbsences->count(),
            'late_arrivals' => $lateArrivals,
            'behavioral_patterns' => $behavioralPatterns,
            'engagement_indicators' => $this->getEngagementIndicators($student),
        ];
    }

    /**
     * Get academic alerts for a child
     */
    private function getAcademicAlerts(Student $student): array
    {
        $alerts = [];
        
        $attendanceStats = $student->getAttendanceStatistics();
        $gradeStats = $student->getGradeStatistics();

        // Attendance alerts
        if ($attendanceStats['attendance_percentage'] < 75) {
            $alerts[] = [
                'type' => 'attendance',
                'severity' => 'high',
                'title' => 'Low Attendance Rate',
                'message' => "Attendance is {$attendanceStats['attendance_percentage']}%, below the recommended 75%",
                'action_required' => true,
                'created_at' => now(),
            ];
        }

        // Grade alerts
        if ($gradeStats['average_grade'] < 60 && $gradeStats['total_grades'] > 0) {
            $alerts[] = [
                'type' => 'grades',
                'severity' => 'high',
                'title' => 'Low Academic Performance',
                'message' => "Average grade is {$gradeStats['average_grade']}%, below passing threshold",
                'action_required' => true,
                'created_at' => now(),
            ];
        }

        // Recent performance drop
        $recentGrades = Grade::where('student_id', $student->id)
            ->orderBy('exam_date', 'desc')
            ->limit(5)
            ->get();

        $olderGrades = Grade::where('student_id', $student->id)
            ->orderBy('exam_date', 'desc')
            ->skip(5)
            ->limit(5)
            ->get();

        if ($recentGrades->count() >= 3 && $olderGrades->count() >= 3) {
            $recentAvg = $recentGrades->avg('calculated_percentage');
            $olderAvg = $olderGrades->avg('calculated_percentage');
            
            if ($recentAvg < $olderAvg - 10) {
                $alerts[] = [
                    'type' => 'performance_drop',
                    'severity' => 'medium',
                    'title' => 'Recent Performance Decline',
                    'message' => 'Recent grades show a declining trend compared to previous performance',
                    'action_required' => true,
                    'created_at' => now(),
                ];
            }
        }

        // Consecutive absences
        $consecutiveAbsences = $this->getConsecutiveAbsences($student);
        if ($consecutiveAbsences >= 3) {
            $alerts[] = [
                'type' => 'consecutive_absences',
                'severity' => 'high',
                'title' => 'Consecutive Absences',
                'message' => "{$consecutiveAbsences} consecutive days absent",
                'action_required' => true,
                'created_at' => now(),
            ];
        }

        return $alerts;
    }

    /**
     * Get recent alerts for a child (used in index view)
     */
    private function getRecentAlertsForChild(Student $student): array
    {
        return array_slice($this->getAcademicAlerts($student), 0, 3);
    }

    /**
     * Get upcoming events for a child
     */
    private function getUpcomingEventsForChild(Student $student): array
    {
        $events = [];

        // Upcoming assignments
        $assignments = Assignment::where('class_id', $student->class_id)
            ->where('status', 'published')
            ->where('due_date', '>=', today())
            ->where('due_date', '<=', today()->addDays(14))
            ->with('subject')
            ->orderBy('due_date')
            ->get();

        foreach ($assignments as $assignment) {
            $events[] = [
                'type' => 'assignment',
                'title' => $assignment->title,
                'subject' => $assignment->subject->name ?? 'Unknown',
                'date' => $assignment->due_date,
                'priority' => $assignment->getPriority(),
                'days_until' => $assignment->days_until_due,
            ];
        }

        return array_slice($events, 0, 5);
    }

    /**
     * Get teacher feedback for a child
     */
    private function getTeacherFeedback(Student $student): array
    {
        // This would typically come from a feedback/comments system
        // For now, return placeholder data
        return [
            'recent_feedback' => [],
            'teacher_notes' => [],
            'behavioral_comments' => [],
        ];
    }

    // Helper methods for calculations
    private function calculateAttendanceStats($attendanceCollection): array
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
            'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
        ];
    }

    private function calculateSubjectTrend($grades): string
    {
        if ($grades->count() < 2) return 'stable';
        
        $recent = $grades->sortByDesc('exam_date')->take(2)->avg('calculated_percentage');
        $older = $grades->sortByDesc('exam_date')->skip(2)->take(2)->avg('calculated_percentage');
        
        if ($recent > $older + 5) return 'improving';
        if ($recent < $older - 5) return 'declining';
        return 'stable';
    }

    private function calculateOverallTrend($grades): string
    {
        if ($grades->count() < 4) return 'stable';
        
        $recentAvg = $grades->sortByDesc('exam_date')->take(5)->avg('calculated_percentage');
        $olderAvg = $grades->sortByDesc('exam_date')->skip(5)->take(5)->avg('calculated_percentage');
        
        if ($recentAvg > $olderAvg + 3) return 'improving';
        if ($recentAvg < $olderAvg - 3) return 'declining';
        return 'stable';
    }

    private function getConsecutiveAbsences(Student $student): int
    {
        $recentAttendance = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $consecutiveCount = 0;
        foreach ($recentAttendance as $attendance) {
            if ($attendance->status === 'absent') {
                $consecutiveCount++;
            } else {
                break;
            }
        }

        return $consecutiveCount;
    }

    // Additional helper methods would be implemented here for:
    // - getWeeklyAttendancePattern()
    // - getMonthlyAttendanceTrend()
    // - getAbsenceReasons()
    // - identifyAttendanceConcerns()
    // - getAttendanceCalendar()
    // - calculateGradeDistribution()
    // - calculateImprovementTrend()
    // - calculateConsistencyScore()
    // - identifyPerformanceAlerts()
    // - calculateOverallGradeTrend()
    // - calculateTrendDirection()
    // - analyzeGradeAttendanceCorrelation()
    // - identifySeasonalPatterns()
    // - analyzeLateSubmissions()
    // - getEngagementIndicators()
}
