<?php

namespace App\Http\Controllers;

use App\Models\ExamType;
use Illuminate\Http\Request;

class ExamTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\SchoolContext::class);
    }

    public function index()
    {
        $schoolId = session('current_school_id');
        $examTypes = ExamType::where('school_id', $schoolId)
            ->orderBy('sort_order')->orderBy('name')->get();

        return view('exam-types.index', compact('examTypes'));
    }

    public function create()
    {
        return view('exam-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:exam_types,code',
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
            'weightage' => 'required|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        ExamType::create($request->all());

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
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:exam_types,code,'.$examType->id,
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
