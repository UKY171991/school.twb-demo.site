<?php

namespace App\Http\Controllers;

use App\Models\ExamTimetable;
use App\Models\ExamType;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExamTimetableController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\SchoolContext::class);
    }

    public function index()
    {
        $currentSchoolId = Session::get('current_school_id');
        
        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }
        
        $timetables = ExamTimetable::where('school_id', $currentSchoolId)
            ->with(['examType', 'subject'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->paginate(20);
            
        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
        
        return view('exam-timetables.index', compact('timetables', 'examTypes', 'grades'));
    }

    public function create()
    {
        $currentSchoolId = Session::get('current_school_id');
        
        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }
        
        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
        
        return view('exam-timetables.create', compact('examTypes', 'subjects', 'grades'));
    }

    public function store(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'subject_id' => 'required|exists:subjects,id',
            'class' => 'required|string',
            'section' => 'nullable|string',
            'academic_year' => 'required|string',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'exam_center' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['school_id'] = $currentSchoolId;

        ExamTimetable::create($data);

        return redirect()->route('exam-timetables.index')->with('success', 'Exam timetable created successfully!');
    }

    public function show(ExamTimetable $examTimetable)
    {
        $examTimetable->load(['examType', 'subject', 'school']);
        return view('exam-timetables.show', compact('examTimetable'));
    }

    public function edit(ExamTimetable $examTimetable)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
        
        return view('exam-timetables.edit', compact('examTimetable', 'examTypes', 'subjects', 'grades'));
    }

    public function update(Request $request, ExamTimetable $examTimetable)
    {
        $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'subject_id' => 'required|exists:subjects,id',
            'class' => 'required|string',
            'section' => 'nullable|string',
            'academic_year' => 'required|string',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'exam_center' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $examTimetable->update($request->all());

        return redirect()->route('exam-timetables.index')->with('success', 'Exam timetable updated successfully!');
    }

    public function destroy(ExamTimetable $examTimetable)
    {
        $examTimetable->delete();
        return redirect()->route('exam-timetables.index')->with('success', 'Exam timetable deleted successfully!');
    }

    public function bulkCreate()
    {
        $currentSchoolId = Session::get('current_school_id');
        
        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }
        
        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
        
        return view('exam-timetables.bulk-create', compact('examTypes', 'subjects', 'grades'));
    }

    public function bulkStore(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'class' => 'required|string',
            'section' => 'nullable|string',
            'academic_year' => 'required|string',
            'exam_center' => 'nullable|string',
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.exam_date' => 'required|date',
            'subjects.*.start_time' => 'required|date_format:H:i',
            'subjects.*.end_time' => 'required|date_format:H:i',
        ]);

        $created = 0;
        foreach ($request->subjects as $subjectData) {
            ExamTimetable::create([
                'school_id' => $currentSchoolId,
                'exam_type_id' => $request->exam_type_id,
                'subject_id' => $subjectData['subject_id'],
                'class' => $request->class,
                'section' => $request->section,
                'academic_year' => $request->academic_year,
                'exam_date' => $subjectData['exam_date'],
                'start_time' => $subjectData['start_time'],
                'end_time' => $subjectData['end_time'],
                'exam_center' => $request->exam_center,
                'instructions' => $subjectData['instructions'] ?? null,
            ]);
            $created++;
        }

        return redirect()->route('exam-timetables.index')->with('success', "Created {$created} exam timetable entries successfully!");
    }

    public function getByClassAndExam(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        $timetables = ExamTimetable::where('school_id', $currentSchoolId)
            ->where('class', $request->class)
            ->where('section', $request->section)
            ->where('exam_type_id', $request->exam_type_id)
            ->where('academic_year', $request->academic_year)
            ->where('is_active', true)
            ->with(['subject'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        return response()->json($timetables);
    }
}
