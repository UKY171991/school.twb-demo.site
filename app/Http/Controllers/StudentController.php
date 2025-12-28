<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
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
        $query = Student::with('grade')->where('school_id', $schoolId);
        
        $students = $query->latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get all schools for selection, but filter grades by current school if one is selected
        $currentSchoolId = session('current_school_id');
        
        if ($currentSchoolId) {
            $grades = \App\Models\Grade::where('school_id', $currentSchoolId)->get();
        } else {
            $grades = \App\Models\Grade::all();
        }
        
        return view('students.create', compact('grades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'grade_id' => 'required|exists:grades,id',
            'school_id' => 'required|exists:schools,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Populate class and section from Grade
        $grade = Grade::findOrFail($request->grade_id);
        $data['class'] = $grade->name;
        $data['section'] = $grade->section;

        // Auto-generate roll number if not provided
        if (empty($data['roll_number'])) {
            $maxRoll = Student::where('school_id', $request->school_id)
                ->where('grade_id', $request->grade_id)
                ->max(DB::raw('CAST(roll_number AS UNSIGNED)'));
            
            $data['roll_number'] = $maxRoll ? $maxRoll + 1 : 1;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $student = new Student();
            $data['image'] = $student->uploadImage($request->file('image'), 'students');
        }

        Student::create($data);

        return redirect()->route('students.index')
                        ->with('success','Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['grade', 'marksheets']);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student, Request $request)
    {
        $query = function() use ($request) {
            $q = \App\Models\Grade::query();
            if ($request->has('current_school_id')) {
                $q->where('school_id', $request->get('current_school_id'));
            }
            return $q;
        };
        
        $grades = $query()->get();
        return view('students.edit', compact('student', 'grades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'grade_id' => 'required|exists:grades,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Add current school context
        $data = $request->all();
        if ($request->has('current_school_id')) {
            $data['school_id'] = $request->get('current_school_id');
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $student->uploadImage($request->file('image'), 'students', $student->image);
        }

        $student->update($data);

        return redirect()->route('students.index')
                        ->with('success','Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Delete student image if exists
        if ($student->image) {
            $student->deleteImage($student->image);
        }

        $student->delete();

        return redirect()->route('students.index')
                        ->with('success','Student deleted successfully.');
    }

    /**
     * Remove student image
     */
    public function removeImage(Student $student)
    {
        if ($student->image) {
            $student->deleteImage($student->image);
            $student->update(['image' => null]);
        }

        return redirect()->back()->with('success', 'Student image removed successfully.');
    }
}
