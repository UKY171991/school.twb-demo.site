<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
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
        $query = Grade::with(['teacher'])->withCount('students')->where('school_id', $schoolId);
        
        $grades = $query->paginate(10);
        return view('grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolId = session('current_school_id');
        $teachers = \App\Models\Teacher::where('school_id', $schoolId)->get();
        return view('grades.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        // Add current school context
        $data = $request->all();
        if ($request->has('current_school_id')) {
            $data['school_id'] = $request->get('current_school_id');
        }

        Grade::create($data);

        return redirect()->route('grades.index')
                        ->with('success','Grade created successfully.');
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
                'last_activity' => $grade->updated_at->diffForHumans()
            ]);
        }
        
        return view('grades.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $grade = Grade::findOrFail($id);
        $schoolId = session('current_school_id');
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
        ]);

        $grade = Grade::findOrFail($id);
        
        // Add current school context
        $data = $request->all();
        if ($request->has('current_school_id')) {
            $data['school_id'] = $request->get('current_school_id');
        }
        
        $grade->update($data);

        return redirect()->route('grades.index')
                        ->with('success','Grade updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return redirect()->route('grades.index')
                        ->with('success','Grade deleted successfully.');
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
