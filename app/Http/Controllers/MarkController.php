<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mark::with(['student.grade', 'subject']);
        
        // Filter by grade if provided
        if ($request->has('grade_id') && $request->grade_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });
        }
        
        // Filter by exam type if provided
        if ($request->has('exam_type') && $request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }
        
        $marks = $query->orderBy('exam_date', 'desc')->get();
        $grades = Grade::all();
        
        return view('marks.index', compact('marks', 'grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $grades = Grade::all();
        $students = [];
        $subjects = [];
        
        if ($request->has('grade_id') && $request->grade_id) {
            $students = Student::where('grade_id', $request->grade_id)->get();
            $subjects = Subject::where('grade_id', $request->grade_id)->get();
        }
        
        return view('marks.create', compact('grades', 'students', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'mark_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'exam_type' => 'required',
            'exam_date' => 'required|date',
        ]);

        Mark::create($request->all());

        return redirect()->route('marks.index')
                        ->with('success','Mark added successfully.');
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
        $mark = Mark::with(['student', 'subject'])->findOrFail($id);
        $subjects = Subject::where('grade_id', $mark->student->grade_id)->get();
        
        return view('marks.edit', compact('mark', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'mark_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'exam_type' => 'required',
            'exam_date' => 'required|date',
        ]);

        $mark = Mark::findOrFail($id);
        $mark->update($request->all());

        return redirect()->route('marks.index')
                        ->with('success','Mark updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mark = Mark::findOrFail($id);
        $mark->delete();

        return redirect()->route('marks.index')
                        ->with('success','Mark deleted successfully.');
    }
}
