<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use Barryvdh\DomPDF\Facade\Pdf;
// use Maatwebsite\Excel\Facades\Excel;

class ReportController extends BaseController
{
    /**
     * Display the reports dashboard
     */
    public function index(): View
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Academic Reports',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Reports', 'url' => null]
            ],
            'statistics' => $this->getReportStatistics(),
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'academicYears' => AcademicYear::where('school_id', $this->getCurrentSchoolId())
                                         ->where('is_active', true)
                                         ->orderBy('start_date', 'desc')
                                         ->get()
        ];

        return view('admin.reports.index', $data);
    }

    /**
     * Generate student academic report
     */
    public function students(Request $request): View|JsonResponse
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'class_id' => 'nullable|exists:class_models,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:html,pdf,excel'
        ]);

        $query = Student::where('school_id', $this->getCurrentSchoolId())
                       ->where('status', 'active')
                       ->with(['user', 'class', 'grades', 'attendance']);

        // Apply filters
        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->date_from && $request->date_to) {
            $query->whereHas('grades', function($q) use ($request) {
                $q->whereBetween('exam_date', [$request->date_from, $request->date_to]);
            });
        }

        $students = $query->get();

        // Calculate academic performance for each student
        $studentsData = $students->map(function($student) use ($request) {
            $grades = $student->grades();
            $attendance = $student->attendance();

            // Apply date filters to grades and attendance
            if ($request->date_from && $request->date_to) {
                $grades = $grades->whereBetween('exam_date', [$request->date_from, $request->date_to]);
                $attendance = $attendance->whereBetween('date', [$request->date_from, $request->date_to]);
            }

            $gradeData = $grades->get();
            $attendanceData = $attendance->get();

            return [
                'student' => $student,
                'academic_performance' => [
                    'total_grades' => $gradeData->count(),
                    'average_grade' => $gradeData->avg('marks_obtained') ?? 0,
                    'highest_grade' => $gradeData->max('marks_obtained') ?? 0,
                    'lowest_grade' => $gradeData->min('marks_obtained') ?? 0,
                    'pass_rate' => $gradeData->count() > 0 ? 
                        ($gradeData->where('marks_obtained', '>=', 60)->count() / $gradeData->count()) * 100 : 0,
                    'grade_distribution' => $this->getGradeDistribution($gradeData)
                ],
                'attendance_summary' => [
                    'total_days' => $attendanceData->count(),
                    'present_days' => $attendanceData->where('status', 'present')->count(),
                    'absent_days' => $attendanceData->where('status', 'absent')->count(),
                    'late_days' => $attendanceData->where('status', 'late')->count(),
                    'attendance_rate' => $attendanceData->count() > 0 ? 
                        ($attendanceData->where('status', 'present')->count() / $attendanceData->count()) * 100 : 0
                ]
            ];
        });

        $data = [
            'page_title' => 'Student Academic Report',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Reports', 'url' => route('admin.reports.index')],
                ['title' => 'Student Reports', 'url' => null]
            ],
            'students_data' => $studentsData,
            'filters' => $request->all(),
            'report_date' => now(),
            'school' => $this->getCurrentSchool()
        ];

        // Handle different output formats
        if ($request->format === 'pdf') {
            // PDF generation would be implemented here
            // $pdf = Pdf::loadView('admin.reports.students-pdf', $data);
            // return $pdf->download('student-academic-report-' . now()->format('Y-m-d') . '.pdf');
            return response()->json(['message' => 'PDF export not yet implemented'], 501);
        }

        if ($request->format === 'excel') {
            // Excel export would be implemented here
            // return Excel::download(new StudentsReportExport($studentsData), 
            //     'student-academic-report-' . now()->format('Y-m-d') . '.xlsx');
            return response()->json(['message' => 'Excel export not yet implemented'], 501);
        }

        return view('admin.reports.students', $data);
    }

    /**
     * Generate teacher performance report
     */
    public function teachers(Request $request): View|JsonResponse
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:html,pdf,excel'
        ]);

        $query = Teacher::where('school_id', $this->getCurrentSchoolId())
                       ->where('is_active', true)
                       ->with(['user', 'subjects', 'classes']);

        if ($request->teacher_id) {
            $query->where('id', $request->teacher_id);
        }

        $teachers = $query->get();

        // Calculate performance metrics for each teacher
        $teachersData = $teachers->map(function($teacher) use ($request) {
            $classes = $teacher->classes();
            $subjects = $teacher->subjects();

            // Get students taught by this teacher
            $studentIds = $classes->get()->pluck('students')->flatten()->pluck('id')->unique();
            
            $grades = Grade::whereIn('student_id', $studentIds)
                          ->whereIn('subject_id', $subjects->pluck('id'));
            
            $attendance = Attendance::whereIn('student_id', $studentIds);

            // Apply date filters
            if ($request->date_from && $request->date_to) {
                $grades = $grades->whereBetween('exam_date', [$request->date_from, $request->date_to]);
                $attendance = $attendance->whereBetween('date', [$request->date_from, $request->date_to]);
            }

            $gradeData = $grades->get();
            $attendanceData = $attendance->get();

            return [
                'teacher' => $teacher,
                'workload' => [
                    'total_classes' => $classes->count(),
                    'total_subjects' => $subjects->count(),
                    'total_students' => $studentIds->count(),
                    'active_schedules' => $teacher->schedules()->where('is_active', true)->count()
                ],
                'student_performance' => [
                    'total_grades_entered' => $gradeData->count(),
                    'average_class_performance' => $gradeData->avg('marks_obtained') ?? 0,
                    'pass_rate' => $gradeData->count() > 0 ? 
                        ($gradeData->where('marks_obtained', '>=', 60)->count() / $gradeData->count()) * 100 : 0,
                    'grade_distribution' => $this->getGradeDistribution($gradeData)
                ],
                'attendance_tracking' => [
                    'total_attendance_records' => $attendanceData->count(),
                    'class_attendance_rate' => $attendanceData->count() > 0 ? 
                        ($attendanceData->where('status', 'present')->count() / $attendanceData->count()) * 100 : 0
                ]
            ];
        });

        $data = [
            'page_title' => 'Teacher Performance Report',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Reports', 'url' => route('admin.reports.index')],
                ['title' => 'Teacher Reports', 'url' => null]
            ],
            'teachers_data' => $teachersData,
            'filters' => $request->all(),
            'report_date' => now(),
            'school' => $this->getCurrentSchool()
        ];

        // Handle different output formats
        if ($request->format === 'pdf') {
            // PDF generation would be implemented here
            return response()->json(['message' => 'PDF export not yet implemented'], 501);
        }

        return view('admin.reports.teachers', $data);
    }

    /**
     * Generate attendance report
     */
    public function attendance(Request $request): View|JsonResponse
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'class_id' => 'nullable|exists:class_models,id',
            'student_id' => 'nullable|exists:students,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'format' => 'nullable|in:html,pdf,excel'
        ]);

        $query = Attendance::whereHas('student', function($q) {
                               $q->where('school_id', $this->getCurrentSchoolId());
                           })
                           ->whereBetween('date', [$request->date_from, $request->date_to])
                           ->with(['student.user', 'student.class']);

        // Apply filters
        if ($request->class_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        $attendanceRecords = $query->get();

        // Group by student and calculate statistics
        $attendanceData = $attendanceRecords->groupBy('student_id')->map(function($records, $studentId) {
            $student = $records->first()->student;
            $totalDays = $records->count();
            $presentDays = $records->where('status', 'present')->count();
            $absentDays = $records->where('status', 'absent')->count();
            $lateDays = $records->where('status', 'late')->count();

            return [
                'student' => $student,
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'late_days' => $lateDays,
                'attendance_rate' => $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0,
                'records' => $records->sortBy('date')
            ];
        });

        // Calculate overall statistics
        $overallStats = [
            'total_students' => $attendanceData->count(),
            'total_records' => $attendanceRecords->count(),
            'overall_attendance_rate' => $attendanceRecords->count() > 0 ? 
                ($attendanceRecords->where('status', 'present')->count() / $attendanceRecords->count()) * 100 : 0,
            'average_daily_attendance' => $attendanceData->avg('attendance_rate')
        ];

        $data = [
            'page_title' => 'Attendance Report',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Reports', 'url' => route('admin.reports.index')],
                ['title' => 'Attendance Reports', 'url' => null]
            ],
            'attendance_data' => $attendanceData,
            'overall_stats' => $overallStats,
            'filters' => $request->all(),
            'report_date' => now(),
            'school' => $this->getCurrentSchool()
        ];

        // Handle different output formats
        if ($request->format === 'pdf') {
            // PDF generation would be implemented here
            return response()->json(['message' => 'PDF export not yet implemented'], 501);
        }

        return view('admin.reports.attendance', $data);
    }

    /**
     * Generate grades report
     */
    public function grades(Request $request): View|JsonResponse
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'class_id' => 'nullable|exists:class_models,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'student_id' => 'nullable|exists:students,id',
            'exam_type' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:html,pdf,excel'
        ]);

        $query = Grade::whereHas('student', function($q) {
                          $q->where('school_id', $this->getCurrentSchoolId());
                      })
                      ->with(['student.user', 'student.class', 'subject']);

        // Apply filters
        if ($request->class_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('exam_date', [$request->date_from, $request->date_to]);
        }

        $grades = $query->get();

        // Group by student and calculate statistics
        $gradesData = $grades->groupBy('student_id')->map(function($studentGrades, $studentId) {
            $student = $studentGrades->first()->student;
            $totalGrades = $studentGrades->count();
            $averageGrade = $studentGrades->avg('marks_obtained');
            $highestGrade = $studentGrades->max('marks_obtained');
            $lowestGrade = $studentGrades->min('marks_obtained');
            $passCount = $studentGrades->where('marks_obtained', '>=', 60)->count();

            return [
                'student' => $student,
                'total_grades' => $totalGrades,
                'average_grade' => round($averageGrade, 2),
                'highest_grade' => $highestGrade,
                'lowest_grade' => $lowestGrade,
                'pass_rate' => $totalGrades > 0 ? ($passCount / $totalGrades) * 100 : 0,
                'grade_distribution' => $this->getGradeDistribution($studentGrades),
                'subject_performance' => $studentGrades->groupBy('subject_id')->map(function($subjectGrades) {
                    return [
                        'subject' => $subjectGrades->first()->subject,
                        'average' => round($subjectGrades->avg('marks_obtained'), 2),
                        'count' => $subjectGrades->count()
                    ];
                }),
                'grades' => $studentGrades->sortByDesc('exam_date')
            ];
        });

        // Calculate overall statistics
        $overallStats = [
            'total_students' => $gradesData->count(),
            'total_grades' => $grades->count(),
            'overall_average' => round($grades->avg('marks_obtained'), 2),
            'overall_pass_rate' => $grades->count() > 0 ? 
                ($grades->where('marks_obtained', '>=', 60)->count() / $grades->count()) * 100 : 0,
            'grade_distribution' => $this->getGradeDistribution($grades)
        ];

        $data = [
            'page_title' => 'Grades Report',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Reports', 'url' => route('admin.reports.index')],
                ['title' => 'Grades Reports', 'url' => null]
            ],
            'grades_data' => $gradesData,
            'overall_stats' => $overallStats,
            'filters' => $request->all(),
            'report_date' => now(),
            'school' => $this->getCurrentSchool()
        ];

        // Handle different output formats
        if ($request->format === 'pdf') {
            // PDF generation would be implemented here
            return response()->json(['message' => 'PDF export not yet implemented'], 501);
        }

        return view('admin.reports.grades', $data);
    }

    /**
     * Generate class performance analytics
     */
    public function classPerformance(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester' => 'nullable|string'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            $classes = ClassModel::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->with(['students', 'subjects'])
                                ->get();

            $classPerformance = $classes->map(function($class) use ($request) {
                $studentIds = $class->students->pluck('id');
                
                $gradesQuery = Grade::whereIn('student_id', $studentIds);
                $attendanceQuery = Attendance::whereIn('student_id', $studentIds);

                // Apply academic year filter if provided
                if ($request->academic_year_id) {
                    $gradesQuery->where('academic_year_id', $request->academic_year_id);
                    $attendanceQuery->where('academic_year_id', $request->academic_year_id);
                }

                $grades = $gradesQuery->get();
                $attendance = $attendanceQuery->get();

                return [
                    'class' => $class,
                    'student_count' => $class->students->count(),
                    'academic_performance' => [
                        'average_grade' => round($grades->avg('marks_obtained') ?? 0, 2),
                        'pass_rate' => $grades->count() > 0 ? 
                            ($grades->where('marks_obtained', '>=', 60)->count() / $grades->count()) * 100 : 0,
                        'total_assessments' => $grades->count()
                    ],
                    'attendance_rate' => $attendance->count() > 0 ? 
                        ($attendance->where('status', 'present')->count() / $attendance->count()) * 100 : 0,
                    'subject_performance' => $class->subjects->map(function($subject) use ($studentIds, $request) {
                        $subjectGrades = Grade::whereIn('student_id', $studentIds)
                                            ->where('subject_id', $subject->id);
                        
                        if ($request->academic_year_id) {
                            $subjectGrades->where('academic_year_id', $request->academic_year_id);
                        }
                        
                        $grades = $subjectGrades->get();
                        
                        return [
                            'subject' => $subject,
                            'average_grade' => round($grades->avg('marks_obtained') ?? 0, 2),
                            'total_assessments' => $grades->count()
                        ];
                    })
                ];
            });

            return [
                'class_performance' => $classPerformance,
                'comparison_data' => $this->getClassComparisonData($classPerformance)
            ];
        });
    }

    /**
     * Send progress reports to parents
     */
    public function sendProgressReports(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'class_id' => 'nullable|exists:class_models,id',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
            'report_type' => 'required|in:academic,attendance,comprehensive',
            'message' => 'nullable|string|max:1000'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            $query = Student::where('school_id', $this->getCurrentSchoolId())
                           ->where('status', 'active')
                           ->with(['user', 'parents.user', 'class']);

            // Apply filters
            if ($request->class_id) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->student_ids) {
                $query->whereIn('id', $request->student_ids);
            }

            $students = $query->get();
            $sentCount = 0;

            foreach ($students as $student) {
                if ($student->parents->isNotEmpty()) {
                    // Generate progress report data
                    $reportData = $this->generateProgressReportData($student, $request->report_type);
                    
                    // Send notification to each parent
                    foreach ($student->parents as $parent) {
                        $this->sendProgressReportNotification($parent, $student, $reportData, $request->message);
                        $sentCount++;
                    }
                }
            }

            return [
                'message' => "Progress reports sent successfully to {$sentCount} parents",
                'sent_count' => $sentCount,
                'student_count' => $students->count()
            ];
        });
    }

    /**
     * Get report statistics
     */
    private function getReportStatistics(): array
    {
        $schoolId = $this->getCurrentSchoolId();
        
        $totalStudents = Student::where('school_id', $schoolId)->where('status', 'active')->count();
        $totalTeachers = Teacher::where('school_id', $schoolId)->where('is_active', true)->count();
        $totalClasses = ClassModel::where('school_id', $schoolId)->where('is_active', true)->count();
        
        // Recent activity
        $recentGrades = Grade::whereHas('student', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->where('created_at', '>=', now()->subDays(7))->count();
        
        $recentAttendance = Attendance::whereHas('student', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->where('date', '>=', now()->subDays(7))->count();

        return [
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'total_classes' => $totalClasses,
            'recent_grades' => $recentGrades,
            'recent_attendance' => $recentAttendance
        ];
    }

    /**
     * Get grade distribution
     */
    private function getGradeDistribution($grades): array
    {
        return [
            'A (90-100)' => $grades->whereBetween('marks_obtained', [90, 100])->count(),
            'B (80-89)' => $grades->whereBetween('marks_obtained', [80, 89])->count(),
            'C (70-79)' => $grades->whereBetween('marks_obtained', [70, 79])->count(),
            'D (60-69)' => $grades->whereBetween('marks_obtained', [60, 69])->count(),
            'F (0-59)' => $grades->where('marks_obtained', '<', 60)->count()
        ];
    }

    /**
     * Get class comparison data
     */
    private function getClassComparisonData($classPerformance): array
    {
        $averageGrades = $classPerformance->pluck('academic_performance.average_grade');
        $passRates = $classPerformance->pluck('academic_performance.pass_rate');
        $attendanceRates = $classPerformance->pluck('attendance_rate');

        return [
            'best_performing_class' => $classPerformance->sortByDesc('academic_performance.average_grade')->first(),
            'highest_attendance_class' => $classPerformance->sortByDesc('attendance_rate')->first(),
            'school_averages' => [
                'average_grade' => round($averageGrades->avg(), 2),
                'pass_rate' => round($passRates->avg(), 2),
                'attendance_rate' => round($attendanceRates->avg(), 2)
            ]
        ];
    }

    /**
     * Generate progress report data for a student
     */
    private function generateProgressReportData(Student $student, string $reportType): array
    {
        $data = [
            'student' => $student,
            'report_type' => $reportType,
            'generated_at' => now()
        ];

        if (in_array($reportType, ['academic', 'comprehensive'])) {
            $grades = $student->grades()->with('subject')->get();
            $data['academic_performance'] = [
                'total_grades' => $grades->count(),
                'average_grade' => round($grades->avg('marks_obtained') ?? 0, 2),
                'subject_performance' => $grades->groupBy('subject_id')->map(function($subjectGrades) {
                    return [
                        'subject' => $subjectGrades->first()->subject,
                        'average' => round($subjectGrades->avg('marks_obtained'), 2),
                        'latest_grade' => $subjectGrades->sortByDesc('exam_date')->first()
                    ];
                })
            ];
        }

        if (in_array($reportType, ['attendance', 'comprehensive'])) {
            $attendance = $student->attendance()->where('date', '>=', now()->subMonth())->get();
            $data['attendance_summary'] = [
                'total_days' => $attendance->count(),
                'present_days' => $attendance->where('status', 'present')->count(),
                'absent_days' => $attendance->where('status', 'absent')->count(),
                'attendance_rate' => $attendance->count() > 0 ? 
                    ($attendance->where('status', 'present')->count() / $attendance->count()) * 100 : 0
            ];
        }

        return $data;
    }

    /**
     * Send progress report notification to parent
     */
    private function sendProgressReportNotification($parent, $student, $reportData, $customMessage = null): void
    {
        // Create notification record
        $notificationData = [
            'school_id' => $this->getCurrentSchoolId(),
            'title' => "Progress Report for {$student->user->name}",
            'message' => $customMessage ?? "Academic progress report for {$student->user->name} is now available.",
            'type' => 'info',
            'data' => $reportData
        ];

        // Here you would typically send email, SMS, or in-app notification
        // For now, we'll just create a system notification
        $parent->user->notifications()->create($notificationData);
    }

    /**
     * Get current school
     */
    protected function getCurrentSchool(): ?\App\Models\School
    {
        return School::find($this->getCurrentSchoolId());
    }
}