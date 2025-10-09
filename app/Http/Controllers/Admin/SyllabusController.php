<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Syllabus;
use App\Models\ClassModel;
use App\Models\Subject;
use Illuminate\Http\Request;

class SyllabusController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $syllabi = Syllabus::with(['class', 'subject'])
            ->where('school_id', $schoolId)
            ->latest()
            ->paginate(20);
        
        return view('admin.syllabus.index', compact('syllabi'));
    }

    public function create()
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassModel::where('school_id', $schoolId)->where('is_active', true)->get();
        $subjects = Subject::where('school_id', $schoolId)->where('is_active', true)->get();
        
        return view('admin.syllabus.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'academic_year' => 'required|string|max:20',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('syllabus', 'public');
        }

        Syllabus::create([
            'school_id' => auth()->user()->school_id,
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'academic_year' => $validated['academic_year'],
            'file_path' => $filePath,
            'is_active' => true,
        ]);

        return redirect()->route('admin.syllabus.index')->with('success', 'Syllabus added successfully!');
    }

    public function show(Syllabus $syllabus)
    {
        $syllabus->load(['class', 'subject']);
        return view('admin.syllabus.show', compact('syllabus'));
    }

    public function edit(Syllabus $syllabus)
    {
        $schoolId = auth()->user()->school_id;
        $classes = ClassModel::where('school_id', $schoolId)->where('is_active', true)->get();
        $subjects = Subject::where('school_id', $schoolId)->where('is_active', true)->get();
        
        return view('admin.syllabus.edit', compact('syllabus', 'classes', 'subjects'));
    }

    public function update(Request $request, Syllabus $syllabus)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'academic_year' => 'required|string|max:20',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $filePath = $syllabus->file_path;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('syllabus', 'public');
        }

        $syllabus->update([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'academic_year' => $validated['academic_year'],
            'file_path' => $filePath,
        ]);

        return redirect()->route('admin.syllabus.index')->with('success', 'Syllabus updated successfully!');
    }

    public function destroy(Syllabus $syllabus)
    {
        $syllabus->delete();
        return redirect()->route('admin.syllabus.index')->with('success', 'Syllabus deleted successfully!');
    }
}
