<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuardianController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $guardians = User::with('students')
            ->where('school_id', $schoolId)
            ->where('role_id', 5) // Guardian role
            ->latest()
            ->paginate(20);
        
        return view('admin.guardians.index', compact('guardians'));
    }

    public function create()
    {
        $schoolId = auth()->user()->school_id;
        $students = Student::where('school_id', $schoolId)->with('user')->get();
        
        return view('admin.guardians.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'occupation' => 'nullable|string|max:255',
            'relationship' => 'required|string|max:50',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        // Create guardian user
        $guardian = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'), // Default password
            'role_id' => 5, // Guardian role
            'school_id' => auth()->user()->school_id,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_active' => true,
        ]);

        // Update students with guardian
        Student::whereIn('id', $validated['student_ids'])
            ->update(['parent_id' => $guardian->id]);

        return redirect()->route('admin.guardians.index')->with('success', 'Guardian added successfully!');
    }

    public function show(User $guardian)
    {
        $guardian->load('students.user');
        return view('admin.guardians.show', compact('guardian'));
    }

    public function edit(User $guardian)
    {
        $schoolId = auth()->user()->school_id;
        $students = Student::where('school_id', $schoolId)->with('user')->get();
        
        return view('admin.guardians.edit', compact('guardian', 'students'));
    }

    public function update(Request $request, User $guardian)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $guardian->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'occupation' => 'nullable|string|max:255',
            'relationship' => 'required|string|max:50',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $guardian->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // Update student relationships
        Student::where('parent_id', $guardian->id)->update(['parent_id' => null]);
        Student::whereIn('id', $validated['student_ids'])
            ->update(['parent_id' => $guardian->id]);

        return redirect()->route('admin.guardians.index')->with('success', 'Guardian updated successfully!');
    }

    public function destroy(User $guardian)
    {
        // Remove guardian from students
        Student::where('parent_id', $guardian->id)->update(['parent_id' => null]);
        
        $guardian->delete();
        return redirect()->route('admin.guardians.index')->with('success', 'Guardian deleted successfully!');
    }
}
