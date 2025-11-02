<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display teacher reports dashboard
     */
    public function index()
    {
        $teacher = auth()->user()->teacher;
        
        // Get teacher's classes
        $classes = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->with(['students', 'subjects'])
            ->get();

        // Get summary statistics
        $stats = $this->getReportStats($teacher);

        return view('tc.reports.index', compact('classes', 'stats'));
    }

    /**
     * Generate class performance report
     */
    public function classPerformance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'subject_id' => 'nullable|exists:subjects,id'
        ]);

        $teacher = auth()->user()->teacher;
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->subMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now();

        // Get grade distribution
        $gradeQuery = Grade::where('class_id', $class->id)
            ->whereBetween('exam_date', [$dateFrom, $dateTo]);
            
        if ($request->subject_id) {
            $gradeQuery->where('subject_id', $request->subject_id);
        }

        $grades = $gradeQuery->get();
        
        // Calculate grade distribution
        $gradeDistribution = $this->calculateGradeDistribution($grades);
        
        // Get improvement tracking
        $improvementData = $this->getImprovementTracking($class->id, $dateFrom, $dateTo, $request->subject_id);
        
        // Get attendance correlation
        $attendanceCorrelation = $this->getAttendanceGradeCorrelation($class->id, $dateFrom, $dateTo);

        $reportData = [
            'class' => $class,
            'period' => [
                'from' => $dateFrom->format('Y-m-d'),
                'to' => $dateTo->format('Y-m-d')
            ],
            'grade_distribution' => $gradeDistribution,
            'improvement_tracking' => $improvementData,
            'attendance_correlation' => $attendanceCorrelation,
            'summary' => [
                'total_students' => $class->students()->where('status', 'active')->count(),
                'total_grades' => $grades->count(),
                'average_grade' => $grades->avg('percentage'),
                'passing_rate' => $grades->where('percentage', '>=', 60)->count() / max($grades->count(), 1) * 100
            ]
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $reportData
            ]);
        }

        return view('tc.reports.class-performance', compact('reportData'));
    }

    /**
     * Generate parent communication log
     */
    public function parentCommunication(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'class_id' => 'nullable|exists:classes,id',
            'student_id' => 'nullable|exists:students,id'
        ]);

        $teacher = auth()->user()->teacher;
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->subMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now();

        // Get teacher's classes
        $teacherClassIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        // Build communication query
        $communicationQuery = Notification::whereIn('school_id', function($query) use ($teacherClassIds) {
                $query->select('school_id')
                      ->from('classes')
                      ->whereIn('id', $teacherClassIds);
            })
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where(function($query) use ($teacher) {
                $query->where('title', 'LIKE', 'Message from ' . $teacher->full_name . '%')
                      ->orWhere('title', 'LIKE', '%Parent Communication%');
            });

        if ($request->class_id && in_array($request->class_id, $teacherClassIds->toArray())) {
            $communicationQuery->whereIn('user_id', function($query) use ($request) {
                $query->select('user_id')
                      ->from('students')
                      ->where('class_id', $request->class_id)
                      ->whereNotNull('user_id');
            });
        }

        if ($request->student_id) {
            $student = Student::where('id', $request->student_id)
                ->whereIn('class_id', $teacherClassIds)
                ->first();
            if ($student && $student->user_id) {
                $communicationQuery->where('user_id', $student->user_id);
            }
        }

        $communications = $communicationQuery->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get communication statistics
        $stats = $this->getCommunicationStats($teacher, $dateFrom, $dateTo);

        $reportData = [
            'communications' => $communications,
            'period' => [
                'from' => $dateFrom->format('Y-m-d'),
                'to' => $dateTo->format('Y-m-d')
            ],
            'stats' => $stats,
            'filters' => [
                'class_id' => $request->class_id,
                'student_id' => $request->student_id
            ]
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $reportData
            ]);
        }

        return view('tc.reports.parent-communication', compact('reportData'));
    }

    /**
     * Generate teaching effectiveness analytics
     */
    public function teachingEffectiveness(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'class_id' => 'nullable|exists:classes,id'
        ]);

        $teacher = auth()->user()->teacher;
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->subMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now();

        // Get teacher's classes
        $classQuery = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true);
            
        if ($request->class_id) {
            $classQuery->where('id', $request->class_id);
        }
        
        $classes = $classQuery->get();

        $effectivenessData = [];
        
        foreach ($classes as $class) {
            $classData = $this->calculateClassEffectiveness($class, $dateFrom, $dateTo);
            $effectivenessData[] = $classData;
        }

        // Calculate overall effectiveness metrics
        $overallMetrics = $this->calculateOverallEffectiveness($effectivenessData);

        $reportData = [
            'period' => [
                'from' => $dateFrom->format('Y-m-d'),
                'to' => $dateTo->format('Y-m-d')
            ],
            'class_effectiveness' => $effectivenessData,
            'overall_metrics' => $overallMetrics,
            'recommendations' => $this->generateTeachingRecommendations($effectivenessData)
        ];

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $reportData
            ]);
        }

        return view('tc.reports.teaching-effectiveness', compact('reportData'));
    }

    /**
     * Export report data
     */
    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:class_performance,parent_communication,teaching_effectiveness',
            'format' => 'required|in:pdf,excel,csv'
        ]);

        $teacher = auth()->user()->teacher;
        
        // Generate report data based on type
        switch ($request->report_type) {
            case 'class_performance':
                $data = $this->generateClassPerformanceExport($request);
                break;
            case 'parent_communication':
                $data = $this->generateCommunicationExport($request);
                break;
            case 'teaching_effectiveness':
                $data = $this->generateEffectivenessExport($request);
                break;
        }

        // Return appropriate format
        switch ($request->format) {
            case 'pdf':
                return $this->exportToPdf($data, $request->report_type);
            case 'excel':
                return $this->exportToExcel($data, $request->report_type);
            case 'csv':
                return $this->exportToCsv($data, $request->report_type);
        }
    }

    /**
     * Get report statistics for dashboard
     */
    private function getReportStats($teacher)
    {
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $totalStudents = Student::whereIn('class_id', $classIds)
            ->where('status', 'active')
            ->count();

        $recentGrades = Grade::whereIn('class_id', $classIds)
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        $recentCommunications = Notification::where('title', 'LIKE', 'Message from ' . $teacher->full_name . '%')
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        $averageClassPerformance = Grade::whereIn('class_id', $classIds)
            ->where('exam_date', '>=', now()->subMonth())
            ->avg('percentage') ?? 0;

        return [
            'total_students' => $totalStudents,
            'recent_grades' => $recentGrades,
            'recent_communications' => $recentCommunications,
            'average_performance' => round($averageClassPerformance, 1)
        ];
    }

    /**
     * Calculate grade distribution
     */
    private function calculateGradeDistribution($grades)
    {
        $distribution = [
            'A' => ['range' => '90-100', 'count' => 0, 'percentage' => 0],
            'B' => ['range' => '80-89', 'count' => 0, 'percentage' => 0],
            'C' => ['range' => '70-79', 'count' => 0, 'percentage' => 0],
            'D' => ['range' => '60-69', 'count' => 0, 'percentage' => 0],
            'F' => ['range' => '0-59', 'count' => 0, 'percentage' => 0]
        ];

        $total = $grades->count();
        
        if ($total === 0) {
            return $distribution;
        }

        foreach ($grades as $grade) {
            $percentage = $grade->percentage;
            
            if ($percentage >= 90) {
                $distribution['A']['count']++;
            } elseif ($percentage >= 80) {
                $distribution['B']['count']++;
            } elseif ($percentage >= 70) {
                $distribution['C']['count']++;
            } elseif ($percentage >= 60) {
                $distribution['D']['count']++;
            } else {
                $distribution['F']['count']++;
            }
        }

        // Calculate percentages
        foreach ($distribution as $grade => &$data) {
            $data['percentage'] = round(($data['count'] / $total) * 100, 1);
        }

        return $distribution;
    }

    /**
     * Get improvement tracking data
     */
    private function getImprovementTracking($classId, $dateFrom, $dateTo, $subjectId = null)
    {
        $query = Grade::where('class_id', $classId)
            ->whereBetween('exam_date', [$dateFrom, $dateTo]);
            
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $grades = $query->orderBy('exam_date')
            ->get()
            ->groupBy('student_id');

        $improvementData = [];
        
        foreach ($grades as $studentId => $studentGrades) {
            if ($studentGrades->count() < 2) continue;
            
            $firstGrade = $studentGrades->first()->percentage;
            $lastGrade = $studentGrades->last()->percentage;
            $improvement = $lastGrade - $firstGrade;
            
            $student = Student::find($studentId);
            if ($student) {
                $improvementData[] = [
                    'student_name' => $student->full_name,
                    'first_grade' => $firstGrade,
                    'last_grade' => $lastGrade,
                    'improvement' => $improvement,
                    'trend' => $improvement > 0 ? 'improving' : ($improvement < 0 ? 'declining' : 'stable')
                ];
            }
        }

        return collect($improvementData)->sortByDesc('improvement')->values()->all();
    }

    /**
     * Get attendance-grade correlation
     */
    private function getAttendanceGradeCorrelation($classId, $dateFrom, $dateTo)
    {
        $students = Student::where('class_id', $classId)
            ->where('status', 'active')
            ->get();

        $correlationData = [];
        
        foreach ($students as $student) {
            $attendancePercentage = $this->calculateAttendancePercentage($student->id, $dateFrom, $dateTo);
            $averageGrade = Grade::where('student_id', $student->id)
                ->whereBetween('exam_date', [$dateFrom, $dateTo])
                ->avg('percentage') ?? 0;

            $correlationData[] = [
                'student_name' => $student->full_name,
                'attendance_percentage' => $attendancePercentage,
                'average_grade' => round($averageGrade, 1)
            ];
        }

        return $correlationData;
    }

    /**
     * Calculate attendance percentage for a student
     */
    private function calculateAttendancePercentage($studentId, $dateFrom, $dateTo)
    {
        $totalDays = Attendance::where('student_id', $studentId)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->count();

        if ($totalDays === 0) return 0;

        $presentDays = Attendance::where('student_id', $studentId)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->where('status', 'present')
            ->count();

        return round(($presentDays / $totalDays) * 100, 1);
    }

    /**
     * Get communication statistics
     */
    private function getCommunicationStats($teacher, $dateFrom, $dateTo)
    {
        $totalMessages = Notification::where('title', 'LIKE', 'Message from ' . $teacher->full_name . '%')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        $readMessages = Notification::where('title', 'LIKE', 'Message from ' . $teacher->full_name . '%')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('is_read', true)
            ->count();

        $responseRate = $totalMessages > 0 ? round(($readMessages / $totalMessages) * 100, 1) : 0;

        return [
            'total_messages' => $totalMessages,
            'read_messages' => $readMessages,
            'response_rate' => $responseRate,
            'avg_messages_per_day' => round($totalMessages / max($dateFrom->diffInDays($dateTo), 1), 1)
        ];
    }

    /**
     * Calculate class effectiveness metrics
     */
    private function calculateClassEffectiveness($class, $dateFrom, $dateTo)
    {
        $students = $class->students()->where('status', 'active')->get();
        $totalStudents = $students->count();

        if ($totalStudents === 0) {
            return [
                'class_name' => $class->full_name,
                'total_students' => 0,
                'metrics' => []
            ];
        }

        // Calculate various effectiveness metrics
        $averageGrade = Grade::where('class_id', $class->id)
            ->whereBetween('exam_date', [$dateFrom, $dateTo])
            ->avg('percentage') ?? 0;

        $passingRate = Grade::where('class_id', $class->id)
            ->whereBetween('exam_date', [$dateFrom, $dateTo])
            ->where('percentage', '>=', 60)
            ->count() / max(Grade::where('class_id', $class->id)
                ->whereBetween('exam_date', [$dateFrom, $dateTo])
                ->count(), 1) * 100;

        $averageAttendance = $students->map(function($student) use ($dateFrom, $dateTo) {
            return $this->calculateAttendancePercentage($student->id, $dateFrom, $dateTo);
        })->avg();

        $improvingStudents = collect($this->getImprovementTracking($class->id, $dateFrom, $dateTo))
            ->where('improvement', '>', 0)
            ->count();

        return [
            'class_name' => $class->full_name,
            'total_students' => $totalStudents,
            'metrics' => [
                'average_grade' => round($averageGrade, 1),
                'passing_rate' => round($passingRate, 1),
                'average_attendance' => round($averageAttendance, 1),
                'improving_students' => $improvingStudents,
                'improvement_rate' => round(($improvingStudents / $totalStudents) * 100, 1)
            ]
        ];
    }

    /**
     * Calculate overall effectiveness across all classes
     */
    private function calculateOverallEffectiveness($classData)
    {
        if (empty($classData)) {
            return [
                'overall_grade' => 0,
                'overall_passing_rate' => 0,
                'overall_attendance' => 0,
                'overall_improvement_rate' => 0,
                'effectiveness_score' => 0
            ];
        }

        $totalStudents = array_sum(array_column($classData, 'total_students'));
        
        if ($totalStudents === 0) {
            return [
                'overall_grade' => 0,
                'overall_passing_rate' => 0,
                'overall_attendance' => 0,
                'overall_improvement_rate' => 0,
                'effectiveness_score' => 0
            ];
        }

        $weightedGrade = 0;
        $weightedPassingRate = 0;
        $weightedAttendance = 0;
        $weightedImprovementRate = 0;

        foreach ($classData as $class) {
            $weight = $class['total_students'] / $totalStudents;
            $metrics = $class['metrics'];
            
            $weightedGrade += $metrics['average_grade'] * $weight;
            $weightedPassingRate += $metrics['passing_rate'] * $weight;
            $weightedAttendance += $metrics['average_attendance'] * $weight;
            $weightedImprovementRate += $metrics['improvement_rate'] * $weight;
        }

        // Calculate effectiveness score (0-100)
        $effectivenessScore = (
            ($weightedGrade / 100) * 0.3 +
            ($weightedPassingRate / 100) * 0.25 +
            ($weightedAttendance / 100) * 0.25 +
            ($weightedImprovementRate / 100) * 0.2
        ) * 100;

        return [
            'overall_grade' => round($weightedGrade, 1),
            'overall_passing_rate' => round($weightedPassingRate, 1),
            'overall_attendance' => round($weightedAttendance, 1),
            'overall_improvement_rate' => round($weightedImprovementRate, 1),
            'effectiveness_score' => round($effectivenessScore, 1)
        ];
    }

    /**
     * Generate teaching recommendations based on effectiveness data
     */
    private function generateTeachingRecommendations($effectivenessData)
    {
        $recommendations = [];

        foreach ($effectivenessData as $class) {
            $metrics = $class['metrics'];
            $className = $class['class_name'];

            if ($metrics['average_grade'] < 70) {
                $recommendations[] = [
                    'type' => 'academic',
                    'priority' => 'high',
                    'class' => $className,
                    'message' => 'Consider reviewing teaching methods and providing additional support for struggling students.'
                ];
            }

            if ($metrics['average_attendance'] < 80) {
                $recommendations[] = [
                    'type' => 'attendance',
                    'priority' => 'medium',
                    'class' => $className,
                    'message' => 'Focus on improving student engagement and addressing attendance issues.'
                ];
            }

            if ($metrics['improvement_rate'] < 30) {
                $recommendations[] = [
                    'type' => 'improvement',
                    'priority' => 'medium',
                    'class' => $className,
                    'message' => 'Implement more personalized learning strategies to help students improve.'
                ];
            }

            if ($metrics['passing_rate'] < 60) {
                $recommendations[] = [
                    'type' => 'performance',
                    'priority' => 'high',
                    'class' => $className,
                    'message' => 'Review curriculum delivery and consider additional assessment methods.'
                ];
            }
        }

        return $recommendations;
    }

    // Export methods would be implemented here
    private function generateClassPerformanceExport($request) { /* Implementation */ }
    private function generateCommunicationExport($request) { /* Implementation */ }
    private function generateEffectivenessExport($request) { /* Implementation */ }
    private function exportToPdf($data, $type) { /* Implementation */ }
    private function exportToExcel($data, $type) { /* Implementation */ }
    private function exportToCsv($data, $type) { /* Implementation */ }
}