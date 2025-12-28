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
        
        // Get unique timetables by getting the first record for each unique combination
        $allTimetables = ExamTimetable::where('school_id', $currentSchoolId)
            ->with(['examType', 'subject'])
            ->get();
            
        $uniqueTimetables = $allTimetables->unique(function ($item) {
            return $item->exam_type_id . '-' . $item->class . '-' . $item->section . '-' . $item->academic_year;
        })->sortBy('class')->values();
        
        // Check if each class combination has subjects added
        $timetables = $uniqueTimetables->map(function ($timetable) use ($allTimetables) {
            $subjectCount = $allTimetables->where('exam_type_id', $timetable->exam_type_id)
                ->where('class', $timetable->class)
                ->where('section', $timetable->section)
                ->where('academic_year', $timetable->academic_year)
                ->count();
            
            $timetable->has_subjects = $subjectCount > 0;
            $timetable->subject_count = $subjectCount;
            return $timetable;
        });
        
        // Convert to paginated collection for consistency
        $perPage = 20;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $timetables->slice($offset, $perPage);
        
        $timetables = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $timetables->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
            
        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
        
        return view('exam-timetables.index', compact('timetables', 'examTypes', 'grades'));
    }

    // Single entry methods
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

    public function editGroup(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $examTypeId = $request->exam_type_id;
        $class = $request->class;
        $section = $request->section;
        $academicYear = $request->academic_year;

        // Retrieve existing timetables for this group
        $timetables = ExamTimetable::where('school_id', $currentSchoolId)
            ->where('exam_type_id', $examTypeId)
            ->where('class', $class)
            ->where(function($query) use ($section) {
                if ($section) {
                    $query->where('section', $section);
                } else {
                    $query->whereNull('section')->orWhere('section', '');
                }
            })
            ->where('academic_year', $academicYear)
            ->get()
            ->keyBy('subject_id');

        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
        
        $groupData = [
            'exam_type_id' => $examTypeId,
            'class' => $class,
            'section' => $section,
            'academic_year' => $academicYear,
            'exam_center' => $timetables->first()->exam_center ?? null,
        ];

        return view('exam-timetables.edit-group', compact('examTypes', 'subjects', 'grades', 'timetables', 'groupData'));
    }

    public function updateGroup(Request $request)
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
            'subjects.*.exam_date' => 'nullable|date',
            'subjects.*.start_time' => 'nullable|date_format:H:i',
            'subjects.*.end_time' => 'nullable|date_format:H:i',
        ]);

        $updatedCount = 0;
        foreach ($request->subjects as $subjectData) {
            // Check if valid data is present
            if (!empty($subjectData['exam_date']) && !empty($subjectData['start_time']) && !empty($subjectData['end_time'])) {
                
                ExamTimetable::updateOrCreate(
                    [
                        'school_id' => $currentSchoolId,
                        'exam_type_id' => $request->exam_type_id,
                        'class' => $request->class,
                        'section' => $request->section,
                        'academic_year' => $request->academic_year,
                        'subject_id' => $subjectData['subject_id'],
                    ],
                    [
                        'exam_date' => $subjectData['exam_date'],
                        'start_time' => $subjectData['start_time'],
                        'end_time' => $subjectData['end_time'],
                        'exam_center' => $request->exam_center,
                        'instructions' => $subjectData['instructions'] ?? null,
                        'is_active' => true,
                    ]
                );
                $updatedCount++;
            } else {
                // If date/time is cleared, delete the entry if it exists
                 ExamTimetable::where('school_id', $currentSchoolId)
                    ->where('exam_type_id', $request->exam_type_id)
                    ->where('class', $request->class)
                    ->where('section', $request->section)
                    ->where('academic_year', $request->academic_year)
                    ->where('subject_id', $subjectData['subject_id'])
                    ->delete();
            }
        }

        return redirect()->route('exam-timetables.index')->with('success', "Exam timetable updated successfully! ({$updatedCount} subjects scheduled)");
    }

    public function bulkEdit(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $request->validate([
            'class_combinations' => 'required|array',
            'class_combinations.*' => 'string'
        ]);

        $timetables = collect();
        
        foreach ($request->class_combinations as $combination) {
            list($examTypeId, $class, $section, $academicYear) = explode('|', $combination);
            
            // Handle null section properly
            $sectionValue = ($section === '' || $section === 'null') ? null : $section;
            
            $classGroup = ExamTimetable::where('school_id', $currentSchoolId)
                ->where('exam_type_id', $examTypeId)
                ->where('class', $class)
                ->where(function($query) use ($sectionValue) {
                    if ($sectionValue === null) {
                        $query->whereNull('section');
                    } else {
                        $query->where('section', $sectionValue);
                    }
                })
                ->where('academic_year', $academicYear)
                ->with(['examType', 'subject'])
                ->first();
                
            if ($classGroup) {
                $timetables->push($classGroup);
            }
        }

        if ($timetables->isEmpty()) {
            return redirect()->route('exam-timetables.index')->with('error', 'No valid timetables selected.');
        }

        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();

        return view('exam-timetables.bulk-edit', compact('timetables', 'examTypes', 'subjects', 'grades'));
    }

    public function bulkUpdate(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        $request->validate([
            'timetables' => 'required|array',
            'timetables.*.exam_type_id' => 'required|exists:exam_types,id',
            'timetables.*.class' => 'required|string',
            'timetables.*.section' => 'nullable|string',
            'timetables.*.academic_year' => 'required|string',
            'timetables.*.original_exam_type_id' => 'required',
            'timetables.*.original_class' => 'required',
            'timetables.*.original_section' => 'nullable',
            'timetables.*.original_academic_year' => 'required',
        ]);

        $updated = 0;
        foreach ($request->timetables as $timetableData) {
            // Update all timetables that match the original combination
            $affectedRows = ExamTimetable::where('school_id', $currentSchoolId)
                ->where('exam_type_id', $timetableData['original_exam_type_id'])
                ->where('class', $timetableData['original_class'])
                ->where('section', $timetableData['original_section'])
                ->where('academic_year', $timetableData['original_academic_year'])
                ->update([
                    'exam_type_id' => $timetableData['exam_type_id'],
                    'class' => $timetableData['class'],
                    'section' => $timetableData['section'],
                    'academic_year' => $timetableData['academic_year'],
                ]);
                
            $updated += $affectedRows;
        }

        return redirect()->route('exam-timetables.index')->with('success', "Updated {$updated} exam timetable entries successfully!");
    }

    public function bulkDelete(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        $request->validate([
            'class_combinations' => 'required|array',
            'class_combinations.*' => 'string'
        ]);

        $deleted = 0;
        foreach ($request->class_combinations as $combination) {
            list($examTypeId, $class, $section, $academicYear) = explode('|', $combination);
            
            // Handle null section properly
            $sectionValue = ($section === '' || $section === 'null') ? null : $section;
            
            $deletedRows = ExamTimetable::where('school_id', $currentSchoolId)
                ->where('exam_type_id', $examTypeId)
                ->where('class', $class)
                ->where(function($query) use ($sectionValue) {
                    if ($sectionValue === null) {
                        $query->whereNull('section');
                    } else {
                        $query->where('section', $sectionValue);
                    }
                })
                ->where('academic_year', $academicYear)
                ->delete();
                
            $deleted += $deletedRows;
        }

        return redirect()->route('exam-timetables.index')->with('success', "Deleted {$deleted} exam timetable entries successfully!");
    }

    public function bulkCreate(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        
        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }
        
        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();
        
        // Pre-fill data if provided
        $prefilledData = [
            'exam_type_id' => $request->get('exam_type_id'),
            'class' => $request->get('class'),
            'section' => $request->get('section'),
            'academic_year' => $request->get('academic_year'),
        ];
        
        return view('exam-timetables.bulk-create', compact('examTypes', 'subjects', 'grades', 'prefilledData'));
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

    /**
     * Print timetable for a specific class and exam
     */
    public function printTimetable(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        $school = \App\Models\School::find($currentSchoolId);
        
        $query = ExamTimetable::where('school_id', $currentSchoolId)
            ->where('class', $request->class)
            ->where('exam_type_id', $request->exam_type_id)
            ->where('academic_year', $request->academic_year)
            ->where('is_active', true);
        
        // Handle section - if empty string or null, treat as null
        if ($request->section && $request->section !== '') {
            $query->where('section', $request->section);
        } else {
            $query->whereNull('section');
        }
        
        $timetables = $query->with(['subject', 'examType'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        $examType = \App\Models\ExamType::find($request->exam_type_id);
        $class = $request->class;
        $section = $request->section;
        $academic_year = $request->academic_year;

        return view('exam-timetables.print', compact('school', 'examType', 'timetables', 'class', 'section', 'academic_year'));
    }

    /**
     * Print all timetables for school
     */
    public function printAllTimetables(Request $request)
    {
        $currentSchoolId = Session::get('current_school_id');
        $school = \App\Models\School::find($currentSchoolId);

        $query = ExamTimetable::where('school_id', $currentSchoolId)
            ->where('is_active', true)
            ->with(['subject', 'examType']);

        if ($request->exam_type_id) {
            $query->where('exam_type_id', $request->exam_type_id);
        }

        if ($request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        $timetables = $query->orderBy('class')
            ->orderBy('section')
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function($item) {
                return $item->class . '-' . $item->section;
            });

        $examType = $request->exam_type_id ? \App\Models\ExamType::find($request->exam_type_id) : null;
        $academic_year = $request->academic_year;

        return view('exam-timetables.print-all', compact('school', 'examType', 'timetables', 'academic_year'));
    }
}
