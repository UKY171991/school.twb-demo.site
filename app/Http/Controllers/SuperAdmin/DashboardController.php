<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSchools = \App\Models\School::count();
        $totalUsers = \App\Models\User::count();
        $totalStudents = \App\Models\Student::count();
        $activeSchools = \App\Models\School::where('is_active', true)->count();
        
        return view('superadmin.dashboard', compact('totalSchools', 'totalUsers', 'totalStudents', 'activeSchools'));
    }
}
