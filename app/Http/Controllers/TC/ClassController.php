<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $classes = ClassModel::with(['school', 'students.user', 'subjects'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->withCount(['students' => function($query) {
                $query->where('status', 'active');
            }])
            ->get();

        // Get class statistics
        $stats = $this->getClassStats($teacher);

        return view('tc.classes.index', compact('classes', 'stats'));
    }

    public function show(ClassModel $class)
    {
        // Ensure teacher can only view their own classes
        $teacher = auth()->user()->teacher;
        if ($class->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this class.');
        }

        $class->load(['school', 'students.user', 'subjects']);
        
        // Get attendance statistics
        $attendanceStats = [
            'total_students' => $class->students->count(),
            'present_today' => \App\Models\Attendance::where('class_id', $class->id)
                ->whereDate('date', today())
                ->where('status', 'present')
                ->count(),
            'absent_today' => \App\Models\Attendance::where('class_id', $class->id)
                ->whereDate('date', today())
                ->where('status', 'absent')
                ->count(),
        ];

        return view('tc.classes.show', compact('class', 'attendanceStats'));
    }

    public function getClassStudents(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $students = Student::with('user')
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Get class statistics for teacher dashboard
     */
    private function getClassStats($teacher)
    {
        $totalClasses = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->count();

        $totalStudents = Student::whereIn('class_id', function($query) use ($teacher) {
                $query->select('id')
                      ->from('classes')
                      ->where('teacher_id', $teacher->id)
                      ->where('is_active', true);
            })
            ->where('status', 'active')
            ->count();

        $todayAttendance = \App\Models\Attendance::whereIn('class_id', function($query) use ($teacher) {
                $query->select('id')
                      ->from('classes')
                      ->where('teacher_id', $teacher->id)
                      ->where('is_active', true);
            })
            ->whereDate('date', today())
            ->count();

        $recentGrades = \App\Models\Grade::where('teacher_id', $teacher->id)
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        return [
            'total_classes' => $totalClasses,
            'total_students' => $totalStudents,
            'today_attendance' => $todayAttendance,
            'recent_grades' => $recentGrades,
        ];
    }

    /**
     * Get class roster with detailed student information
     */
    public function getRoster(ClassModel $class)
    {
        $teacher = auth()->user()->teacher;
        if ($class->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this class.');
        }

        $students = Student::with(['user', 'parent'])
            ->where('class_id', $class->id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get()
            ->map(function($student) {
                $attendanceStats = $student->getAttendanceStatistics();
                $gradeStats = $student->getGradeStatistics();
                
                return [
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'full_name' => $student->full_name,
                    'email' => $student->user->email ?? null,
                    'phone' => $student->phone,
                    'photo_url' => $student->photo_url,
                    'parent_name' => $student->parent->full_name ?? 'N/A',
                    'parent_phone' => $student->parent->phone ?? 'N/A',
                    'attendance_percentage' => $attendanceStats['attendance_percentage'],
                    'average_grade' => $gradeStats['average_grade'],
                    'status' => $student->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Send message to students
     */
    public function sendMessage(Request $request, ClassModel $class)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
            'send_to_all' => 'boolean'
        ]);

        $teacher = auth()->user()->teacher;
        if ($class->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this class.');
        }

        try {
            $students = collect();
            
            if ($request->send_to_all) {
                $students = Student::where('class_id', $class->id)
                    ->where('status', 'active')
                    ->get();
            } else {
                $students = Student::whereIn('id', $request->student_ids ?? [])
                    ->where('class_id', $class->id)
                    ->where('status', 'active')
                    ->get();
            }

            // Create notifications for students
            foreach ($students as $student) {
                if ($student->user) {
                    \App\Models\Notification::create([
                        'school_id' => $class->school_id,
                        'user_id' => $student->user->id,
                        'title' => 'Message from ' . $teacher->full_name,
                        'message' => $request->message,
                        'type' => 'info',
                        'is_read' => false,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Message sent to ' . $students->count() . ' students successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get class performance analytics
     */
    public function getPerformanceAnalytics(ClassModel $class)
    {
        $teacher = auth()->user()->teacher;
        if ($class->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this class.');
        }

        // Attendance analytics
        $attendanceData = \App\Models\Attendance::where('class_id', $class->id)
            ->whereBetween('date', [now()->subMonth(), now()])
            ->selectRaw('DATE(date) as date, status, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // Grade analytics
        $gradeData = \App\Models\Grade::where('class_id', $class->id)
            ->whereBetween('exam_date', [now()->subMonth(), now()])
            ->selectRaw('
                AVG((marks_obtained / total_marks) * 100) as avg_percentage,
                COUNT(*) as total_grades,
                SUM(CASE WHEN (marks_obtained / total_marks) * 100 >= 60 THEN 1 ELSE 0 END) as passing_grades
            ')
            ->first();

        // Student performance summary
        $studentPerformance = Student::where('class_id', $class->id)
            ->where('status', 'active')
            ->with(['attendance' => function($query) {
                $query->where('date', '>=', now()->subMonth());
            }, 'grades' => function($query) {
                $query->where('exam_date', '>=', now()->subMonth());
            }])
            ->get()
            ->map(function($student) {
                $attendanceStats = $student->getAttendanceStatistics();
                $gradeStats = $student->getGradeStatistics();
                
                return [
                    'student_name' => $student->full_name,
                    'attendance_percentage' => $attendanceStats['attendance_percentage'],
                    'average_grade' => $gradeStats['average_grade'],
                    'needs_attention' => $attendanceStats['attendance_percentage'] < 75 || $gradeStats['average_grade'] < 60,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'attendance_trends' => $attendanceData,
                'grade_summary' => $gradeData,
                'student_performance' => $studentPerformance,
                'class_info' => [
                    'name' => $class->full_name,
                    'total_students' => $class->students()->where('status', 'active')->count(),
                    'capacity' => $class->capacity,
                ]
            ]
        ]);
    }

    /**
     * Create lesson plan
     */
    public function createLessonPlan(Request $request, ClassModel $class)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'objectives' => 'required|string',
            'materials' => 'nullable|string',
            'activities' => 'required|string',
            'assessment' => 'nullable|string',
            'homework' => 'nullable|string',
            'lesson_date' => 'required|date',
            'duration' => 'required|integer|min:1|max:300', // minutes
        ]);

        $teacher = auth()->user()->teacher;
        if ($class->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this class.');
        }

        try {
            // For now, we'll store lesson plans as notifications
            // In a full implementation, you'd create a LessonPlan model
            \App\Models\Notification::create([
                'school_id' => $class->school_id,
                'user_id' => $teacher->user_id,
                'title' => 'Lesson Plan: ' . $request->title,
                'message' => json_encode([
                    'type' => 'lesson_plan',
                    'class_id' => $class->id,
                    'subject_id' => $request->subject_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'objectives' => $request->objectives,
                    'materials' => $request->materials,
                    'activities' => $request->activities,
                    'assessment' => $request->assessment,
                    'homework' => $request->homework,
                    'lesson_date' => $request->lesson_date,
                    'duration' => $request->duration,
                ]),
                'type' => 'info',
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lesson plan created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create lesson plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lesson plans for class
     */
    public function getLessonPlans(ClassModel $class)
    {
        $teacher = auth()->user()->teacher;
        if ($class->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this class.');
        }

        // Get lesson plans from notifications (in a real app, you'd have a LessonPlan model)
        $lessonPlans = \App\Models\Notification::where('user_id', $teacher->user_id)
            ->where('title', 'LIKE', 'Lesson Plan:%')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($notification) {
                $data = json_decode($notification->message, true);
                if (is_array($data) && isset($data['type']) && $data['type'] === 'lesson_plan') {
                    return $data;
                }
                return null;
            })
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $lessonPlans
        ]);
    }
}
