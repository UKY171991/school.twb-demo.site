<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
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
        $query = Subject::with(['grade', 'teacher', 'school'])->where('school_id', $schoolId);

        $subjects = $query->latest()->paginate(10);

        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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

        return view('subjects.create', compact('grades', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:subjects,code',
            'description' => 'nullable|string',
            'max_marks' => 'nullable|integer|min:1',
            'pass_marks' => 'nullable|integer|min:1',
            'grade_id' => 'required|exists:grades,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        // Add current school context
        $data = $request->all();
        if ($request->has('current_school_id')) {
            $data['school_id'] = $request->get('current_school_id');
        }

        Subject::create($data);

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

        return view('subjects.edit', compact('subject', 'grades', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:subjects,code,'.$subject->id,
            'description' => 'nullable|string',
            'max_marks' => 'nullable|integer|min:1',
            'pass_marks' => 'nullable|integer|min:1',
            'grade_id' => 'required|exists:grades,id',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        // Add current school context
        $data = $request->all();
        if ($request->has('current_school_id')) {
            $data['school_id'] = $request->get('current_school_id');
        }

        $subject->update($data);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

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
