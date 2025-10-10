<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'schools' => School::count(),
            'teachers' => Teacher::count(),
            'students' => Student::count(),
            'classes' => ClassModel::count(),
        ];

        $recentSchools = School::latest()->take(5)->get();
        $recentStudents = Student::with('class')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentSchools', 'recentStudents'));
    }

    /**
     * Get dashboard statistics via AJAX.
     */
    public function getStats(Request $request)
    {
        try {
            $stats = [
                'schools' => School::count(),
                'teachers' => Teacher::count(),
                'students' => Student::count(),
                'classes' => ClassModel::count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }
}
