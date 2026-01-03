<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        try {
            $leaves = Leave::with(['student', 'grade'])->orderBy('start_date', 'desc')->paginate(25);
        } catch (QueryException $e) {
            // If the table doesn't exist yet, return an empty paginator so the page doesn't 500
            if (str_contains($e->getMessage(), 'no such table') || !Schema::hasTable('leaves')) {
                $leaves = new LengthAwarePaginator([], 0, 25);
            } else {
                throw $e;
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($leaves);
        }

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $students = Student::orderBy('name')->get();
        $grades = Grade::orderBy('name')->get();
        if (request()->ajax()) {
            return view('leaves._form', compact('students', 'grades'));
        }
        return view('leaves.create', compact('students', 'grades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'grade_id' => 'nullable|exists:grades,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'reason' => 'nullable|string',
            'type' => 'required|string',
        ]);

        $data['created_by'] = auth()->id();

        if (!Schema::hasTable('leaves')) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Leaves table not migrated'], 500);
            }
            return redirect()->back()->withErrors('Leaves table not migrated');
        }

        $leave = Leave::create($data);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'leave' => $leave]);
        }
        return redirect()->route('leaves.index')->with('success', 'Leave created successfully.');
    }

    public function edit(Leave $leave)
    {
        $students = Student::orderBy('name')->get();
        $grades = Grade::orderBy('name')->get();
        if (request()->ajax()) {
            return view('leaves._form', compact('leave', 'students', 'grades'));
        }
        return view('leaves.create', compact('leave', 'students', 'grades'));
    }

    public function update(Request $request, Leave $leave)
    {
        $data = $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'grade_id' => 'nullable|exists:grades,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'reason' => 'nullable|string',
            'type' => 'required|string',
        ]);

        if (!Schema::hasTable('leaves')) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Leaves table not migrated'], 500);
            }
            return redirect()->back()->withErrors('Leaves table not migrated');
        }

        $leave->update($data);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'leave' => $leave]);
        }
        return redirect()->route('leaves.index')->with('success', 'Leave updated successfully.');
    }

    public function destroy(Leave $leave)
    {
        if (!Schema::hasTable('leaves')) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Leaves table not migrated'], 500);
            }
            return redirect()->back()->withErrors('Leaves table not migrated');
        }

        $leave->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('leaves.index')->with('success', 'Leave deleted.');
    }
}
