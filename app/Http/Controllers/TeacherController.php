<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\School;
use Illuminate\Http\Request;

class TeacherController extends Controller
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
        $query = Teacher::where('school_id', $schoolId);

        $teachers = $query->latest()->paginate(10);

        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::where('status', 'active')->orderBy('name')->get();
        return view('teachers.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'date_of_joining' => 'nullable|date',
            'address' => 'nullable|string',
            'school_id' => 'required|exists:schools,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'designation' => 'nullable|string|max:100',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $teacher = new Teacher;
            $data['image'] = $teacher->uploadImage($request->file('image'), 'teachers');
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $teacher = new Teacher;
            $data['signature'] = $teacher->uploadImage($request->file('signature'), 'signatures');
        }

        Teacher::create($data);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $schools = School::where('status', 'active')->orderBy('name')->get();

        return view('teachers.edit', compact('teacher', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'date_of_joining' => 'nullable|date',
            'address' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'designation' => 'nullable|string|max:100',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $teacher = Teacher::findOrFail($id);

        // Add current school context
        $data = $request->all();
        if ($request->has('current_school_id')) {
            $data['school_id'] = $request->get('current_school_id');
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $teacher->uploadImage($request->file('image'), 'teachers', $teacher->image);
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $data['signature'] = $teacher->uploadImage($request->file('signature'), 'signatures', $teacher->signature);
        }

        $teacher->update($data);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);

        // Delete teacher image if exists
        if ($teacher->image) {
            $teacher->deleteImage($teacher->image);
        }

        $teacher->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    /**
     * Remove teacher image
     */
    public function removeImage(string $id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->image) {
            $teacher->deleteImage($teacher->image);
            $teacher->update(['image' => null]);
        }

        return redirect()->back()->with('success', 'Teacher image removed successfully.');
    }

    /**
     * Remove teacher signature
     */
    public function removeSignature(string $id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->signature) {
            $teacher->deleteImage($teacher->signature);
            $teacher->update(['signature' => null]);
        }

        return redirect()->back()->with('success', 'Teacher signature removed successfully.');
    }
}
