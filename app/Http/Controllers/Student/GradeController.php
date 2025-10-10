<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;
        
        $query = Grade::with(['subject', 'classModel'])
            ->where('student_id', $student->id);

        // Apply filters
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

        // Get all subjects for filter dropdown
        $subjects = \App\Models\Subject::where('class_id', $student->class_id)
            ->where('is_active', true)
            ->get();

        // Get grade statistics
        $stats = [
            'total_grades' => Grade::where('student_id', $student->id)->count(),
            'average_grade' => Grade::where('student_id', $student->id)->avg('marks_obtained'),
            'highest_grade' => Grade::where('student_id', $student->id)->max('marks_obtained'),
            'lowest_grade' => Grade::where('student_id', $student->id)->min('marks_obtained'),
        ];

        // Get grades by subject
        $gradesBySubject = Grade::with('subject')
            ->where('student_id', $student->id)
            ->selectRaw('subject_id, AVG(marks_obtained) as average_marks, COUNT(*) as total_exams')
            ->groupBy('subject_id')
            ->get();

        return view('student.grades.index', compact('grades', 'subjects', 'stats', 'gradesBySubject'));
    }

    public function show(Grade $grade)
    {
        $student = auth()->user()->student;
        
        // Ensure student can only view their own grades
        if ($grade->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this grade.');
        }

        $grade->load(['subject', 'classModel']);
        return view('student.grades.show', compact('grade'));
    }

    public function getSubjectGrades(Request $request)
    {
        $student = auth()->user()->student;
        
        $grades = Grade::with(['subject'])
            ->where('student_id', $student->id)
            ->where('subject_id', $request->subject_id)
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    public function getGradeStatistics()
    {
        $student = auth()->user()->student;
        
        $stats = [
            'total_grades' => Grade::where('student_id', $student->id)->count(),
            'average_grade' => Grade::where('student_id', $student->id)->avg('marks_obtained'),
            'highest_grade' => Grade::where('student_id', $student->id)->max('marks_obtained'),
            'lowest_grade' => Grade::where('student_id', $student->id)->min('marks_obtained'),
        ];

        // Get monthly grade data for chart
        $monthlyData = Grade::where('student_id', $student->id)
            ->selectRaw('MONTH(exam_date) as month, AVG(marks_obtained) as average_marks, COUNT(*) as total_exams')
            ->whereYear('exam_date', $request->year ?? date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Fill missing months with zero data
        $fullYearData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyData->firstWhere('month', $i);
            $fullYearData[] = [
                'month' => $i,
                'average_marks' => $monthData ? round($monthData->average_marks, 2) : 0,
                'total_exams' => $monthData ? $monthData->total_exams : 0
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'monthly_data' => $fullYearData
            ]
        ]);
    }
}
