<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::withCount(['teachers', 'students'])->paginate(10);
        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:schools',
            'principal_name' => 'required|string|max:255',
            'established_year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $school = School::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'School created successfully',
                'data' => $school
            ]);
        }

        return redirect()->route('admin.schools.index')
            ->with('success', 'School created successfully.');
    }

    public function show(School $school)
    {
        $school->load(['teachers', 'students']);
        return view('admin.schools.show', compact('school'));
    }

    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:schools,email,' . $school->id,
            'principal_name' => 'required|string|max:255',
            'established_year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $school->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'School updated successfully',
                'data' => $school
            ]);
        }

        return redirect()->route('admin.schools.index')
            ->with('success', 'School updated successfully.');
    }

    public function destroy(School $school)
    {
        $school->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'School deleted successfully'
            ]);
        }

        return redirect()->route('admin.schools.index')
            ->with('success', 'School deleted successfully.');
    }

    public function toggleStatus(School $school)
    {
        $school->update(['is_active' => !$school->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'School status updated successfully',
            'is_active' => $school->is_active
        ]);
    }
}
