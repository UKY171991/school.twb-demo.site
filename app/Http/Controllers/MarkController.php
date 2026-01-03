<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\SchoolContext::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schoolId = session('current_school_id');

        $query = Mark::with(['student.grade', 'subject'])
            ->whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });

        // Filter by grade if provided
        if ($request->has('grade_id') && $request->grade_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });
        }

        // Filter by exam type if provided
        if ($request->has('exam_type') && $request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }

        $marks = $query->orderBy('exam_date', 'desc')->get();
        $grades = Grade::where('school_id', $schoolId)->get();
        $examTypes = \App\Models\ExamType::getActiveTypes($schoolId);

        return view('marks.index', compact('marks', 'grades', 'examTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $schoolId = session('current_school_id');

        $grades = Grade::where('school_id', $schoolId)->get();
        $examTypes = \App\Models\ExamType::getActiveTypes($schoolId);
        $students = [];
        $subjects = [];

        if ($request->has('grade_id') && $request->grade_id) {
            $students = Student::where('grade_id', $request->grade_id)
                ->where('school_id', $schoolId)->get();
            $subjects = Subject::where('grade_id', $request->grade_id)
                ->where('school_id', $schoolId)->get();
        }

        return view('marks.create', compact('grades', 'students', 'subjects', 'examTypes'));
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
            ->with('success', 'Mark added successfully.');
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
    public function edit(string $id, Request $request)
    {
        $schoolId = session('current_school_id');
        $mark = Mark::with(['student', 'subject'])->findOrFail($id);

        $subjects = Subject::where('grade_id', $mark->student->grade_id)
            ->where('school_id', $schoolId)->get();
        $examTypes = \App\Models\ExamType::getActiveTypes($schoolId);

        return view('marks.edit', compact('mark', 'subjects', 'examTypes'));
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
            ->with('success', 'Mark updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mark = Mark::findOrFail($id);
        $mark->delete();

        return redirect()->route('marks.index')
            ->with('success', 'Mark deleted successfully.');
    }
}
