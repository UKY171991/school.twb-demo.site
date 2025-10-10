<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        $subjects = Subject::with(['teacher.user', 'classModel'])
            ->where('class_id', $student->class_id)
            ->where('is_active', true)
            ->get();

        // Get grade statistics for each subject
        $subjectStats = [];
        foreach ($subjects as $subject) {
            $grades = \App\Models\Grade::where('student_id', $student->id)
                ->where('subject_id', $subject->id)
                ->get();

            $subjectStats[$subject->id] = [
                'total_exams' => $grades->count(),
                'average_grade' => $grades->avg('marks_obtained'),
                'highest_grade' => $grades->max('marks_obtained'),
                'latest_grade' => $grades->sortByDesc('exam_date')->first(),
            ];
        }

        return view('student.subjects.index', compact('subjects', 'subjectStats'));
    }

    public function show(Subject $subject)
    {
        $student = auth()->user()->student;
        
        // Ensure student can only view subjects from their class
        if ($subject->class_id !== $student->class_id) {
            abort(403, 'Unauthorized access to this subject.');
        }

        $subject->load(['teacher.user', 'classModel']);
        
        // Get all grades for this subject
        $grades = \App\Models\Grade::where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->orderBy('exam_date', 'desc')
            ->get();

        // Get grade statistics
        $stats = [
            'total_exams' => $grades->count(),
            'average_grade' => $grades->avg('marks_obtained'),
            'highest_grade' => $grades->max('marks_obtained'),
            'lowest_grade' => $grades->min('marks_obtained'),
            'total_marks_obtained' => $grades->sum('marks_obtained'),
            'total_possible_marks' => $grades->sum('total_marks'),
        ];

        // Calculate percentage
        $stats['percentage'] = $stats['total_possible_marks'] > 0 
            ? round(($stats['total_marks_obtained'] / $stats['total_possible_marks']) * 100, 2) 
            : 0;

        // Get grades by exam type
        $gradesByType = $grades->groupBy('exam_type');

        return view('student.subjects.show', compact('subject', 'grades', 'stats', 'gradesByType'));
    }

    public function getSubjectDetails(Request $request)
    {
        $student = auth()->user()->student;
        
        $subject = Subject::with(['teacher.user'])
            ->where('id', $request->subject_id)
            ->where('class_id', $student->class_id)
            ->firstOrFail();

        // Get recent grades for this subject
        $recentGrades = \App\Models\Grade::where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->orderBy('exam_date', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'subject' => $subject,
                'recent_grades' => $recentGrades
            ]
        ]);
    }
}
