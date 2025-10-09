<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $students = Student::with(['user', 'class', 'section'])
            ->where('school_id', $schoolId)
            ->latest()
            ->paginate(20);
        
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassModel::where('school_id', $schoolId)->where('is_active', true)->get();
        $sections = Section::whereHas('class', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->get();
        $guardians = User::where('school_id', $schoolId)
            ->where('role_id', 5) // Guardian role
            ->get();
        
        return view('admin.students.create', compact('classes', 'sections', 'guardians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'admission_number' => 'required|string|unique:students,admission_number',
            'admission_date' => 'required|date',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'parent_id' => 'nullable|exists:users,id',
            'religion' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        // Create user account for student
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'), // Default password
            'role_id' => 4, // Student role
            'school_id' => auth()->user()->school_id,
            'phone' => $request->phone,
            'address' => $validated['address'],
            'is_active' => true,
        ]);

        // Create student record
        Student::create([
            'user_id' => $user->id,
            'school_id' => auth()->user()->school_id,
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'],
            'parent_id' => $validated['parent_id'],
            'admission_number' => $validated['admission_number'],
            'admission_date' => $validated['admission_date'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'blood_group' => $validated['blood_group'],
            'religion' => $validated['religion'],
            'nationality' => $validated['nationality'],
            'address' => $validated['address'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully!');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'class', 'section', 'parent']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassModel::where('school_id', $schoolId)->where('is_active', true)->get();
        $sections = Section::whereHas('class', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->get();
        $guardians = User::where('school_id', $schoolId)->where('role_id', 5)->get();
        
        return view('admin.students.edit', compact('student', 'classes', 'sections', 'guardians'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'admission_number' => 'required|string|unique:students,admission_number,' . $student->id,
            'admission_date' => 'required|date',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'parent_id' => 'nullable|exists:users,id',
            'religion' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        // Update user
        $student->user->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $request->phone,
            'address' => $validated['address'],
        ]);

        // Update student
        $student->update([
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'],
            'parent_id' => $validated['parent_id'],
            'admission_number' => $validated['admission_number'],
            'admission_date' => $validated['admission_date'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'blood_group' => $validated['blood_group'],
            'religion' => $validated['religion'],
            'nationality' => $validated['nationality'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        $student->user->delete();
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully!');
    }
}
