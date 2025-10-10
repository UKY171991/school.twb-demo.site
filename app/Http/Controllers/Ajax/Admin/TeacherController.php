<?php

namespace App\Http\Controllers\Ajax\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function select(Request $request)
    {
        try {
            $teachers = Teacher::with('user')
                ->where('is_active', true)
                ->when($request->school_id, function($query, $schoolId) {
                    return $query->where('school_id', $schoolId);
                })
                ->when($request->search, function($query, $search) {
                    return $query->whereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                })
                ->limit(20)
                ->get()
                ->map(function($teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->user->name,
                        'email' => $teacher->user->email,
                        'employee_id' => $teacher->employee_id,
                        'specialization' => $teacher->subject_specialization
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $teachers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch teachers: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $teacher = Teacher::with('user')->findOrFail($request->teacher_id);
            
            $stats = [
                'total_classes' => \App\Models\ClassModel::where('teacher_id', $teacher->id)->count(),
                'total_students' => \App\Models\Student::whereHas('classModel', function($query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                })->count(),
                'total_subjects' => \App\Models\Subject::where('teacher_id', $teacher->id)->count(),
                'total_grades_recorded' => \App\Models\Grade::whereHas('subject', function($query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                })->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch teacher stats: ' . $e->getMessage()
            ], 500);
        }
    }
}
