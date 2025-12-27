<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roll_number' => 'required|string|unique:students,roll_number',
            'class' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')
                        ->with('success','Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('marksheets');
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roll_number' => 'required|string|unique:students,roll_number,' . $student->id,
            'class' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')
                        ->with('success','Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
                        ->with('success','Student deleted successfully.');
    }
}
