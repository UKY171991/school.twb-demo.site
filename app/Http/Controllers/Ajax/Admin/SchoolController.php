<?php

namespace App\Http\Controllers\Ajax\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function select(Request $request)
    {
        try {
            $schools = School::where('is_active', true)
                ->select('id', 'name', 'address')
                ->when($request->search, function($query, $search) {
                    return $query->where('name', 'like', '%' . $search . '%');
                })
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $schools
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch schools: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $school = School::findOrFail($request->school_id);
            
            $stats = [
                'total_teachers' => \App\Models\Teacher::where('school_id', $school->id)->count(),
                'total_students' => \App\Models\Student::where('school_id', $school->id)->count(),
                'total_classes' => \App\Models\ClassModel::where('school_id', $school->id)->count(),
                'total_subjects' => \App\Models\Subject::where('school_id', $school->id)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch school stats: ' . $e->getMessage()
            ], 500);
        }
    }
}
