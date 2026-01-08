<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\School;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct()
    {
        // Middleware is already applied in bootstrap/app.php
    }

    public function index(Request $request)
    {
        $schoolId = $request->current_school_id;
        
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $teachers = Teacher::where('school_id', $schoolId)->with('school')->latest()->get();

        if ($request->ajax()) {
            return response()->json(['data' => $teachers]);
        }

        return view('teachers.index', compact('teachers'));
    }

    public function create(Request $request)
    {
        $schools = School::where('status', 'active')->orderBy('name')->get();

        if ($request->ajax()) {
            return view('teachers.create', compact('schools'))->renderSections()['content'];
        }
        
        return view('teachers.create', compact('schools'));
    }

    public function store(StoreTeacherRequest $request)
    {
        $data = $request->validated();
        $data['school_id'] = $request->current_school_id;

        if ($request->hasFile('image')) {
            $teacher = new Teacher;
            $data['image'] = $teacher->uploadImage($request->file('image'), 'teachers');
        }

        if ($request->hasFile('signature')) {
            $teacher = new Teacher;
            $data['signature'] = $teacher->uploadImage($request->file('signature'), 'signatures');
        }

        $teacher = Teacher::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Teacher created successfully.',
            'teacher' => $teacher->load('school')
        ]);
    }

    public function show(Request $request, Teacher $teacher)
    {
        if ($request->ajax()) {
            return view('teachers.show', compact('teacher'))->renderSections()['content'];
        }

        return view('teachers.show', compact('teacher'));
    }

    public function edit(Request $request, Teacher $teacher)
    {
        $schools = School::where('status', 'active')->orderBy('name')->get();

        if ($request->ajax()) {
            return view('teachers.edit', compact('teacher', 'schools'))->renderSections()['content'];
        }

        return view('teachers.edit', compact('teacher', 'schools'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image'] = $teacher->uploadImage($request->file('image'), 'teachers', $teacher->image);
        }

        if ($request->hasFile('signature')) {
            $data['signature'] = $teacher->uploadImage($request->file('signature'), 'signatures', $teacher->signature);
        }

        $teacher->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully.',
            'teacher' => $teacher->load('school')
        ]);
    }

    public function destroy(Request $request, Teacher $teacher)
    {
        if ($teacher->subjects()->count() > 0) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete teacher. Teacher has subjects assigned.'
            ], 422);
        }

        if ($teacher->image) {
            $teacher->deleteImage($teacher->image);
        }

        if ($teacher->signature) {
            $teacher->deleteImage($teacher->signature);
        }

        $teacher->delete();

        return response()->json(['success' => true, 'message' => 'Teacher deleted successfully.']);
    }
    
    public function removeImage(Teacher $teacher)
    {
        if ($teacher->image) {
            $teacher->deleteImage($teacher->image);
            $teacher->update(['image' => null]);
        }

        return redirect()->back()->with('success', 'Teacher image removed successfully.');
    }

    public function removeSignature(Teacher $teacher)
    {
        if ($teacher->signature) {
            $teacher->deleteImage($teacher->signature);
            $teacher->update(['signature' => null]);
        }

        return redirect()->back()->with('success', 'Teacher signature removed successfully.');
    }
}