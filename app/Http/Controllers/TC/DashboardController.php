<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            abort(403, 'Teacher profile not found.');
        }

        $class_ids = ClassModel::where('teacher_id', $teacher->id)->pluck('id');

        $stats = [
            'total_classes' => $class_ids->count(),
            'total_students' => Student::whereIn('class_id', $class_ids)->count(),
            'total_subjects' => Subject::where('teacher_id', $teacher->id)->count(),
            'total_grades_recorded' => Grade::whereIn('class_id', $class_ids)->count(),
        ];

        $classes = ClassModel::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->with('school')
            ->take(5)
            ->get();

        return view('tc.dashboard', compact('stats', 'classes'));
    }
}
