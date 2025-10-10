<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'school'])->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $schools = School::where('is_active', true)->get();
        return view('admin.teachers.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'school_id' => 'required|exists:schools,id',
            'employee_id' => 'required|string|max:50|unique:teachers',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'subject_specialization' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'teacher',
            'school_id' => $request->school_id,
            'is_active' => $request->is_active ?? true
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'school_id' => $request->school_id,
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'qualification' => $request->qualification,
            'experience_years' => $request->experience_years,
            'salary' => $request->salary,
            'joining_date' => $request->joining_date,
            'subject_specialization' => $request->subject_specialization,
            'is_active' => $request->is_active ?? true
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully',
                'data' => $teacher->load('user')
            ]);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'school', 'classes']);
        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $schools = School::where('is_active', true)->get();
        $teacher->load('user');
        return view('admin.teachers.edit', compact('teacher', 'schools'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'school_id' => 'required|exists:schools,id',
            'employee_id' => 'required|string|max:50|unique:teachers,employee_id,' . $teacher->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'subject_specialization' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'school_id' => $request->school_id,
            'is_active' => $request->is_active
        ]);

        $teacher->update($request->except(['name', 'email']));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully',
                'data' => $teacher->load('user')
            ]);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user->delete();
        $teacher->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully'
            ]);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    public function toggleStatus(Teacher $teacher)
    {
        $teacher->update(['is_active' => !$teacher->is_active]);
        $teacher->user->update(['is_active' => !$teacher->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Teacher status updated successfully',
            'is_active' => $teacher->is_active
        ]);
    }
}
