<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $sections = Section::with('class')
            ->whereHas('class', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->latest()
            ->paginate(20);
        
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassModel::where('school_id', $schoolId)->where('is_active', true)->get();
        
        return view('admin.sections.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        Section::create([
            'class_id' => $validated['class_id'],
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.sections.index')->with('success', 'Section added successfully!');
    }

    public function show(Section $section)
    {
        $section->load('class');
        return view('admin.sections.show', compact('section'));
    }

    public function edit(Section $section)
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassModel::where('school_id', $schoolId)->where('is_active', true)->get();
        
        return view('admin.sections.edit', compact('section', 'classes'));
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        $section->update($validated);

        return redirect()->route('admin.sections.index')->with('success', 'Section updated successfully!');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('admin.sections.index')->with('success', 'Section deleted successfully!');
    }
}
