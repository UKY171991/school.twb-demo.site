<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\School;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['school', 'teacher.user'])->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $schools = School::where('is_active', true)->get();
        $teachers = Teacher::with('user')->where('is_active', true)->get();
        return view('admin.subjects.create', compact('schools', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $subject = Subject::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subject created successfully',
                'data' => $subject->load(['school', 'teacher.user'])
            ]);
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['school', 'teacher.user', 'grades']);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $schools = School::where('is_active', true)->get();
        $teachers = Teacher::with('user')->where('is_active', true)->get();
        return view('admin.subjects.edit', compact('subject', 'schools', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $subject->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully',
                'data' => $subject->load(['school', 'teacher.user'])
            ]);
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully'
            ]);
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    public function toggleStatus(Subject $subject)
    {
        $subject->update(['is_active' => !$subject->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Subject status updated successfully',
            'is_active' => $subject->is_active
        ]);
    }
}
