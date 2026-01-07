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
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('schools.create')->renderSections()['content'];
        }
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
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
            'principal_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'exam_controller_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $school = new School;
            $data['logo'] = $school->uploadImage($request->file('logo'), 'schools/logos');
        }

        // Handle signatures
        if ($request->hasFile('principal_signature')) {
            $school = new School;
            $data['principal_signature'] = $school->uploadImage($request->file('principal_signature'), 'schools/signatures');
        }

        if ($request->hasFile('exam_controller_signature')) {
            $school = new School;
            $data['exam_controller_signature'] = $school->uploadImage($request->file('exam_controller_signature'), 'schools/signatures');
        }

        School::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'School created successfully!']);
        }

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
            'subjects_count' => $school->subjects()->count(),
        ];

        return view('schools.show', compact('school', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school, Request $request)
    {
        if ($request->ajax()) {
            return view('schools.edit', compact('school'))->renderSections()['content'];
        }
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code,'.$school->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:schools,email,'.$school->id,
            'website' => 'nullable|url',
            'principal_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'principal_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'exam_controller_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $school->uploadImage($request->file('logo'), 'schools/logos', $school->logo);
        }

        // Handle signatures
        if ($request->hasFile('principal_signature')) {
            $data['principal_signature'] = $school->uploadImage($request->file('principal_signature'), 'schools/signatures', $school->principal_signature);
        }

        if ($request->hasFile('exam_controller_signature')) {
            $data['exam_controller_signature'] = $school->uploadImage($request->file('exam_controller_signature'), 'schools/signatures', $school->exam_controller_signature);
        }

        $school->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'School updated successfully!']);
        }

        return redirect()->route('schools.index')
            ->with('success', 'School updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, School $school)
    {
        // Check if school has any associated data
        if ($school->students()->count() > 0 || $school->teachers()->count() > 0) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete school with existing students or teachers. Please transfer them first.'], 422);
            }
            return redirect()->route('schools.index')
                ->with('error', 'Cannot delete school with existing students or teachers. Please transfer them first.');
        }

        // Delete school logo if exists
        if ($school->logo) {
            $school->deleteImage($school->logo);
        }

        $school->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'School deleted successfully!']);
        }

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
                'message' => 'Switched to '.$school->name,
                'school' => [
                    'id' => $school->id,
                    'name' => $school->name,
                ],
            ]);
        }

        return redirect()->back()
            ->with('success', 'Switched to '.$school->name);
    }

    public function removeLogo(School $school)
    {
        if ($school->logo) {
            $school->deleteImage($school->logo);
            $school->update(['logo' => null]);
        }

        return redirect()->back()->with('success', 'School logo removed successfully.');
    }

    /**
     * Remove principal signature
     */
    public function removePrincipalSignature(School $school)
    {
        if ($school->principal_signature) {
            $school->deleteImage($school->principal_signature);
            $school->update(['principal_signature' => null]);
        }

        return redirect()->back()->with('success', 'Principal signature removed successfully.');
    }

    /**
     * Remove exam controller signature
     */
    public function removeExamControllerSignature(School $school)
    {
        if ($school->exam_controller_signature) {
            $school->deleteImage($school->exam_controller_signature);
            $school->update(['exam_controller_signature' => null]);
        }

        return redirect()->back()->with('success', 'Exam controller signature removed successfully.');
    }
}
