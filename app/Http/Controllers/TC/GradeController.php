<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        $subjectIds = Subject::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $grades = Grade::with(['student.user', 'subject', 'classModel'])
            ->whereIn('subject_id', $subjectIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tc.grades.index', compact('grades'));
    }

    public function create()
    {
        $teacher = auth()->user()->teacher;
        $subjects = Subject::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $students = Student::with('user')
            ->whereIn('class_id', $classIds)
            ->where('is_active', true)
            ->get();

        $classes = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        return view('tc.grades.create', compact('subjects', 'students', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'exam_type' => 'required|in:quiz,midterm,final,assignment,project',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'grade' => 'required|string|max:5',
            'remarks' => 'nullable|string|max:255',
            'exam_date' => 'required|date'
        ]);

        // Verify teacher has access to this subject
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $grade = Grade::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade recorded successfully',
                'data' => $grade->load(['student.user', 'subject', 'classModel'])
            ]);
        }

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Grade recorded successfully.');
    }

    public function show(Grade $grade)
    {
        // Verify teacher has access to this grade
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $grade->subject_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$subject) {
            abort(403, 'Unauthorized access to this grade.');
        }

        $grade->load(['student.user', 'subject', 'classModel']);
        return view('tc.grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        // Verify teacher has access to this grade
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $grade->subject_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$subject) {
            abort(403, 'Unauthorized access to this grade.');
        }

        $subjects = Subject::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $students = Student::with('user')
            ->whereIn('class_id', $classIds)
            ->where('is_active', true)
            ->get();

        $classes = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        return view('tc.grades.edit', compact('grade', 'subjects', 'students', 'classes'));
    }

    public function update(Request $request, Grade $grade)
    {
        // Verify teacher has access to this grade
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $grade->subject_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$subject) {
            abort(403, 'Unauthorized access to this grade.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'exam_type' => 'required|in:quiz,midterm,final,assignment,project',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'grade' => 'required|string|max:5',
            'remarks' => 'nullable|string|max:255',
            'exam_date' => 'required|date'
        ]);

        $grade->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade updated successfully',
                'data' => $grade->load(['student.user', 'subject', 'classModel'])
            ]);
        }

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        // Verify teacher has access to this grade
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $grade->subject_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$subject) {
            abort(403, 'Unauthorized access to this grade.');
        }

        $grade->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade deleted successfully'
            ]);
        }

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Grade deleted successfully.');
    }

    public function getStudentGrades(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $grades = Grade::with(['student.user'])
            ->where('student_id', $request->student_id)
            ->where('subject_id', $subject->id)
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    public function getClassGrades(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $subject = Subject::where('id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $grades = Grade::with(['student.user'])
            ->where('class_id', $request->class_id)
            ->where('subject_id', $subject->id)
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }
}
