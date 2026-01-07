<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        // Middleware is already applied in bootstrap/app.php
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schoolId = $request->current_school_id;
        
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $query = Subject::with(['grade', 'teacher', 'school'])->where('school_id', $schoolId);

        $subjects = $query->latest()->paginate(10);

        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $schoolId = $request->current_school_id;
        
        if (!$schoolId) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please select a school first.'], 422);
            }
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $grades = \App\Models\Grade::where('school_id', $schoolId)->get();
        $teachers = \App\Models\Teacher::where('school_id', $schoolId)->get();

        if ($request->ajax()) {
            return view('subjects.create', compact('grades', 'teachers'))->renderSections()['content'];
        }

        return view('subjects.create', compact('grades', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $schoolId = $request->current_school_id;

        if (!$schoolId) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please select a school first.'], 422);
            }
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => [
                'nullable',
                'string',
                // Unique code within the same school
                \Illuminate\Validation\Rule::unique('subjects')->where(function ($query) use ($schoolId) {
                    return $query->where('school_id', $schoolId);
                }),
            ],
            'description' => 'nullable|string',
            'max_marks' => 'nullable|integer|min:1',
            'pass_marks' => 'nullable|integer|min:1',
            'grade_id' => 'required|exists:grades,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['school_id'] = $schoolId;

        Subject::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Subject created successfully.']);
        }

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $subject->load(['grade', 'teacher', 'school']);

        return view('subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject, Request $request)
    {
        $query = function ($model) use ($request) {
            $q = $model::query();
            if ($request->has('current_school_id')) {
                $q->where('school_id', $request->get('current_school_id'));
            }

            return $q;
        };

        $grades = $query(\App\Models\Grade::class)->get();
        $teachers = $query(\App\Models\Teacher::class)->get();

        if ($request->ajax()) {
            return view('subjects.edit', compact('subject', 'grades', 'teachers'))->renderSections()['content'];
        }

        return view('subjects.edit', compact('subject', 'grades', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:subjects,code,'.$subject->id,
            'description' => 'nullable|string',
            'max_marks' => 'nullable|integer|min:1',
            'pass_marks' => 'nullable|integer|min:1',
            'grade_id' => 'required|exists:grades,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Add current school context
        $data = $request->all();
        if ($request->has('current_school_id')) {
            $data['school_id'] = $request->get('current_school_id');
        }

        $subject->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Subject updated successfully.']);
        }

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Subject $subject)
    {
        $subject->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Subject deleted successfully.']);
        }

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    /**
     * Get subjects by grade (API endpoint)
     */
    public function getByGrade($gradeId)
    {
        $subjects = Subject::where('grade_id', $gradeId)
            ->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        return response()->json($subjects);
    }
}
