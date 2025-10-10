<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $subjects = Subject::with(['school', 'classModel'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        return view('tc.subjects.index', compact('subjects'));
    }

    public function show(Subject $subject)
    {
        // Ensure teacher can only view their own subjects
        $teacher = auth()->user()->teacher;
        if ($subject->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this subject.');
        }

        $subject->load(['school', 'classModel']);
        
        // Get subject statistics
        $stats = [
            'total_students' => \App\Models\Student::where('class_id', $subject->class_id)
                ->where('is_active', true)
                ->count(),
            'total_grades' => \App\Models\Grade::where('subject_id', $subject->id)->count(),
            'average_grade' => \App\Models\Grade::where('subject_id', $subject->id)
                ->avg('marks_obtained'),
        ];

        // Get recent grades for this subject
        $recentGrades = \App\Models\Grade::with(['student.user'])
            ->where('subject_id', $subject->id)
            ->orderBy('exam_date', 'desc')
            ->limit(10)
            ->get();

        return view('tc.subjects.show', compact('subject', 'stats', 'recentGrades'));
    }

    public function getSubjectGrades(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $grades = \App\Models\Grade::with(['student.user'])
            ->where('subject_id', $subject->id)
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }
}
