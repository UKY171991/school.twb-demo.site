<?php

namespace App\Http\Controllers\Ajax\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function select(Request $request)
    {
        try {
            $classes = ClassModel::with(['school', 'teacher.user'])
                ->where('is_active', true)
                ->when($request->school_id, function($query, $schoolId) {
                    return $query->where('school_id', $schoolId);
                })
                ->when($request->search, function($query, $search) {
                    return $query->where('name', 'like', '%' . $search . '%');
                })
                ->limit(20)
                ->get()
                ->map(function($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name,
                        'school' => $class->school->name ?? 'N/A',
                        'teacher' => $class->teacher->user->name ?? 'N/A',
                        'capacity' => $class->capacity
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch classes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudents(Request $request)
    {
        try {
            $class = ClassModel::findOrFail($request->class_id);
            
            $students = \App\Models\Student::with('user')
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->get()
                ->map(function($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->user->name,
                        'student_id' => $student->student_id,
                        'email' => $student->user->email
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch class students: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $class = ClassModel::with(['school', 'teacher.user'])->findOrFail($request->class_id);
            
            $stats = [
                'total_students' => \App\Models\Student::where('class_id', $class->id)->count(),
                'total_subjects' => \App\Models\Subject::where('class_id', $class->id)->count(),
                'teacher_name' => $class->teacher->user->name ?? 'N/A',
                'school_name' => $class->school->name ?? 'N/A',
                'capacity' => $class->capacity,
                'room_number' => $class->room_number
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch class stats: ' . $e->getMessage()
            ], 500);
        }
    }
}
