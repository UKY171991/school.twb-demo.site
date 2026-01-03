<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
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
        
        $query = Grade::with(['teacher'])->where('school_id', $schoolId);
        $grades = $query->paginate(10);

        return view('grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $schoolId = $request->current_school_id;
        
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }
        
        $teachers = \App\Models\Teacher::where('school_id', $schoolId)->get();

        return view('grades.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $schoolId = $request->current_school_id;

        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Unique name within the same school
                \Illuminate\Validation\Rule::unique('grades')->where(function ($query) use ($schoolId) {
                    return $query->where('school_id', $schoolId);
                }),
            ],
            'section' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'nullable|integer|min:1|max:500',
            'description' => 'nullable|string|max:1000',
            'grade_theme' => 'nullable|integer|min:1|max:12',
            'status' => 'nullable|string|in:active,inactive,upcoming',
        ]);

        $data = $request->all();
        $data['school_id'] = $schoolId;

        // Set default values if not provided
        $data['capacity'] = $data['capacity'] ?? 40;
        $data['grade_theme'] = $data['grade_theme'] ?? 1;
        $data['status'] = $data['status'] ?? 'active';

        Grade::create($data);

        return redirect()->route('grades.index')
            ->with('success', 'Grade created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grade = Grade::with(['teacher'])->withCount('students')->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'id' => $grade->id,
                'name' => $grade->name,
                'section' => $grade->section,
                'students_count' => $grade->students_count,
                'created_at' => $grade->created_at->format('d/m/Y'),
                'status' => 'Active', // Assuming active for now as per view
                'last_activity' => $grade->updated_at->diffForHumans(),
            ]);
        }

        return view('grades.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        // Get school ID from multiple sources with fallback
        $schoolId = $request->input('current_school_id') 
                   ?: session('current_school_id')
                   ?: \App\Models\School::value('id');
        
        // Ensure we have a valid school ID
        if (!$schoolId) {
            // Create a default school if none exists
            $school = \App\Models\School::firstOrCreate([
                'code' => 'DEFAULT'
            ], [
                'name' => 'Default School',
                'address' => 'Default Address',
                'status' => 'active'
            ]);
            $schoolId = $school->id;
            session(['current_school_id' => $schoolId]);
        }
        
        $grade = Grade::findOrFail($id);
        $teachers = \App\Models\Teacher::where('school_id', $schoolId)->get();

        return view('grades.edit', compact('grade', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'nullable|integer|min:1|max:100',
            'description' => 'nullable|string|max:1000',
            'grade_theme' => 'nullable|integer|min:1|max:12',
            'status' => 'nullable|string|in:active,inactive,upcoming',
        ]);

        $grade = Grade::findOrFail($id);

        // Add current school context
        $data = $request->all();
        if ($request->has('current_school_id')) {
            $data['school_id'] = $request->get('current_school_id');
        }

        // Set default values if not provided
        $data['capacity'] = $data['capacity'] ?? 40;
        $data['grade_theme'] = $data['grade_theme'] ?? 1;
        $data['status'] = $data['status'] ?? 'active';

        $grade->update($data);

        return redirect()->route('grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return redirect()->route('grades.index')
            ->with('success', 'Grade deleted successfully.');
    }

    /**
     * Get grades by school (API endpoint)
     */
    public function getBySchool($schoolId)
    {
        $grades = Grade::where('school_id', $schoolId)
            ->select('id', 'name', 'section')
            ->orderBy('name')
            ->get();

        return response()->json($grades);
    }
}
