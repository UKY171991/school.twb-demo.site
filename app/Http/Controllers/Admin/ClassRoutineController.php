<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoutine;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassRoutineController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $routines = ClassRoutine::with(['class', 'subject', 'teacher.user'])
            ->where('school_id', $schoolId)
            ->latest()
            ->paginate(20);
        
        return view('admin.class-routines.index', compact('routines'));
    }

    public function create()
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassModel::where('school_id', $schoolId)->where('is_active', true)->get();
        $subjects = Subject::where('school_id', $schoolId)->where('is_active', true)->get();
        $teachers = Teacher::where('school_id', $schoolId)->where('is_active', true)->with('user')->get();
        
        return view('admin.class-routines.create', compact('classes', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,user_id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
        ]);

        ClassRoutine::create([
            'school_id' => auth()->user()->school_id,
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'room_number' => $validated['room_number'],
            'academic_year' => $validated['academic_year'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.class-routines.index')->with('success', 'Class routine added successfully!');
    }

    public function show(ClassRoutine $classRoutine)
    {
        $classRoutine->load(['class', 'subject', 'teacher.user']);
        return view('admin.class-routines.show', compact('classRoutine'));
    }

    public function edit(ClassRoutine $classRoutine)
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassModel::where('school_id', $schoolId)->where('is_active', true)->get();
        $subjects = Subject::where('school_id', $schoolId)->where('is_active', true)->get();
        $teachers = Teacher::where('school_id', $schoolId)->where('is_active', true)->with('user')->get();
        
        return view('admin.class-routines.edit', compact('classRoutine', 'classes', 'subjects', 'teachers'));
    }

    public function update(Request $request, ClassRoutine $classRoutine)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,user_id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
        ]);

        $classRoutine->update($validated);

        return redirect()->route('admin.class-routines.index')->with('success', 'Class routine updated successfully!');
    }

    public function destroy(ClassRoutine $classRoutine)
    {
        $classRoutine->delete();
        return redirect()->route('admin.class-routines.index')->with('success', 'Class routine deleted successfully!');
    }
}
