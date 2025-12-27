<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::with('student.grade');
        
        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $query->whereDate('attendance_date', $request->date);
        } else {
            // Default to today
            $query->whereDate('attendance_date', Carbon::today());
        }
        
        $attendances = $query->orderBy('attendance_date', 'desc')->get();
        $grades = Grade::all();
        
        return view('attendances.index', compact('attendances', 'grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $grades = Grade::all();
        $students = [];
        
        if ($request->has('grade_id') && $request->grade_id) {
            $students = Student::where('grade_id', $request->grade_id)->get();
        }
        
        return view('attendances.create', compact('grades', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|array',
        ]);

        foreach ($request->student_ids as $student_id) {
            // Check if attendance already exists
            $existing = Attendance::where('student_id', $student_id)
                ->whereDate('attendance_date', $request->attendance_date)
                ->first();
                
            if ($existing) {
                $existing->update([
                    'status' => $request->status[$student_id] ?? 'absent',
                    'note' => $request->note[$student_id] ?? null,
                ]);
            } else {
                Attendance::create([
                    'student_id' => $student_id,
                    'attendance_date' => $request->attendance_date,
                    'status' => $request->status[$student_id] ?? 'absent',
                    'note' => $request->note[$student_id] ?? null,
                ]);
            }
        }

        return redirect()->route('attendances.index', ['date' => $request->attendance_date])
                        ->with('success','Attendance marked successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attendance = Attendance::with('student')->findOrFail($id);
        return view('attendances.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendances.index')
                        ->with('success','Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendances.index')
                        ->with('success','Attendance deleted successfully.');
    }
}
