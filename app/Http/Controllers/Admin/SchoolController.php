<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use DataTables;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = School::withCount(['teachers', 'students'])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $statusBtn = '<button class="btn btn-sm toggle-status-btn '.($row->is_active ? 'btn-success' : 'btn-danger').'" data-url="'.route('admin.schools.toggle-status', $row).'">';
                    $statusBtn .= '<i class="fas '.($row->is_active ? 'fa-toggle-on' : 'fa-toggle-off').'"></i> ';
                    $statusBtn .= '<span>'.($row->is_active ? 'Active' : 'Inactive').'</span>';
                    $statusBtn .= '</button>';
                    return $statusBtn;
                })
                ->addColumn('actions', function($row){
                    $actionBtn = '<div class="btn-group" role="group">';
                    $actionBtn .= '<a href="'.route('admin.schools.show', $row).'" class="btn btn-info btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a>';
                    $actionBtn .= '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="'.$row->id.'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></button>';
                    $actionBtn .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-url="'.route('admin.schools.destroy', $row).'" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.schools.index');
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:schools',
            'principal_name' => 'required|string|max:255',
            'established_year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $school = School::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'School created successfully',
                'data' => $school
            ]);
        }

        return redirect()->route('admin.schools.index')
            ->with('success', 'School created successfully.');
    }

    public function show(School $school)
    {
        $school->load(['teachers', 'students']);
        return view('admin.schools.show', compact('school'));
    }

    public function edit(School $school)
    {
        return response()->json($school);
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:schools,email,' . $school->id,
            'principal_name' => 'required|string|max:255',
            'established_year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $school->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'School updated successfully',
                'data' => $school
            ]);
        }

        return redirect()->route('admin.schools.index')
            ->with('success', 'School updated successfully.');
    }

    public function destroy(School $school)
    {
        $school->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'School deleted successfully'
            ]);
        }

        return redirect()->route('admin.schools.index')
            ->with('success', 'School deleted successfully.');
    }

    public function toggleStatus(School $school)
    {
        $school->update(['is_active' => !$school->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'School status updated successfully',
            'is_active' => $school->is_active
        ]);
    }
}


