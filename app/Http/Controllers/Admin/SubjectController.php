<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\School;
use App\Models\Teacher;
use Illuminate\Http\Request;
use DataTables;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Subject::with(['school', 'teacher.user'])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $statusBtn = '<button class="btn btn-sm toggle-status-btn '.($row->is_active ? 'btn-success' : 'btn-danger').'" data-url="'.route('admin.subjects.toggle-status', $row).'">';
                    $statusBtn .= '<i class="fas '.($row->is_active ? 'fa-toggle-on' : 'fa-toggle-off').'"></i> ';
                    $statusBtn .= '<span>'.($row->is_active ? 'Active' : 'Inactive').'</span>';
                    $statusBtn .= '</button>';
                    return $statusBtn;
                })
                ->addColumn('actions', function($row){
                    $actionBtn = '<div class="btn-group" role="group">';
                    $actionBtn .= '<a href="'.route('admin.subjects.show', $row).'" class="btn btn-info btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a>';
                    $actionBtn .= '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="'.$row->id.'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></button>';
                    $actionBtn .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-url="'.route('admin.subjects.destroy', $row).'" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.subjects.index');
    }

    public function create()
    {
        $schools = School::where('is_active', true)->get();
        $teachers = Teacher::with('user')->where('is_active', true)->get();
        return view('admin.subjects._form', compact('schools', 'teachers'))->render();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $subject = Subject::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subject created successfully',
                'data' => $subject->load(['school', 'teacher.user'])
            ]);
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['school', 'teacher.user', 'grades']);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $schools = School::where('is_active', true)->get();
        $teachers = Teacher::with('user')->where('is_active', true)->get();
        return view('admin.subjects._form', compact('subject', 'schools', 'teachers'))->render();
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $subject->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully',
                'data' => $subject->load(['school', 'teacher.user'])
            ]);
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully'
            ]);
        }

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    public function toggleStatus(Subject $subject)
    {
        $subject->update(['is_active' => !$subject->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Subject status updated successfully',
            'is_active' => $subject->is_active
        ]);
    }
}
