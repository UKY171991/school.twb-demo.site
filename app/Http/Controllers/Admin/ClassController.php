<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\School;
use App\Models\Teacher;
use Illuminate\Http\Request;
use DataTables;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ClassModel::with(['school', 'teacher.user'])->withCount('students')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $statusBtn = '<button class="btn btn-sm toggle-status-btn '.($row->is_active ? 'btn-success' : 'btn-danger').'" data-url="'.route('admin.classes.toggle-status', $row).'">';
                    $statusBtn .= '<i class="fas '.($row->is_active ? 'fa-toggle-on' : 'fa-toggle-off').'"></i> ';
                    $statusBtn .= '<span>'.($row->is_active ? 'Active' : 'Inactive').'</span>';
                    $statusBtn .= '</button>';
                    return $statusBtn;
                })
                ->addColumn('actions', function($row){
                    $actionBtn = '<div class="btn-group" role="group">';
                    $actionBtn .= '<a href="'.route('admin.classes.show', $row).'" class="btn btn-info btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a>';
                    $actionBtn .= '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="'.$row->id.'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></button>';
                    $actionBtn .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-url="'.route('admin.classes.destroy', $row).'" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.classes.index');
    }

    public function create()
    {
        $schools = School::where('is_active', true)->get();
        $teachers = Teacher::with('user')->where('is_active', true)->get();
        return view('admin.classes._form', compact('schools', 'teachers'))->render();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $class = ClassModel::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Class created successfully',
                'data' => $class->load(['school', 'teacher.user'])
            ]);
        }

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(ClassModel $class)
    {
        $class->load(['school', 'teacher.user', 'students.user', 'subjects']);
        return view('admin.classes.show', compact('class'));
    }

    public function edit(ClassModel $class)
    {
        $schools = School::where('is_active', true)->get();
        $teachers = Teacher::with('user')->where('is_active', true)->get();
        return view('admin.classes._form', compact('class', 'schools', 'teachers'))->render();
    }

    public function update(Request $request, ClassModel $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $class->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully',
                'data' => $class->load(['school', 'teacher.user'])
            ]);
        }

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassModel $class)
    {
        $class->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Class deleted successfully'
            ]);
        }

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function toggleStatus(ClassModel $class)
    {
        $class->update(['is_active' => !$class->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Class status updated successfully',
            'is_active' => $class->is_active
        ]);
    }
}
