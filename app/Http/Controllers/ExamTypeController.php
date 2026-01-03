<?php

namespace App\Http\Controllers;

use App\Models\ExamType;
use Illuminate\Http\Request;

class ExamTypeController extends Controller
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

        $examTypes = ExamType::where('school_id', $schoolId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('exam-types.index', compact('examTypes'));
    }

    public function create(Request $request)
    {
        return view('exam-types.create');
    }

    public function store(Request $request)
    {
        $schoolId = $request->current_school_id;

        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                // Unique code within the same school
                \Illuminate\Validation\Rule::unique('exam_types')->where(function ($query) use ($schoolId) {
                    return $query->where('school_id', $schoolId);
                }),
            ],
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'weightage' => 'required|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['school_id'] = $schoolId;

        ExamType::create($data);

        return redirect()->route('exam-types.index')
            ->with('success', 'Exam type created successfully.');
    }

    public function show(ExamType $examType)
    {
        return view('exam-types.show', compact('examType'));
    }

    public function edit(ExamType $examType)
    {
        return view('exam-types.edit', compact('examType'));
    }

    public function update(Request $request, ExamType $examType)
    {
        $schoolId = $request->current_school_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                // Unique code within the same school, excluding current ID
                \Illuminate\Validation\Rule::unique('exam_types')->where(function ($query) use ($schoolId) {
                    return $query->where('school_id', $schoolId);
                })->ignore($examType->id),
            ],
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'weightage' => 'required|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $examType->update($request->all());

        return redirect()->route('exam-types.index')
            ->with('success', 'Exam type updated successfully.');
    }

    public function destroy(ExamType $examType)
    {
        $examType->delete();

        return redirect()->route('exam-types.index')
            ->with('success', 'Exam type deleted successfully.');
    }

    public function toggleStatus(ExamType $examType)
    {
        $examType->update(['is_active' => ! $examType->is_active]);

        return redirect()->route('exam-types.index')
            ->with('success', 'Exam type status updated successfully.');
    }
}
