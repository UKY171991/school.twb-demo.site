<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Grade::with(['student.user', 'subject', 'classModel'])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('marks', function($row){
                    return $row->marks_obtained . ' / ' . $row->total_marks;
                })
                ->editColumn('exam_date', function ($row) {
                    return Carbon::parse($row->exam_date)->format('d M, Y');
                })
                ->addColumn('actions', function($row){
                    $actionBtn = '<div class="btn-group" role="group">';
                    $actionBtn .= '<a href="'.route('admin.grades.show', $row).'" class="btn btn-info btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a>';
                    $actionBtn .= '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="'.$row->id.'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></button>';
                    $actionBtn .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-url="'.route('admin.grades.destroy', $row).'" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.grades.index');
    }

    public function create()
    {
        $students = Student::with('user')->where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $classes = ClassModel::where('is_active', true)->get();
        return view('admin.grades._form', compact('students', 'subjects', 'classes'))->render();
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

        $grade = Grade::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade recorded successfully',
                'data' => $grade->load(['student.user', 'subject', 'classModel'])
            ]);
        }

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade recorded successfully.');
    }

    public function show(Grade $grade)
    {
        $grade->load(['student.user', 'subject', 'classModel']);
        return view('admin.grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $students = Student::with('user')->where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $classes = ClassModel::where('is_active', true)->get();
        return view('admin.grades._form', compact('grade', 'students', 'subjects', 'classes'))->render();
    }

    public function update(Request $request, Grade $grade)
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

        $grade->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade updated successfully',
                'data' => $grade->load(['student.user', 'subject', 'classModel'])
            ]);
        }

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade deleted successfully'
            ]);
        }

        return redirect()->route('admin.grades.index')
            ->with('success', 'Grade deleted successfully.');
    }

    public function reports()
    {
        $classes = ClassModel::with('school')->where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.grades.reports', compact('classes', 'subjects'));
    }

    public function getStudentGrades(Request $request)
    {
        $grades = Grade::with(['subject'])
            ->where('student_id', $request->student_id)
            ->where('class_id', $request->class_id)
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    public function getClassGrades(Request $request)
    {
        $grades = Grade::with(['student.user', 'subject'])
            ->where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }
}
