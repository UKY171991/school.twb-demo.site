<?php

namespace App\Http\Controllers\Ajax\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function select(Request $request)
    {
        try {
            $subjects = Subject::with(['school', 'teacher.user', 'classModel'])
                ->where('is_active', true)
                ->when($request->school_id, function($query, $schoolId) {
                    return $query->where('school_id', $schoolId);
                })
                ->when($request->class_id, function($query, $classId) {
                    return $query->where('class_id', $classId);
                })
                ->when($request->teacher_id, function($query, $teacherId) {
                    return $query->where('teacher_id', $teacherId);
                })
                ->when($request->search, function($query, $search) {
                    return $query->where('name', 'like', '%' . $search . '%')
                               ->orWhere('code', 'like', '%' . $search . '%');
                })
                ->limit(20)
                ->get()
                ->map(function($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'code' => $subject->code,
                        'teacher' => $subject->teacher->user->name ?? 'N/A',
                        'class' => $subject->classModel->name ?? 'N/A',
                        'credits' => $subject->credits
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subjects: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $subject = Subject::with(['teacher.user', 'classModel'])->findOrFail($request->subject_id);
            
            $stats = [
                'total_students' => \App\Models\Student::where('class_id', $subject->class_id)->count(),
                'total_grades' => \App\Models\Grade::where('subject_id', $subject->id)->count(),
                'average_grade' => \App\Models\Grade::where('subject_id', $subject->id)->avg('marks_obtained') ?? 0,
                'teacher_name' => $subject->teacher->user->name ?? 'N/A',
                'class_name' => $subject->classModel->name ?? 'N/A',
                'credits' => $subject->credits
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subject stats: ' . $e->getMessage()
            ], 500);
        }
    }
}
