<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ExamType;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdmitCardController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\SchoolContext::class);
    }
    public function index()
    {
        $currentSchoolId = Session::get('current_school_id');
        
        $students = Student::where('school_id', $currentSchoolId)
            ->with(['grade'])
            ->orderBy('class')
            ->orderBy('section')
            ->orderBy('roll_number')
            ->paginate(20);
            
        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
        
        return view('admit-cards.index', compact('students', 'examTypes', 'grades'));
    }

    public function search(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        // If no search parameters provided, show the search form
        if (!$request->hasAny(['roll_number', 'exam_type_id', 'class', 'section', 'academic_year'])) {
            $examTypes = ExamType::getActiveTypes($currentSchoolId);
            $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
            return view('admit-cards.search', compact('examTypes', 'grades'));
        }

        // Build the query based on search parameters
        $query = Student::where('school_id', $currentSchoolId)->with(['grade']);

        // Filter by roll number
        if ($request->filled('roll_number')) {
            $query->where('roll_number', 'like', '%' . $request->roll_number . '%');
        }

        // Filter by class
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        // Filter by section
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $students = $query->orderBy('class')
                         ->orderBy('section')
                         ->orderBy('roll_number')
                         ->paginate(20);

        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
        
        // Pass search parameters for pagination
        $students->appends($request->query());

        return view('admit-cards.search', compact('students', 'examTypes', 'grades'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_type_id' => 'required|exists:exam_types,id',
            'academic_year' => 'required|string',
        ]);

        $student = Student::with(['grade', 'school'])->findOrFail($request->student_id);
        $examType = ExamType::findOrFail($request->exam_type_id);
        
        // Get subjects for the student's grade
        $subjects = Subject::where('school_id', $student->school_id)
            ->where(function($query) use ($student) {
                $query->where('grade_id', $student->grade_id)
                      ->orWhereNull('grade_id');
            })
            ->orderBy('name')
            ->get();

        // Get exam timetable for this class and exam type
        $timetable = \App\Models\ExamTimetable::where('school_id', $student->school_id)
            ->where('class', $student->class)
            ->where(function($query) use ($student) {
                $query->where('section', $student->section)
                      ->orWhereNull('section');
            })
            ->where('exam_type_id', $request->exam_type_id)
            ->where('academic_year', $request->academic_year)
            ->where('is_active', true)
            ->with(['subject'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        $admitCardData = [
            'student' => $student,
            'examType' => $examType,
            'subjects' => $subjects,
            'timetable' => $timetable,
            'academic_year' => $request->academic_year,
            'exam_center' => $request->exam_center ?? $student->school->name,
        ];

        return view('admit-cards.print', $admitCardData);
    }

    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'academic_year' => 'required|string',
            'class' => 'required|string',
            'section' => 'nullable|string',
        ]);

        $currentSchoolId = Session::get('current_school_id');
        
        $query = Student::where('school_id', $currentSchoolId)
            ->where('class', $request->class)
            ->with(['grade', 'school']);

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $students = $query->orderBy('roll_number')->get();
        $examType = ExamType::findOrFail($request->exam_type_id);

        if ($students->isEmpty()) {
            return back()->with('error', 'No students found for the selected criteria.');
        }

        // Get subjects for the class
        $firstStudent = $students->first();
        $subjects = Subject::where('school_id', $currentSchoolId)
            ->where(function($query) use ($firstStudent) {
                $query->where('grade_id', $firstStudent->grade_id)
                      ->orWhereNull('grade_id');
            })
            ->orderBy('name')
            ->get();

        // Get exam timetable for this class and exam type
        $timetable = \App\Models\ExamTimetable::where('school_id', $currentSchoolId)
            ->where('class', $request->class)
            ->where(function($query) use ($request) {
                $query->where('section', $request->section)
                      ->orWhereNull('section');
            })
            ->where('exam_type_id', $request->exam_type_id)
            ->where('academic_year', $request->academic_year)
            ->where('is_active', true)
            ->with(['subject'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        $bulkData = [
            'students' => $students,
            'examType' => $examType,
            'subjects' => $subjects,
            'timetable' => $timetable,
            'academic_year' => $request->academic_year,
            'exam_center' => $request->exam_center ?? $firstStudent->school->name,
        ];

        return view('admit-cards.bulk-print', $bulkData);
    }
}