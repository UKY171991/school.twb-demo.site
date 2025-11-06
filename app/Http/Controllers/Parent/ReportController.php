<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\Message;
use App\Models\Meeting;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends BaseController
{
    public function index()
    {
        $parent = Auth::user();
        $children = $parent->children()->with(['school', 'classModel.teacher'])->get();
        
        // Get summary statistics
        $totalChildren = $children->count();
        $totalConversations = Conversation::where('parent_id', $parent->id)->count();
        $totalMeetings = Meeting::where('parent_id', $parent->id)->count();
        $pendingPermissions = $this->getPendingPermissionsCount($children);
        
        return view('parent.reports.index', compact(
            'children', 
            'totalChildren', 
            'totalConversations', 
            'totalMeetings', 
            'pendingPermissions'
        ));
    }

    public function childProgress(Request $request, Student $student)
    {
        // Verify parent has access to this student
        $parent = Auth::user();
        if (!$parent->children()->where('id', $student->id)->exists()) {
            abort(403, 'Unauthorized access to student data');
        }

        $dateRange = $request->get('date_range', '30'); // Default to 30 days
        $startDate = Carbon::now()->subDays((int)$dateRange);
        
        // Academic Performance Data
        $academicData = $this->getAcademicPerformanceData($student, $startDate);
        
        // Attendance Data
        $attendanceData = $this->getAttendanceData($student, $startDate);
        
        // Assignment Data
        $assignmentData = $this->getAssignmentData($student, $startDate);
        
        // Comparative Analysis (compare with class average)
        $comparativeData = $this->getComparativeAnalysis($student, $startDate);
        
        // Progress Trends
        $progressTrends = $this->getProgressTrends($student, $startDate);
        
        return view('parent.reports.child-progress', compact(
            'student',
            'dateRange',
            'academicData',
            'attendanceData', 
            'assignmentData',
            'comparativeData',
            'progressTrends'
        ));
    }

    public function familyDashboard()
    {
        $parent = Auth::user();
        $children = $parent->children()->with(['school', 'classModel.teacher'])->get();
        
        // Multi-child performance overview
        $familyOverview = [];
        foreach ($children as $child) {
            $familyOverview[] = [
                'student' => $child,
                'academic_summary' => $this->getAcademicSummary($child),
                'attendance_summary' => $this->getAttendanceSummary($child),
                'recent_activity' => $this->getRecentActivity($child),
                'alerts' => $this->getStudentAlerts($child)
            ];
        }
        
        // Family engagement metrics
        $engagementMetrics = $this->getFamilyEngagementMetrics($parent);
        
        // Family trends and insights
        $familyTrends = $this->getFamilyTrends($children);
        
        return view('parent.reports.family-dashboard', compact(
            'children',
            'familyOverview',
            'engagementMetrics',
            'familyTrends'
        ));
    }

    public function engagementReport()
    {
        $parent = Auth::user();
        
        // Communication frequency analysis
        $communicationStats = $this->getCommunicationStats($parent);
        
        // Meeting attendance tracking
        $meetingStats = $this->getMeetingStats($parent);
        
        // Parent involvement metrics
        $involvementMetrics = $this->getParentInvolvementMetrics($parent);
        
        // Engagement trends over time
        $engagementTrends = $this->getEngagementTrends($parent);
        
        return view('parent.reports.engagement', compact(
            'communicationStats',
            'meetingStats',
            'involvementMetrics',
            'engagementTrends'
        ));
    }

    public function exportReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:child_progress,family_overview,engagement',
            'student_id' => 'nullable|exists:students,id',
            'format' => 'required|in:pdf,excel',
            'date_range' => 'nullable|integer|min:1|max:365'
        ]);

        $parent = Auth::user();
        $reportType = $request->report_type;
        $format = $request->format;
        
        // Generate report data based on type
        $reportData = $this->generateReportData($reportType, $request, $parent);
        
        // Export in requested format
        if ($format === 'pdf') {
            return $this->exportToPdf($reportData, $reportType);
        } else {
            return $this->exportToExcel($reportData, $reportType);
        }
    }

    private function getAcademicPerformanceData(Student $student, Carbon $startDate): array
    {
        $grades = Grade::where('student_id', $student->id)
            ->where('created_at', '>=', $startDate)
            ->with(['subject', 'teacher'])
            ->orderBy('created_at')
            ->get();

        $subjectPerformance = $grades->groupBy('subject.name')->map(function ($subjectGrades) {
            $average = $subjectGrades->avg('percentage');
            $trend = $this->calculateTrend($subjectGrades->pluck('percentage')->toArray());
            
            return [
                'average' => round($average, 2),
                'count' => $subjectGrades->count(),
                'trend' => $trend,
                'latest_grade' => $subjectGrades->last()->percentage ?? 0,
                'grades' => $subjectGrades->map(function ($grade) {
                    return [
                        'date' => $grade->created_at->format('Y-m-d'),
                        'percentage' => $grade->percentage,
                        'exam_type' => $grade->exam_type
                    ];
                })
            ];
        });

        return [
            'overall_average' => round($grades->avg('percentage'), 2),
            'total_grades' => $grades->count(),
            'subject_performance' => $subjectPerformance,
            'recent_grades' => $grades->take(5)
        ];
    }

    private function getAttendanceData(Student $student, Carbon $startDate): array
    {
        $attendance = Attendance::where('student_id', $student->id)
            ->where('date', '>=', $startDate)
            ->orderBy('date')
            ->get();

        $totalDays = $attendance->count();
        $presentDays = $attendance->where('status', 'present')->count();
        $absentDays = $attendance->where('status', 'absent')->count();
        $lateDays = $attendance->where('status', 'late')->count();

        $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

        return [
            'attendance_rate' => $attendanceRate,
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'weekly_pattern' => $this->getWeeklyAttendancePattern($attendance),
            'monthly_trend' => $this->getMonthlyAttendanceTrend($attendance)
        ];
    }

    private function getAssignmentData(Student $student, Carbon $startDate): array
    {
        $assignments = Assignment::whereHas('students', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })
        ->where('created_at', '>=', $startDate)
        ->with(['subject', 'students' => function ($query) use ($student) {
            $query->where('student_id', $student->id);
        }])
        ->get();

        $totalAssignments = $assignments->count();
        $completedAssignments = $assignments->filter(function ($assignment) {
            return $assignment->students->first()?->pivot->status === 'completed';
        })->count();

        $completionRate = $totalAssignments > 0 ? round(($completedAssignments / $totalAssignments) * 100, 2) : 0;

        return [
            'total_assignments' => $totalAssignments,
            'completed_assignments' => $completedAssignments,
            'completion_rate' => $completionRate,
            'overdue_assignments' => $this->getOverdueAssignments($student),
            'upcoming_assignments' => $this->getUpcomingAssignments($student)
        ];
    }

    private function getComparativeAnalysis(Student $student, Carbon $startDate): array
    {
        // Get class average for comparison
        $classmates = Student::where('class_id', $student->class_id)
            ->where('id', '!=', $student->id)
            ->get();

        if ($classmates->isEmpty()) {
            return ['available' => false];
        }

        $studentAverage = Grade::where('student_id', $student->id)
            ->where('created_at', '>=', $startDate)
            ->avg('percentage');

        $classAverage = Grade::whereIn('student_id', $classmates->pluck('id'))
            ->where('created_at', '>=', $startDate)
            ->avg('percentage');

        return [
            'available' => true,
            'student_average' => round($studentAverage, 2),
            'class_average' => round($classAverage, 2),
            'performance_vs_class' => $studentAverage > $classAverage ? 'above' : 'below',
            'difference' => round(abs($studentAverage - $classAverage), 2)
        ];
    }

    private function getProgressTrends(Student $student, Carbon $startDate): array
    {
        $grades = Grade::where('student_id', $student->id)
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at')
            ->get();

        $monthlyProgress = $grades->groupBy(function ($grade) {
            return $grade->created_at->format('Y-m');
        })->map(function ($monthGrades) {
            return round($monthGrades->avg('percentage'), 2);
        });

        return [
            'monthly_progress' => $monthlyProgress,
            'overall_trend' => $this->calculateTrend($monthlyProgress->values()->toArray()),
            'improvement_areas' => $this->identifyImprovementAreas($student, $startDate),
            'strengths' =>