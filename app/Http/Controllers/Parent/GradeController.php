<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $parent = auth()->user()->parent;
        $children = $parent->students;

        $query = Grade::with(['student.user', 'subject', 'classModel'])
            ->whereIn('student_id', $children->pluck('id'));

        // Apply filters
        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

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
        $subjects = \App\Models\Subject::whereIn('class_id', $children->pluck('class_id'))
            ->where('is_active', true)
            ->distinct()
            ->get();

        // Get grade statistics for all children
        $gradeStats = [];
        foreach ($children as $child) {
            $stats = Grade::where('student_id', $child->id)
                ->selectRaw('
                    COUNT(*) as total_grades,
                    AVG(marks_obtained) as average_grade,
                    MAX(marks_obtained) as highest_grade,
                    MIN(marks_obtained) as lowest_grade
                ')
                ->first();

            $gradeStats[$child->id] = [
                'student_name' => $child->user->name,
                'total_grades' => $stats->total_grades ?? 0,
                'average_grade' => $stats->average_grade ? round($stats->average_grade, 2) : 0,
                'highest_grade' => $stats->highest_grade ?? 0,
                'lowest_grade' => $stats->lowest_grade ?? 0
            ];
        }

        // Get grades by subject for all children
        $gradesBySubject = Grade::with(['subject', 'student.user'])
            ->whereIn('student_id', $children->pluck('id'))
            ->selectRaw('subject_id, student_id, AVG(marks_obtained) as average_marks, COUNT(*) as total_exams')
            ->groupBy('subject_id', 'student_id')
            ->get();

        return view('parent.grades.index', compact('grades', 'children', 'subjects', 'gradeStats', 'gradesBySubject'));
    }

    public function getMonthlyData(Request $request)
    {
        $parent = auth()->user()->parent;
        $children = $parent->students;
        $year = $request->year ?? date('Y');

        $monthlyData = Grade::whereIn('student_id', $children->pluck('id'))
            ->selectRaw('MONTH(exam_date) as month, AVG(marks_obtained) as average_marks, COUNT(*) as total_exams')
            ->whereYear('exam_date', $year)
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
            'data' => $fullYearData
        ]);
    }

    public function getChildGradeStats(Request $request)
    {
        $parent = auth()->user()->parent;
        $student = \App\Models\Student::where('id', $request->student_id)
            ->whereIn('id', $parent->students->pluck('id'))
            ->firstOrFail();

        $stats = Grade::where('student_id', $student->id)
            ->selectRaw('
                COUNT(*) as total_grades,
                AVG(marks_obtained) as average_grade,
                MAX(marks_obtained) as highest_grade,
                MIN(marks_obtained) as lowest_grade
            ')
            ->first();

        // Get grades by subject
        $gradesBySubject = Grade::with('subject')
            ->where('student_id', $student->id)
            ->selectRaw('subject_id, AVG(marks_obtained) as average_marks, COUNT(*) as total_exams')
            ->groupBy('subject_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'student_name' => $student->user->name,
                'total_grades' => $stats->total_grades ?? 0,
                'average_grade' => $stats->average_grade ? round($stats->average_grade, 2) : 0,
                'highest_grade' => $stats->highest_grade ?? 0,
                'lowest_grade' => $stats->lowest_grade ?? 0,
                'grades_by_subject' => $gradesBySubject
            ]
        ]);
    }
}
