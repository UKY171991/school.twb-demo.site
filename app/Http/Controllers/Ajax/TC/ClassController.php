<?php

namespace App\Http\Controllers\Ajax\TC;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        try {
            $teacher = auth()->user()->teacher;
            
            $classes = ClassModel::with(['school', 'students' => function($query) {
                    $query->where('status', 'active');
                }])
                ->where('teacher_id', $teacher->id)
                ->where('is_active', true)
                ->withCount(['students' => function($query) {
                    $query->where('status', 'active');
                }])
                ->get()
                ->map(function($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name,
                        'section' => $class->section,
                        'full_name' => $class->full_name,
                        'students_count' => $class->students_count,
                        'room_number' => $class->room_number,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load classes: ' . $e->getMessage()
            ], 500);
        }
    }
}