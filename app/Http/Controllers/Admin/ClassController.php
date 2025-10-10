<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\School;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with(['school', 'teacher.user', 'students'])->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $schools = School::where('is_active', true)->get();
        $teachers = Teacher::with('user')->where('is_active', true)->get();
        return view('admin.classes.create', compact('schools', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $class = ClassModel::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Class created successfully',
                'data' => $class->load(['school', 'teacher.user'])
            ]);
        }

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(ClassModel $class)
    {
        $class->load(['school', 'teacher.user', 'students.user', 'subjects']);
        return view('admin.classes.show', compact('class'));
    }

    public function edit(ClassModel $class)
    {
        $schools = School::where('is_active', true)->get();
        $teachers = Teacher::with('user')->where('is_active', true)->get();
        return view('admin.classes.edit', compact('class', 'schools', 'teachers'));
    }

    public function update(Request $request, ClassModel $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $class->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully',
                'data' => $class->load(['school', 'teacher.user'])
            ]);
        }

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassModel $class)
    {
        $class->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Class deleted successfully'
            ]);
        }

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function toggleStatus(ClassModel $class)
    {
        $class->update(['is_active' => !$class->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Class status updated successfully',
            'is_active' => $class->is_active
        ]);
    }
}
