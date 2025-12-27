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
    public function index(Request $request)
    {
        $currentSchoolId = $request->get('current_school_id');
        
        // Filter stats by current school if available
        $query = function($model) use ($currentSchoolId) {
            if ($currentSchoolId && in_array('school_id', $model::make()->getFillable())) {
                return $model::where('school_id', $currentSchoolId);
            }
            return $model::query();
        };
        
        $stats = [
            'teachers' => $query(\App\Models\Teacher::class)->count(),
            'students' => $query(\App\Models\Student::class)->count(),
            'grades' => $query(\App\Models\Grade::class)->count(),
            'subjects' => $query(\App\Models\Subject::class)->count(),
            'attendances_today' => \App\Models\Attendance::whereDate('attendance_date', today())->count(),
            'marksheets' => $query(\App\Models\Marksheet::class)->count(),
        ];
        
        return view('home', compact('stats'));
    }
}
