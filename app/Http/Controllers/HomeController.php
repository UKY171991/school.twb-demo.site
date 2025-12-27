<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats = [
            'teachers' => \App\Models\Teacher::count(),
            'students' => \App\Models\Student::count(),
            'grades' => \App\Models\Grade::count(),
            'subjects' => \App\Models\Subject::count(),
            'attendances_today' => \App\Models\Attendance::whereDate('attendance_date', today())->count(),
            'marks' => \App\Models\Mark::count(),
        ];
        
        return view('home', compact('stats'));
    }
}
