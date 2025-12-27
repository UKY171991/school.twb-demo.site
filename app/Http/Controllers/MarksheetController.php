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

            // Recalculate positions for all students in the same group
            $this->recalculateGroupPositions($marksheet);
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
            'exam_type_id' => 'required|exists:exam_types,id',
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
                'exam_type_id' => $request->exam_type_id,
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

            // Recalculate positions for all students in the same group if requested
            if ($request->has('recalculate_position')) {
                $this->recalculateGroupPositions($marksheet);
            }
        });

        return redirect()->route('marksheets.index')->with('success', 'Marksheet updated successfully!');
    }

    public function destroy(Marksheet $marksheet)
    {
        // Store the marksheet data before deletion for position recalculation
        $class = $marksheet->class;
        $section = $marksheet->section;
        $examTypeId = $marksheet->exam_type_id;
        $academicYear = $marksheet->academic_year;

        $marksheet->delete();

        // Recalculate positions for remaining students in the same group
        $this->recalculateGroupPositionsByData($class, $section, $examTypeId, $academicYear);

        return redirect()->route('marksheets.index')->with('success', 'Marksheet deleted successfully!');
    }

    public function searchByRoll(Request $request)
    {
        // If no search parameters provided, show the search form
        if (!$request->hasAny(['roll_number', 'exam_type_id', 'exam_name', 'academic_year', 'class', 'section', 'result'])) {
            return view('marksheets.search');
        }

        // Build the query based on search parameters
        $query = Marksheet::with(['student', 'examType', 'marks.subject']);

        // Filter by roll number (specific student search)
        if ($request->filled('roll_number')) {
            $student = Student::where('roll_number', $request->roll_number)->first();
            
            if (!$student) {
                return view('marksheets.search')->with('error', 'Student not found with roll number: ' . $request->roll_number);
            }

            $marksheets = Marksheet::where('student_id', $student->id)
                                  ->with(['student', 'examType', 'marks.subject'])
                                  ->latest()
                                  ->get();

            return view('marksheets.search', compact('student', 'marksheets'));
        }

        // Advanced search filters
        if ($request->filled('exam_type_id')) {
            $query->where('exam_type_id', $request->exam_type_id);
        }

        if ($request->filled('exam_name')) {
            $query->where('exam_name', 'like', '%' . $request->exam_name . '%');
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('result')) {
            $query->where('result', $request->result);
        }

        // Execute the query
        $results = $query->orderBy('percentage', 'desc')
                        ->orderBy('exam_date', 'desc')
                        ->get();

        return view('marksheets.search', compact('results'));
    }

    public function recalculatePositions()
    {
        try {
            // Get all marksheets grouped by class, section, exam_type, and academic year
            $marksheets = Marksheet::all();
            
            $groups = $marksheets->groupBy(function($marksheet) {
                return $marksheet->class . '-' . $marksheet->section . '-' . 
                       ($marksheet->exam_type_id ?? 'no-exam-type') . '-' . 
                       $marksheet->academic_year;
            });

            $totalUpdated = 0;

            foreach ($groups as $groupMarksheets) {
                // Sort by percentage (descending) and obtained marks (descending)
                $sortedMarksheets = $groupMarksheets->sortByDesc('percentage')
                                                   ->sortByDesc('obtained_marks');

                $totalStudents = $sortedMarksheets->count();
                $position = 1;

                foreach ($sortedMarksheets as $marksheet) {
                    $marksheet->update([
                        'class_position' => $position,
                        'total_students' => $totalStudents
                    ]);
                    $position++;
                    $totalUpdated++;
                }
            }

            return redirect()->route('marksheets.index')
                           ->with('success', "Class positions recalculated successfully! Updated {$totalUpdated} marksheets in " . $groups->count() . " groups.");
        } catch (\Exception $e) {
            return redirect()->route('marksheets.index')
                           ->with('error', 'Error recalculating positions: ' . $e->getMessage());
        }
    }

    private function recalculateGroupPositions(Marksheet $marksheet)
    {
        // Get all marksheets in the same group (class, section, exam_type, academic_year)
        $query = Marksheet::where('class', $marksheet->class)
                          ->where('section', $marksheet->section)
                          ->where('academic_year', $marksheet->academic_year);

        // Only filter by exam_type_id if it's set
        if ($marksheet->exam_type_id) {
            $query->where('exam_type_id', $marksheet->exam_type_id);
        } else {
            $query->whereNull('exam_type_id');
        }

        $groupMarksheets = $query->orderBy('percentage', 'desc')
                                ->orderBy('obtained_marks', 'desc')
                                ->get();

        $totalStudents = $groupMarksheets->count();
        $position = 1;

        foreach ($groupMarksheets as $groupMarksheet) {
            $groupMarksheet->update([
                'class_position' => $position,
                'total_students' => $totalStudents
            ]);
            $position++;
        }
    }

    private function recalculateGroupPositionsByData($class, $section, $examTypeId, $academicYear)
    {
        // Get all marksheets in the same group using the provided data
        $query = Marksheet::where('class', $class)
                          ->where('section', $section)
                          ->where('academic_year', $academicYear);

        // Only filter by exam_type_id if it's set
        if ($examTypeId) {
            $query->where('exam_type_id', $examTypeId);
        } else {
            $query->whereNull('exam_type_id');
        }

        $groupMarksheets = $query->orderBy('percentage', 'desc')
                                ->orderBy('obtained_marks', 'desc')
                                ->get();

        $totalStudents = $groupMarksheets->count();
        $position = 1;

        foreach ($groupMarksheets as $groupMarksheet) {
            $groupMarksheet->update([
                'class_position' => $position,
                'total_students' => $totalStudents
            ]);
            $position++;
        }
    }
}
