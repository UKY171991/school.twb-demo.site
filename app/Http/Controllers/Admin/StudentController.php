<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\School;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DataTables;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Student::with(['user', 'school', 'classModel'])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $statusBtn = '<button class="btn btn-sm toggle-status-btn '.($row->is_active ? 'btn-success' : 'btn-danger').'" data-url="'.route('admin.students.toggle-status', $row).'">';
                    $statusBtn .= '<i class="fas '.($row->is_active ? 'fa-toggle-on' : 'fa-toggle-off').'"></i> ';
                    $statusBtn .= '<span>'.($row->is_active ? 'Active' : 'Inactive').'</span>';
                    $statusBtn .= '</button>';
                    return $statusBtn;
                })
                ->addColumn('actions', function($row){
                    $actionBtn = '<div class="btn-group" role="group">';
                    $actionBtn .= '<a href="'.route('admin.students.show', $row).'" class="btn btn-info btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a>';
                    $actionBtn .= '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="'.$row->id.'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></button>';
                    $actionBtn .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-url="'.route('admin.students.destroy', $row).'" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.students.index');
    }

    public function create()
    {
        $schools = School::where('is_active', true)->get();
        $classes = ClassModel::where('is_active', true)->get();
        return view('admin.students._form', compact('schools', 'classes'))->render();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'school_id' => 'required|exists:schools,id',
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|string|max:50|unique:students',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'admission_date' => 'required|date',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'student',
            'school_id' => $request->school_id,
            'is_active' => $request->is_active ?? true
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'school_id' => $request->school_id,
            'class_id' => $request->class_id,
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'admission_date' => $request->admission_date,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
            'is_active' => $request->is_active ?? true
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'data' => $student->load('user')
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'school', 'classModel', 'attendance', 'grades']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $schools = School::where('is_active', true)->get();
        $classes = ClassModel::where('is_active', true)->get();
        $student->load('user');
        return view('admin.students._form', compact('student', 'schools', 'classes'))->render();
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'school_id' => 'required|exists:schools,id',
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|string|max:50|unique:students,student_id,' . $student->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'admission_date' => 'required|date',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'school_id' => $request->school_id,
            'is_active' => $request->is_active
        ]);

        if ($request->password) {
            $student->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $student->update($request->except(['name', 'email', 'password']));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'data' => $student->load('user')
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->user->delete();
        $student->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully'
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function toggleStatus(Student $student)
    {
        $student->update(['is_active' => !$student->is_active]);
        $student->user->update(['is_active' => !$student->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Student status updated successfully',
            'is_active' => $student->is_active
        ]);
    }
}


