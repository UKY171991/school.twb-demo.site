<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\BaseController;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SchoolController extends BaseController
{
    /**
     * Display a listing of schools
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getSchoolsDataTable($request);
        }

        return view('superadmin.schools.index');
    }

    /**
     * Show the form for creating a new school
     */
    public function create()
    {
        return view('superadmin.schools.create');
    }

    /**
     * Store a newly created school
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'principal_name' => 'nullable|string|max:255',
            'principal_phone' => 'nullable|string|max:20',
            'principal_email' => 'nullable|email|max:255',
            'established_date' => 'nullable|date',
            'timezone' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return $this->ajaxError('Validation failed', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $school = School::create([
                'name' => $request->name,
                'code' => $request->code,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'description' => $request->description,
                'principal_name' => $request->principal_name,
                'principal_phone' => $request->principal_phone,
                'principal_email' => $request->principal_email,
                'established_date' => $request->established_date,
                'timezone' => $request->timezone ?? 'UTC',
                'is_active' => true,
            ]);

            // Create default configurations for the school
            \App\Models\SchoolConfiguration::createDefaultsForSchool($school->id);

            DB::commit();

            if ($request->ajax()) {
                return $this->ajaxSuccess('School created successfully', [
                    'school' => $school,
                    'redirect' => route('superadmin.schools.index')
                ]);
            }

            return redirect()->route('superadmin.schools.index')
                           ->with('success', 'School created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return $this->ajaxError('Failed to create school: ' . $e->getMessage());
            }
            
            return redirect()->back()
                           ->with('error', 'Failed to create school')
                           ->withInput();
        }
    }

    /**
     * Display the specified school
     */
    public function show(School $school)
    {
        $statistics = $school->getStatistics();
        
        return view('superadmin.schools.show', compact('school', 'statistics'));
    }

    /**
     * Show the form for editing the specified school
     */
    public function edit(School $school)
    {
        return view('superadmin.schools.edit', compact('school'));
    }

    /**
     * Update the specified school
     */
    public function update(Request $request, School $school)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code,' . $school->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'principal_name' => 'nullable|string|max:255',
            'principal_phone' => 'nullable|string|max:20',
            'principal_email' => 'nullable|email|max:255',
            'established_date' => 'nullable|date',
            'timezone' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return $this->ajaxError('Validation failed', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $school->update([
                'name' => $request->name,
                'code' => $request->code,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'description' => $request->description,
                'principal_name' => $request->principal_name,
                'principal_phone' => $request->principal_phone,
                'principal_email' => $request->principal_email,
                'established_date' => $request->established_date,
                'timezone' => $request->timezone ?? 'UTC',
            ]);

            if ($request->ajax()) {
                return $this->ajaxSuccess('School updated successfully', [
                    'school' => $school,
                    'redirect' => route('superadmin.schools.index')
                ]);
            }

            return redirect()->route('superadmin.schools.index')
                           ->with('success', 'School updated successfully');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return $this->ajaxError('Failed to update school: ' . $e->getMessage());
            }
            
            return redirect()->back()
                           ->with('error', 'Failed to update school')
                           ->withInput();
        }
    }

    /**
     * Remove the specified school
     */
    public function destroy(Request $request, School $school)
    {
        try {
            // Check if school has associated data
            $hasUsers = $school->users()->exists();
            $hasStudents = $school->students()->exists();
            $hasTeachers = $school->teachers()->exists();

            if ($hasUsers || $hasStudents || $hasTeachers) {
                if ($request->ajax()) {
                    return $this->ajaxError('Cannot delete school with associated users, students, or teachers');
                }
                return redirect()->back()->with('error', 'Cannot delete school with associated data');
            }

            $school->delete();

            if ($request->ajax()) {
                return $this->ajaxSuccess('School deleted successfully');
            }

            return redirect()->route('superadmin.schools.index')
                           ->with('success', 'School deleted successfully');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return $this->ajaxError('Failed to delete school: ' . $e->getMessage());
            }
            
            return redirect()->back()->with('error', 'Failed to delete school');
        }
    }

    /**
     * Toggle school active status
     */
    public function toggleStatus(Request $request, School $school)
    {
        try {
            $school->update(['is_active' => !$school->is_active]);
            
            $status = $school->is_active ? 'activated' : 'deactivated';
            $message = "School {$status} successfully";

            if ($request->ajax()) {
                return $this->ajaxSuccess($message, [
                    'school' => $school,
                    'status' => $school->is_active
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return $this->ajaxError('Failed to update school status: ' . $e->getMessage());
            }
            
            return redirect()->back()->with('error', 'Failed to update school status');
        }
    }

    /**
     * Get schools data for DataTable
     */
    private function getSchoolsDataTable(Request $request)
    {
        $query = School::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('code', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhere('principal_name', 'like', "%{$searchValue}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Get total count before pagination
        $totalRecords = School::count();
        $filteredRecords = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columns = ['id', 'name', 'code', 'email', 'principal_name', 'is_active', 'created_at'];
            $orderColumn = $columns[$request->order[0]['column']] ?? 'created_at';
            $orderDirection = $request->order[0]['dir'] ?? 'desc';
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        if ($request->has('start') && $request->has('length')) {
            $query->skip($request->start)->take($request->length);
        }

        $schools = $query->get();

        // Format data for DataTable
        $data = $schools->map(function ($school) {
            return [
                'id' => $school->id,
                'name' => $school->name,
                'code' => $school->code,
                'email' => $school->email ?? '-',
                'principal_name' => $school->principal_name ?? '-',
                'students_count' => $school->getActiveStudentsCount(),
                'teachers_count' => $school->getActiveTeachersCount(),
                'is_active' => $school->is_active,
                'status_badge' => $school->is_active 
                    ? '<span class="badge badge-success">Active</span>' 
                    : '<span class="badge badge-danger">Inactive</span>',
                'created_at' => $school->created_at->format('M d, Y'),
                'actions' => $this->getSchoolActions($school)
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get action buttons for school
     */
    private function getSchoolActions(School $school)
    {
        $actions = '<div class="btn-group" role="group">';
        
        // View button
        $actions .= '<a href="' . route('superadmin.schools.show', $school) . '" 
                        class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                    </a>';
        
        // Edit button
        $actions .= '<a href="' . route('superadmin.schools.edit', $school) . '" 
                        class="btn btn-sm btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>';
        
        // Toggle status button
        $statusClass = $school->is_active ? 'btn-warning' : 'btn-success';
        $statusIcon = $school->is_active ? 'fa-pause' : 'fa-play';
        $statusTitle = $school->is_active ? 'Deactivate' : 'Activate';
        
        $actions .= '<button type="button" 
                        class="btn btn-sm ' . $statusClass . ' toggle-status-btn" 
                        data-url="' . route('superadmin.schools.toggle-status', $school) . '"
                        title="' . $statusTitle . '">
                        <i class="fas ' . $statusIcon . '"></i>
                    </button>';
        
        // Delete button (only if no associated data)
        $hasAssociatedData = $school->users()->exists() || 
                           $school->students()->exists() || 
                           $school->teachers()->exists();
        
        if (!$hasAssociatedData) {
            $actions .= '<button type="button" 
                            class="btn btn-sm btn-danger delete-btn" 
                            data-url="' . route('superadmin.schools.destroy', $school) . '"
                            title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>';
        }
        
        $actions .= '</div>';
        
        return $actions;
    }
}