<?php

namespace App\Http\Controllers;

use App\Models\Marksheet;
use App\Models\Student;
use App\Models\Subject;
use App\Models\MarksheetMark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarksheetController extends Controller
{
    public function index()
    {
        $marksheets = Marksheet::with('student')->latest()->paginate(10);
        return view('marksheets.index', compact('marksheets'));
    }

    public function create()
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('marksheets.create', compact('students', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_type_id' => 'required|exists:exam_types,id',
            'exam_name' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'academic_year' => 'required|string|max:255',
            'marks' => 'required|array',
            'marks.*' => 'required|integer|min:0'
        ]);

        DB::transaction(function () use ($request) {
            $student = Student::find($request->student_id);
            
            $marksheet = Marksheet::create([
                'student_id' => $request->student_id,
                'exam_type_id' => $request->exam_type_id,
                'exam_name' => $request->exam_name,
                'exam_date' => $request->exam_date,
                'class' => $student->class,
                'section' => $student->section,
                'academic_year' => $request->academic_year,
            ]);

            foreach ($request->marks as $subjectId => $obtainedMarks) {
                MarksheetMark::create([
                    'student_id' => $request->student_id,
                    'subject_id' => $subjectId,
                    'marksheet_id' => $marksheet->id,
                    'obtained_marks' => $obtainedMarks,
                ]);
            }

            $marksheet->calculateResult();
        });

        return redirect()->route('marksheets.index')->with('success', 'Marksheet created successfully!');
    }

    public function show(Marksheet $marksheet)
    {
        $marksheet->load(['student', 'examType', 'marks.subject']);
        return view('marksheets.show', compact('marksheet'));
    }

    public function print(Marksheet $marksheet)
    {
        $marksheet->load(['student', 'examType', 'marks.subject']);
        return view('marksheets.print', compact('marksheet'));
    }

    public function edit(Marksheet $marksheet)
    {
        $marksheet->load(['student', 'marks.subject']);
        $students = Student::all();
        $subjects = Subject::all();
        return view('marksheets.edit', compact('marksheet', 'students', 'subjects'));
    }

    public function update(Request $request, Marksheet $marksheet)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_name' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'academic_year' => 'required|string|max:255',
            'marks' => 'required|array',
            'marks.*' => 'required|integer|min:0'
        ]);

        DB::transaction(function () use ($request, $marksheet) {
            $student = Student::find($request->student_id);
            
            $marksheet->update([
                'student_id' => $request->student_id,
                'exam_name' => $request->exam_name,
                'exam_date' => $request->exam_date,
                'class' => $student->class,
                'section' => $student->section,
                'academic_year' => $request->academic_year,
            ]);

            // Delete existing marks
            $marksheet->marks()->delete();

            // Create new marks
            foreach ($request->marks as $subjectId => $obtainedMarks) {
                MarksheetMark::create([
                    'student_id' => $request->student_id,
                    'subject_id' => $subjectId,
                    'marksheet_id' => $marksheet->id,
                    'obtained_marks' => $obtainedMarks,
                ]);
            }

            $marksheet->calculateResult();
        });

        return redirect()->route('marksheets.index')->with('success', 'Marksheet updated successfully!');
    }

    public function destroy(Marksheet $marksheet)
    {
        $marksheet->delete();
        return redirect()->route('marksheets.index')->with('success', 'Marksheet deleted successfully!');
    }

    public function searchByRoll(Request $request)
    {
        $rollNumber = $request->get('roll_number');
        
        if (!$rollNumber) {
            return view('marksheets.search');
        }

        $student = Student::where('roll_number', $rollNumber)->first();
        
        if (!$student) {
            return view('marksheets.search')->with('error', 'Student not found with roll number: ' . $rollNumber);
        }

        $marksheets = Marksheet::where('student_id', $student->id)
                              ->with(['student', 'marks.subject'])
                              ->latest()
                              ->get();

        return view('marksheets.search', compact('student', 'marksheets'));
    }
}
