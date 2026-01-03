<?php

namespace App\Http\Controllers;

use App\Models\ExamTimetable;
use App\Models\ExamType;
use App\Models\Grade;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ExamTimetableController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\SchoolContext::class);
    }

    /**
     * Get current school ID or redirect if not set
     */
    private function getCurrentSchoolId()
    {
        return Session::get('current_school_id');
    }

    /**
     * Check if school is selected, redirect if not
     */
    private function requireSchool()
    {
        $schoolId = $this->getCurrentSchoolId();
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }
        return null;
    }

    /**
     * Build section query condition
     */
    private function buildSectionCondition($query, $section)
    {
        if ($section === null || $section === '' || $section === 'null') {
            return $query->where(function ($q) {
                $q->whereNull('section')->orWhere('section', '');
            });
        }
        return $query->where('section', $section);
    }

    /**
     * Display a listing of exam timetables
     */
    public function index(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        // Build query with filters
        $query = ExamTimetable::where('school_id', $currentSchoolId)
            ->with(['examType', 'subject']);

        // Apply filters
        if ($request->filled('exam_type_id')) {
            $query->where('exam_type_id', $request->exam_type_id);
        }
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $allTimetables = $query->get();

        // Get unique timetables by combination
        $uniqueTimetables = $allTimetables->unique(function ($item) {
            return $item->exam_type_id . '-' . $item->class . '-' . ($item->section ?? '') . '-' . $item->academic_year;
        })->sortBy('class')->values();

        // Add subject count to each timetable
        $timetables = $uniqueTimetables->map(function ($timetable) use ($allTimetables) {
            $subjectCount = $allTimetables->where('exam_type_id', $timetable->exam_type_id)
                ->where('class', $timetable->class)
                ->filter(function ($item) use ($timetable) {
                    return ($item->section ?? '') === ($timetable->section ?? '');
                })
                ->where('academic_year', $timetable->academic_year)
                ->count();

            $timetable->has_subjects = $subjectCount > 0;
            $timetable->subject_count = $subjectCount;

            return $timetable;
        });

        // Paginate results
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $timetables->slice($offset, $perPage);

        $timetables = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $timetables->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();

        return view('exam-timetables.index', compact('timetables', 'examTypes', 'grades'));
    }

    /**
     * Show the form for creating a new timetable
     */
    public function create()
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();

        return view('exam-timetables.create', compact('examTypes', 'subjects', 'grades'));
    }

    /**
     * Store a newly created timetable
     */
    public function store(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $validated = $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'subject_id' => 'required|exists:subjects,id',
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'academic_year' => 'required|string|max:20',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'exam_center' => 'nullable|string|max:255',
            'instructions' => 'nullable|string|max:1000',
        ]);

        $validated['school_id'] = $currentSchoolId;

        try {
            ExamTimetable::create($validated);
            return redirect()->route('exam-timetables.index')->with('success', 'Exam timetable created successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to create exam timetable: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create exam timetable. Please try again.');
        }
    }

    /**
     * Display the specified timetable
     */
    public function show(ExamTimetable $examTimetable)
    {
        $examTimetable->load(['examType', 'subject', 'school']);
        return view('exam-timetables.show', compact('examTimetable'));
    }

    /**
     * Show the form for editing the specified timetable
     */
    public function edit(ExamTimetable $examTimetable)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();

        return view('exam-timetables.edit', compact('examTimetable', 'examTypes', 'subjects', 'grades'));
    }

    /**
     * Update the specified timetable
     */
    public function update(Request $request, ExamTimetable $examTimetable)
    {
        $validated = $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'subject_id' => 'required|exists:subjects,id',
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'academic_year' => 'required|string|max:20',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'exam_center' => 'nullable|string|max:255',
            'instructions' => 'nullable|string|max:1000',
        ]);

        try {
            $examTimetable->update($validated);
            return redirect()->route('exam-timetables.index')->with('success', 'Exam timetable updated successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to update exam timetable: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update exam timetable. Please try again.');
        }
    }

    /**
     * Remove the specified timetable
     */
    public function destroy(ExamTimetable $examTimetable)
    {
        try {
            $examTimetable->delete();
            return redirect()->route('exam-timetables.index')->with('success', 'Exam timetable deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete exam timetable: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete exam timetable. Please try again.');
        }
    }

    /**
     * Edit a group of timetables (by class/exam type combination)
     */
    public function editGroup(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $examTypeId = $request->get('exam_type');
        $class = $request->get('class');
        $section = $request->get('section');
        $academicYear = $request->get('academic_year');

        // Build query for existing timetables
        $query = ExamTimetable::where('school_id', $currentSchoolId)
            ->where('exam_type_id', $examTypeId)
            ->where('class', $class)
            ->where('academic_year', $academicYear);

        $this->buildSectionCondition($query, $section);

        $timetables = $query->get()->keyBy('subject_id');

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

    /**
     * Update a group of timetables
     */
    public function updateGroup(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $validated = $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'academic_year' => 'required|string|max:20',
            'exam_center' => 'nullable|string|max:255',
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.exam_date' => 'nullable|date',
            'subjects.*.start_time' => 'nullable|date_format:H:i',
            'subjects.*.end_time' => 'nullable|date_format:H:i',
        ]);

        try {
            DB::beginTransaction();

            $updatedCount = 0;
            $deletedCount = 0;

            foreach ($request->subjects as $subjectData) {
                $hasValidData = !empty($subjectData['exam_date']) && 
                               !empty($subjectData['start_time']) && 
                               !empty($subjectData['end_time']);

                if ($hasValidData) {
                    ExamTimetable::updateOrCreate(
                        [
                            'school_id' => $currentSchoolId,
                            'exam_type_id' => $request->exam_type_id,
                            'class' => $request->class,
                            'section' => $request->section ?: null,
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
                    // Delete entry if data is cleared
                    $query = ExamTimetable::where('school_id', $currentSchoolId)
                        ->where('exam_type_id', $request->exam_type_id)
                        ->where('class', $request->class)
                        ->where('academic_year', $request->academic_year)
                        ->where('subject_id', $subjectData['subject_id']);

                    $this->buildSectionCondition($query, $request->section);
                    $deletedCount += $query->delete();
                }
            }

            DB::commit();

            $message = "Timetable updated! {$updatedCount} subject(s) scheduled";
            if ($deletedCount > 0) {
                $message .= ", {$deletedCount} removed";
            }

            return redirect()->route('exam-timetables.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update group timetable: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update timetable. Please try again.');
        }
    }

    /**
     * Delete a group of timetables (by class/exam type combination)
     */
    public function deleteGroup(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $examTypeId = $request->get('exam_type');
        $class = $request->get('class');
        $section = $request->get('section');
        $academicYear = $request->get('academic_year');

        try {
            $query = ExamTimetable::where('school_id', $currentSchoolId)
                ->where('exam_type_id', $examTypeId)
                ->where('class', $class)
                ->where('academic_year', $academicYear);

            $this->buildSectionCondition($query, $section);

            $deletedCount = $query->delete();

            return redirect()->route('exam-timetables.index')
                ->with('success', "Deleted {$deletedCount} timetable entries for {$class} " . ($section ?: 'All') . " successfully!");
        } catch (\Exception $e) {
            Log::error('Failed to delete group timetable: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete timetable. Please try again.');
        }
    }

    /**
     * Show add subjects form for a class
     */
    public function addSubjects(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();

        $prefilledData = [
            'exam_type_id' => $request->get('exam_type'),
            'class' => $request->get('class'),
            'section' => $request->get('section'),
            'academic_year' => $request->get('academic_year'),
        ];

        return view('exam-timetables.bulk-create', compact('examTypes', 'subjects', 'grades', 'prefilledData'));
    }

    /**
     * Bulk edit multiple timetables
     */
    public function bulkEdit(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        // Handle both GET (from URL) and POST requests
        $selectedParam = $request->get('selected') ?? $request->get('class_combinations');
        
        if (is_string($selectedParam)) {
            $combinations = explode(',', $selectedParam);
        } else {
            $combinations = $selectedParam ?? [];
        }

        if (empty($combinations)) {
            return redirect()->route('exam-timetables.index')->with('error', 'No timetables selected.');
        }

        $timetables = collect();

        foreach ($combinations as $combination) {
            $parts = explode('|', $combination);
            if (count($parts) < 4) continue;

            [$examTypeId, $class, $section, $academicYear] = $parts;
            $sectionValue = ($section === '' || $section === 'null') ? null : $section;

            $query = ExamTimetable::where('school_id', $currentSchoolId)
                ->where('exam_type_id', $examTypeId)
                ->where('class', $class)
                ->where('academic_year', $academicYear)
                ->with(['examType', 'subject']);

            $this->buildSectionCondition($query, $sectionValue);

            $classGroup = $query->first();
            if ($classGroup) {
                $timetables->push($classGroup);
            }
        }

        if ($timetables->isEmpty()) {
            return redirect()->route('exam-timetables.index')->with('error', 'No valid timetables found.');
        }

        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();

        return view('exam-timetables.bulk-edit', compact('timetables', 'examTypes', 'subjects', 'grades'));
    }

    /**
     * Bulk update multiple timetables
     */
    public function bulkUpdate(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $validated = $request->validate([
            'timetables' => 'required|array',
            'timetables.*.exam_type_id' => 'required|exists:exam_types,id',
            'timetables.*.class' => 'required|string|max:50',
            'timetables.*.section' => 'nullable|string|max:10',
            'timetables.*.academic_year' => 'required|string|max:20',
            'timetables.*.original_exam_type_id' => 'required',
            'timetables.*.original_class' => 'required',
            'timetables.*.original_section' => 'nullable',
            'timetables.*.original_academic_year' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $updated = 0;
            foreach ($request->timetables as $timetableData) {
                $query = ExamTimetable::where('school_id', $currentSchoolId)
                    ->where('exam_type_id', $timetableData['original_exam_type_id'])
                    ->where('class', $timetableData['original_class'])
                    ->where('academic_year', $timetableData['original_academic_year']);

                $this->buildSectionCondition($query, $timetableData['original_section']);

                $affectedRows = $query->update([
                    'exam_type_id' => $timetableData['exam_type_id'],
                    'class' => $timetableData['class'],
                    'section' => $timetableData['section'] ?: null,
                    'academic_year' => $timetableData['academic_year'],
                ]);

                $updated += $affectedRows;
            }

            DB::commit();

            return redirect()->route('exam-timetables.index')
                ->with('success', "Updated {$updated} exam timetable entries successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to bulk update timetables: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update timetables. Please try again.');
        }
    }

    /**
     * Bulk delete multiple timetables
     */
    public function bulkDelete(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $combinations = $request->get('selected_combinations', []);

        if (empty($combinations)) {
            return redirect()->route('exam-timetables.index')->with('error', 'No timetables selected for deletion.');
        }

        try {
            DB::beginTransaction();

            $deleted = 0;
            foreach ($combinations as $combination) {
                $parts = explode('|', $combination);
                if (count($parts) < 4) continue;

                [$examTypeId, $class, $section, $academicYear] = $parts;
                $sectionValue = ($section === '' || $section === 'null') ? null : $section;

                $query = ExamTimetable::where('school_id', $currentSchoolId)
                    ->where('exam_type_id', $examTypeId)
                    ->where('class', $class)
                    ->where('academic_year', $academicYear);

                $this->buildSectionCondition($query, $sectionValue);

                $deleted += $query->delete();
            }

            DB::commit();

            return redirect()->route('exam-timetables.index')
                ->with('success', "Deleted {$deleted} exam timetable entries successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to bulk delete timetables: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete timetables. Please try again.');
        }
    }

    /**
     * Show bulk create form
     */
    public function bulkCreate(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $examTypes = ExamType::getActiveTypes($currentSchoolId);
        $subjects = Subject::where('school_id', $currentSchoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $currentSchoolId)->orderBy('name')->get();

        $prefilledData = [
            'exam_type_id' => $request->get('exam_type_id'),
            'class' => $request->get('class'),
            'section' => $request->get('section'),
            'academic_year' => $request->get('academic_year'),
        ];

        return view('exam-timetables.bulk-create', compact('examTypes', 'subjects', 'grades', 'prefilledData'));
    }

    /**
     * Store bulk created timetables
     */
    public function bulkStore(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $validated = $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'academic_year' => 'required|string|max:20',
            'exam_center' => 'nullable|string|max:255',
            'subjects' => 'required|array|min:1',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.exam_date' => 'required|date',
            'subjects.*.start_time' => 'required|date_format:H:i',
            'subjects.*.end_time' => 'required|date_format:H:i|after:subjects.*.start_time',
        ]);

        try {
            DB::beginTransaction();

            $created = 0;
            foreach ($request->subjects as $subjectData) {
                ExamTimetable::create([
                    'school_id' => $currentSchoolId,
                    'exam_type_id' => $request->exam_type_id,
                    'subject_id' => $subjectData['subject_id'],
                    'class' => $request->class,
                    'section' => $request->section ?: null,
                    'academic_year' => $request->academic_year,
                    'exam_date' => $subjectData['exam_date'],
                    'start_time' => $subjectData['start_time'],
                    'end_time' => $subjectData['end_time'],
                    'exam_center' => $request->exam_center,
                    'instructions' => $subjectData['instructions'] ?? null,
                    'is_active' => true,
                ]);
                $created++;
            }

            DB::commit();

            return redirect()->route('exam-timetables.index')
                ->with('success', "Created {$created} exam timetable entries successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to bulk create timetables: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create timetables. Please try again.');
        }
    }

    /**
     * Get timetables by class and exam type (API)
     */
    public function getByClassAndExam(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return response()->json(['error' => 'No school selected'], 400);
        }

        $query = ExamTimetable::where('school_id', $currentSchoolId)
            ->where('class', $request->class)
            ->where('exam_type_id', $request->exam_type_id)
            ->where('academic_year', $request->academic_year)
            ->where('is_active', true)
            ->with(['subject']);

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $timetables = $query->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        return response()->json($timetables);
    }

    /**
     * Print timetable for a specific class and exam
     */
    public function printTimetable(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $school = School::find($currentSchoolId);

        $query = ExamTimetable::where('school_id', $currentSchoolId)
            ->where('class', $request->class)
            ->where('exam_type_id', $request->exam_type)
            ->where('academic_year', $request->academic_year)
            ->where('is_active', true);

        $this->buildSectionCondition($query, $request->section);

        $timetables = $query->with(['subject', 'examType'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        if ($timetables->isEmpty()) {
            return back()->with('error', 'No timetable entries found for the selected criteria.');
        }

        $examType = ExamType::find($request->exam_type);
        $class = $request->class;
        $section = $request->section;
        $academic_year = $request->academic_year;

        // Get grade with class teacher
        $gradeQuery = Grade::where('school_id', $currentSchoolId)
            ->where('name', $class)
            ->with('teacher');

        if ($section) {
            $gradeQuery->where('section', $section);
        }

        $grade = $gradeQuery->first();

        return view('exam-timetables.print', compact('school', 'examType', 'timetables', 'class', 'section', 'academic_year', 'grade'));
    }

    /**
     * Print all timetables for school
     */
    public function printAllTimetables(Request $request)
    {
        $currentSchoolId = $this->getCurrentSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $school = School::find($currentSchoolId);

        $query = ExamTimetable::where('school_id', $currentSchoolId)
            ->where('is_active', true)
            ->with(['subject', 'examType']);

        if ($request->filled('exam_type_id')) {
            $query->where('exam_type_id', $request->exam_type_id);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $timetables = $query->orderBy('class')
            ->orderBy('section')
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($item) {
                return $item->class . '-' . ($item->section ?? 'All');
            });

        if ($timetables->isEmpty()) {
            return back()->with('error', 'No timetable entries found.');
        }

        $examType = $request->filled('exam_type_id') ? ExamType::find($request->exam_type_id) : null;
        $academic_year = $request->academic_year;

        return view('exam-timetables.print-all', compact('school', 'examType', 'timetables', 'academic_year'));
    }
}
