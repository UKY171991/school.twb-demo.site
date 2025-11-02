<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $subjectIds = Subject::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        // Get statistics
        $stats = $this->getGradeStats($teacher);

        $grades = Grade::with(['student.user', 'subject', 'class'])
            ->whereIn('subject_id', $subjectIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tc.grades.index', compact('grades', 'stats'));
    }

    public function create()
    {
        $teacher = auth()->user()->teacher;
        $subjects = Subject::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $students = Student::with('user')
            ->whereIn('class_id', $classIds)
            ->where('is_active', true)
            ->get();

        $classes = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        return view('tc.grades.create', compact('subjects', 'students', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'exam_type' => 'required|in:quiz,midterm,final,assignment,project',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'grade' => 'required|string|max:5',
            'remarks' => 'nullable|string|max:255',
            'exam_date' => 'required|date'
        ]);

        // Verify teacher has access to this subject
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $grade = Grade::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade recorded successfully',
                'data' => $grade->load(['student.user', 'subject', 'classModel'])
            ]);
        }

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Grade recorded successfully.');
    }

    public function show(Grade $grade)
    {
        // Verify teacher has access to this grade
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $grade->subject_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$subject) {
            abort(403, 'Unauthorized access to this grade.');
        }

        $grade->load(['student.user', 'subject', 'classModel']);
        return view('tc.grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        // Verify teacher has access to this grade
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $grade->subject_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$subject) {
            abort(403, 'Unauthorized access to this grade.');
        }

        $subjects = Subject::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $students = Student::with('user')
            ->whereIn('class_id', $classIds)
            ->where('is_active', true)
            ->get();

        $classes = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        return view('tc.grades.edit', compact('grade', 'subjects', 'students', 'classes'));
    }

    public function update(Request $request, Grade $grade)
    {
        // Verify teacher has access to this grade
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $grade->subject_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$subject) {
            abort(403, 'Unauthorized access to this grade.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'exam_type' => 'required|in:quiz,midterm,final,assignment,project',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'grade' => 'required|string|max:5',
            'remarks' => 'nullable|string|max:255',
            'exam_date' => 'required|date'
        ]);

        $grade->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade updated successfully',
                'data' => $grade->load(['student.user', 'subject', 'classModel'])
            ]);
        }

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        // Verify teacher has access to this grade
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $grade->subject_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$subject) {
            abort(403, 'Unauthorized access to this grade.');
        }

        $grade->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade deleted successfully'
            ]);
        }

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Grade deleted successfully.');
    }

    public function getStudentGrades(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $grades = Grade::with(['student.user'])
            ->where('student_id', $request->student_id)
            ->where('subject_id', $subject->id)
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    public function getClassGrades(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $grades = Grade::with(['student.user'])
            ->where('class_id', $request->class_id)
            ->where('subject_id', $subject->id)
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    /**
     * Get grade statistics for teacher dashboard
     */
    private function getGradeStats($teacher)
    {
        $subjectIds = Subject::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $totalGrades = Grade::whereIn('subject_id', $subjectIds)->count();
        $recentGrades = Grade::whereIn('subject_id', $subjectIds)
            ->where('created_at', '>=', now()->subWeek())
            ->count();
        
        $averageGrade = Grade::whereIn('subject_id', $subjectIds)
            ->selectRaw('AVG((marks_obtained / total_marks) * 100) as avg_percentage')
            ->value('avg_percentage');

        $passingGrades = Grade::whereIn('subject_id', $subjectIds)
            ->whereRaw('(marks_obtained / total_marks) * 100 >= 60')
            ->count();

        $passRate = $totalGrades > 0 ? round(($passingGrades / $totalGrades) * 100, 2) : 0;

        return [
            'total_grades' => $totalGrades,
            'recent_grades' => $recentGrades,
            'average_grade' => round($averageGrade ?? 0, 2),
            'pass_rate' => $passRate,
        ];
    }

    /**
     * Get grade data for AJAX requests
     */
    public function getGradeData(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $subjectIds = Subject::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $query = Grade::with(['student.user', 'subject', 'class'])
            ->whereIn('subject_id', $subjectIds);

        // Apply filters
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('exam_date', [$request->start_date, $request->end_date]);
        }

        $grades = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $grades->map(function($grade) {
                return [
                    'id' => $grade->id,
                    'student_name' => $grade->student->full_name ?? 'Unknown',
                    'subject_name' => $grade->subject->name ?? 'Unknown',
                    'class_name' => $grade->class->full_name ?? 'Unknown',
                    'exam_type' => $grade->exam_type,
                    'marks_obtained' => $grade->marks_obtained,
                    'total_marks' => $grade->total_marks,
                    'percentage' => $grade->calculated_percentage,
                    'grade_letter' => $grade->grade_letter,
                    'exam_date' => $grade->exam_date?->format('Y-m-d'),
                    'created_at' => $grade->created_at->format('Y-m-d H:i'),
                ];
            })
        ]);
    }

    /**
     * Get students for grade entry
     */
    public function getStudentsForGrading(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $teacher = auth()->user()->teacher;
        
        // Verify teacher has access to this class and subject
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        // Get students in the class
        $students = Student::with('user')
            ->where('class_id', $class->id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'full_name' => $student->full_name,
                    'photo_url' => $student->photo_url,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'students' => $students,
                'class' => [
                    'id' => $class->id,
                    'name' => $class->full_name,
                ],
                'subject' => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                ]
            ]
        ]);
    }

    /**
     * Bulk grade entry
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_type' => 'required|in:quiz,midterm,final,assignment,project',
            'exam_date' => 'required|date',
            'total_marks' => 'required|numeric|min:1',
            'grades_data' => 'required|array',
            'grades_data.*.student_id' => 'required|exists:students,id',
            'grades_data.*.marks_obtained' => 'required|numeric|min:0',
            'grades_data.*.remarks' => 'nullable|string|max:255'
        ]);

        $teacher = auth()->user()->teacher;
        
        // Verify teacher has access to this class and subject
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        try {
            $createdGrades = [];
            
            foreach ($request->grades_data as $gradeData) {
                $percentage = ($gradeData['marks_obtained'] / $request->total_marks) * 100;
                
                $grade = Grade::create([
                    'school_id' => $class->school_id,
                    'student_id' => $gradeData['student_id'],
                    'subject_id' => $request->subject_id,
                    'class_id' => $request->class_id,
                    'teacher_id' => $teacher->id,
                    'exam_type' => $request->exam_type,
                    'marks_obtained' => $gradeData['marks_obtained'],
                    'total_marks' => $request->total_marks,
                    'percentage' => round($percentage, 2),
                    'grade_letter' => $this->calculateGradeLetter($percentage),
                    'remarks' => $gradeData['remarks'] ?? null,
                    'exam_date' => $request->exam_date,
                ]);
                
                $createdGrades[] = $grade;
            }

            return response()->json([
                'success' => true,
                'message' => 'Grades recorded successfully',
                'data' => $createdGrades
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record grades: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate grade letter from percentage
     */
    private function calculateGradeLetter(float $percentage): string
    {
        return match(true) {
            $percentage >= 90 => 'A+',
            $percentage >= 85 => 'A',
            $percentage >= 80 => 'A-',
            $percentage >= 75 => 'B+',
            $percentage >= 70 => 'B',
            $percentage >= 65 => 'B-',
            $percentage >= 60 => 'C+',
            $percentage >= 55 => 'C',
            $percentage >= 50 => 'C-',
            $percentage >= 45 => 'D',
            default => 'F'
        };
    }

    /**
     * Get grade analytics
     */
    public function getAnalytics(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $subjectIds = Subject::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::now()->subMonth();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : \Carbon\Carbon::now();

        // Grade distribution
        $gradeDistribution = Grade::whereIn('subject_id', $subjectIds)
            ->whereBetween('exam_date', [$startDate, $endDate])
            ->selectRaw('
                SUM(CASE WHEN (marks_obtained / total_marks) * 100 >= 90 THEN 1 ELSE 0 END) as a_grades,
                SUM(CASE WHEN (marks_obtained / total_marks) * 100 >= 80 AND (marks_obtained / total_marks) * 100 < 90 THEN 1 ELSE 0 END) as b_grades,
                SUM(CASE WHEN (marks_obtained / total_marks) * 100 >= 70 AND (marks_obtained / total_marks) * 100 < 80 THEN 1 ELSE 0 END) as c_grades,
                SUM(CASE WHEN (marks_obtained / total_marks) * 100 >= 60 AND (marks_obtained / total_marks) * 100 < 70 THEN 1 ELSE 0 END) as d_grades,
                SUM(CASE WHEN (marks_obtained / total_marks) * 100 < 60 THEN 1 ELSE 0 END) as f_grades
            ')
            ->first();

        // Subject-wise performance
        $subjectPerformance = Grade::with('subject')
            ->whereIn('subject_id', $subjectIds)
            ->whereBetween('exam_date', [$startDate, $endDate])
            ->selectRaw('subject_id, AVG((marks_obtained / total_marks) * 100) as avg_percentage, COUNT(*) as total_grades')
            ->groupBy('subject_id')
            ->get();

        // Class-wise performance
        $classPerformance = Grade::with('class')
            ->whereIn('subject_id', $subjectIds)
            ->whereBetween('exam_date', [$startDate, $endDate])
            ->selectRaw('class_id, AVG((marks_obtained / total_marks) * 100) as avg_percentage, COUNT(*) as total_grades')
            ->groupBy('class_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'grade_distribution' => $gradeDistribution,
                'subject_performance' => $subjectPerformance,
                'class_performance' => $classPerformance,
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ]
            ]
        ]);
    }
}
