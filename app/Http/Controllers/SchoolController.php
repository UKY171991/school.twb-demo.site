<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = School::latest()->paginate(10);
        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:schools,email',
            'website' => 'nullable|url',
            'principal_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $school = new School();
            $data['logo'] = $school->uploadImage($request->file('logo'), 'schools/logos');
        }

        School::create($data);

        return redirect()->route('schools.index')
                        ->with('success', 'School created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        $school->load(['students', 'teachers', 'grades', 'subjects']);
        
        $stats = [
            'students_count' => $school->getActiveStudentsCount(),
            'teachers_count' => $school->getActiveTeachersCount(),
            'grades_count' => $school->getGradesCount(),
            'subjects_count' => $school->subjects()->count()
        ];

        return view('schools.show', compact('school', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code,' . $school->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:schools,email,' . $school->id,
            'website' => 'nullable|url',
            'principal_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $school->uploadImage($request->file('logo'), 'schools/logos', $school->logo);
        }

        $school->update($data);

        return redirect()->route('schools.index')
                        ->with('success', 'School updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        // Check if school has any associated data
        if ($school->students()->count() > 0 || $school->teachers()->count() > 0) {
            return redirect()->route('schools.index')
                           ->with('error', 'Cannot delete school with existing students or teachers. Please transfer them first.');
        }

        // Delete school logo if exists
        if ($school->logo) {
            $school->deleteImage($school->logo);
        }

        $school->delete();

        return redirect()->route('schools.index')
                        ->with('success', 'School deleted successfully!');
    }

    /**
     * Switch to a different school context
     */
    public function switchSchool(Request $request, School $school)
    {
        session(['current_school_id' => $school->id]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Switched to ' . $school->name,
                'school' => [
                    'id' => $school->id,
                    'name' => $school->name
                ]
            ]);
        }
        
        return redirect()->back()
                        ->with('success', 'Switched to ' . $school->name);
    }

    /**
     * Remove school logo
     */
    public function removeLogo(School $school)
    {
        if ($school->logo) {
            $school->deleteImage($school->logo);
            $school->update(['logo' => null]);
        }

        return redirect()->back()->with('success', 'School logo removed successfully.');
    }
}