<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $schoolId = $user->school_id;
        
        $totalStudents = \App\Models\Student::where('school_id', $schoolId)->count();
        $totalTeachers = \App\Models\Teacher::where('school_id', $schoolId)->count();
        $totalClasses = \App\Models\ClassModel::where('school_id', $schoolId)->count();
        $school = $user->school;
        
        return view('admin.dashboard', compact('totalStudents', 'totalTeachers', 'totalClasses', 'school'));
    }
}
