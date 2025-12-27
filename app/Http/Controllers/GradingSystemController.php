<?php

namespace App\Http\Controllers;

use App\Models\GradingSystem;
use Illuminate\Http\Request;

class GradingSystemController extends Controller
{
    public function index()
    {
        $gradingSystems = GradingSystem::orderBy('sort_order')->orderBy('min_percentage', 'desc')->get();
        return view('settings.grading-system.index', compact('gradingSystems'));
    }

    public function create()
    {
        return view('settings.grading-system.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:10',
            'min_percentage' => 'required|numeric|min:0|max:100',
            'max_percentage' => 'required|numeric|min:0|max:100|gte:min_percentage',
            'grade_points' => 'nullable|numeric|min:0|max:10',
            'description' => 'nullable|string',
            'is_passing' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        GradingSystem::create($request->all());

        return redirect()->route('grading-systems.index')
                        ->with('success', 'Grading system created successfully.');
    }

    public function show(GradingSystem $gradingSystem)
    {
        return view('settings.grading-system.show', compact('gradingSystem'));
    }

    public function edit(GradingSystem $gradingSystem)
    {
        return view('settings.grading-system.edit', compact('gradingSystem'));
    }

    public function update(Request $request, GradingSystem $gradingSystem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:10',
            'min_percentage' => 'required|numeric|min:0|max:100',
            'max_percentage' => 'required|numeric|min:0|max:100|gte:min_percentage',
            'grade_points' => 'nullable|numeric|min:0|max:10',
            'description' => 'nullable|string',
            'is_passing' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        $gradingSystem->update($request->all());

        return redirect()->route('grading-systems.index')
                        ->with('success', 'Grading system updated successfully.');
    }

    public function destroy(GradingSystem $gradingSystem)
    {
        $gradingSystem->delete();

        return redirect()->route('grading-systems.index')
                        ->with('success', 'Grading system deleted successfully.');
    }

    public function toggleStatus(GradingSystem $gradingSystem)
    {
        $gradingSystem->update(['is_active' => !$gradingSystem->is_active]);

        return redirect()->route('grading-systems.index')
                        ->with('success', 'Grading system status updated successfully.');
    }
}
