<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getUsersDataTable($request);
        }

        $schools = School::where('is_active', true)->orderBy('name')->get();
        $userTypes = ['super_admin', 'admin', 'teacher', 'student', 'parent'];

        return view('superadmin.users.index', compact('schools', 'userTypes'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();
        $userTypes = ['admin', 'teacher', 'student', 'parent']; // Exclude super_admin from creation
        $roles = Role::all();

        return view('superadmin.users.create', compact('schools', 'userTypes', 'roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => ['required', Rule::in(['admin', 'teacher', 'student', 'parent'])],
            'school_id' => 'required|exists:schools,id',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return $this->ajaxError('Validation failed', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
                'school_id' => $request->school_id,
                'phone' => $request->phone,
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Assign roles if provided
            if ($request->has('roles') && is_array($request->roles)) {
                $user->assignRole($request->roles);
            }

            // Create default dashboard widgets for the user
            \App\Models\DashboardWidget::createDefaultWidgets($user->id, $user->user_type);

            DB::commit();

            if ($request->ajax()) {
                return $this->ajaxSuccess('User created successfully', [
                    'user' => $user->load('school', 'roles'),
                    'redirect' => route('superadmin.users.index')
                ]);
            }

            return redirect()->route('superadmin.users.index')
                           ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return $this->ajaxError('Failed to create user: ' . $e->getMessage());
            }
            
            return redirect()->back()
                           ->with('error', 'Failed to create user')
                           ->withInput();
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load('school', 'roles', 'dashboardWidgets', 'notifications');
        
        return view('superadmin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();
        $userTypes = $user->isSuperAdmin() 
            ? ['super_admin'] 
            : ['admin', 'teacher', 'student', 'parent'];
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('superadmin.users.edit', compact('user', 'schools', 'userTypes', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'user_type' => ['required', Rule::in($user->isSuperAdmin() ? ['super_admin'] : ['admin', 'teacher', 'student', 'parent'])],
            'school_id' => $user->isSuperAdmin() ? 'nullable' : 'required|exists:schools,id',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return $this->ajaxError('Validation failed', $validator->errors());
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'user_type' => $request->user_type,
                'phone' => $request->phone,
                'is_active' => $request->boolean('is_active', true),
            ];

            // Only update school_id for non-super-admin users
            if (!$user->isSuperAdmin()) {
                $updateData['school_id'] = $request->school_id;
            }

            // Update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Update roles
            if ($request->has('roles')) {
                $user->syncRoles($request->roles ?? []);
            }

            DB::commit();

            if ($request->ajax()) {
                return $this->ajaxSuccess('User updated successfully', [
                    'user' => $user->load('school', 'roles'),
                    'redirect' => route('superadmin.users.index')
                ]);
            }

            return redirect()->route('superadmin.users.index')
                           ->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return $this->ajaxError('Failed to update user: ' . $e->getMessage());
            }
            
            return redirect()->back()
                           ->with('error', 'Failed to update user')
                           ->withInput();
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(Request $request, User $user)
    {
        try {
            // Prevent deletion of super admin users
            if ($user->isSuperAdmin()) {
                if ($request->ajax()) {
                    return $this->ajaxError('Cannot delete super admin users');
                }
                return redirect()->back()->with('error', 'Cannot delete super admin users');
            }

            // Check for associated data that might prevent deletion
            $hasAssociatedData = false;
            $associatedDataMessage = '';

            if ($user->isTeacher() && $user->teacher && $user->teacher->classes()->exists()) {
                $hasAssociatedData = true;
                $associatedDataMessage = 'Cannot delete teacher with assigned classes';
            } elseif ($user->isStudent() && $user->student && $user->student->grades()->exists()) {
                $hasAssociatedData = true;
                $associatedDataMessage = 'Cannot delete student with academic records';
            }

            if ($hasAssociatedData) {
                if ($request->ajax()) {
                    return $this->ajaxError($associatedDataMessage);
                }
                return redirect()->back()->with('error', $associatedDataMessage);
            }

            $user->delete();

            if ($request->ajax()) {
                return $this->ajaxSuccess('User deleted successfully');
            }

            return redirect()->route('superadmin.users.index')
                           ->with('success', 'User deleted successfully');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return $this->ajaxError('Failed to delete user: ' . $e->getMessage());
            }
            
            return redirect()->back()->with('error', 'Failed to delete user');
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(Request $request, User $user)
    {
        try {
            // Prevent deactivating super admin users
            if ($user->isSuperAdmin() && $user->is_active) {
                if ($request->ajax()) {
                    return $this->ajaxError('Cannot deactivate super admin users');
                }
                return redirect()->back()->with('error', 'Cannot deactivate super admin users');
            }

            $user->update(['is_active' => !$user->is_active]);
            
            $status = $user->is_active ? 'activated' : 'deactivated';
            $message = "User {$status} successfully";

            if ($request->ajax()) {
                return $this->ajaxSuccess($message, [
                    'user' => $user,
                    'status' => $user->is_active
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return $this->ajaxError('Failed to update user status: ' . $e->getMessage());
            }
            
            return redirect()->back()->with('error', 'Failed to update user status');
        }
    }

    /**
     * Bulk assign roles to users
     */
    public function bulkAssignRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        if ($validator->fails()) {
            return $this->ajaxError('Validation failed', $validator->errors());
        }

        try {
            DB::beginTransaction();

            $users = User::whereIn('id', $request->user_ids)->get();
            $assignedCount = 0;

            foreach ($users as $user) {
                // Skip super admin users
                if (!$user->isSuperAdmin()) {
                    $user->syncRoles($request->roles);
                    $assignedCount++;
                }
            }

            DB::commit();

            return $this->ajaxSuccess("Roles assigned to {$assignedCount} users successfully");

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->ajaxError('Failed to assign roles: ' . $e->getMessage());
        }
    }

    /**
     * Get users data for DataTable
     */
    private function getUsersDataTable(Request $request)
    {
        $query = User::with(['school', 'roles']);

        // Search functionality
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhere('phone', 'like', "%{$searchValue}%")
                  ->orWhereHas('school', function ($sq) use ($searchValue) {
                      $sq->where('name', 'like', "%{$searchValue}%");
                  });
            });
        }

        // School filter
        if ($request->has('school_id') && $request->school_id !== '') {
            $query->where('school_id', $request->school_id);
        }

        // User type filter
        if ($request->has('user_type') && $request->user_type !== '') {
            $query->where('user_type', $request->user_type);
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Get total count before pagination
        $totalRecords = User::count();
        $filteredRecords = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columns = ['id', 'name', 'email', 'user_type', 'school_id', 'is_active', 'created_at'];
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

        $users = $query->get();

        // Format data for DataTable
        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => ucfirst(str_replace('_', ' ', $user->user_type)),
                'user_type_badge' => $this->getUserTypeBadge($user->user_type),
                'school_name' => $user->school ? $user->school->name : 'N/A',
                'roles' => $user->roles->pluck('name')->implode(', ') ?: 'No roles',
                'is_active' => $user->is_active,
                'status_badge' => $user->is_active 
                    ? '<span class="badge badge-success">Active</span>' 
                    : '<span class="badge badge-danger">Inactive</span>',
                'last_login' => $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never',
                'created_at' => $user->created_at->format('M d, Y'),
                'actions' => $this->getUserActions($user)
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
     * Get user type badge
     */
    private function getUserTypeBadge($userType)
    {
        $badges = [
            'super_admin' => '<span class="badge badge-danger">Super Admin</span>',
            'admin' => '<span class="badge badge-primary">Admin</span>',
            'teacher' => '<span class="badge badge-success">Teacher</span>',
            'student' => '<span class="badge badge-info">Student</span>',
            'parent' => '<span class="badge badge-warning">Parent</span>',
        ];

        return $badges[$userType] ?? '<span class="badge badge-secondary">Unknown</span>';
    }

    /**
     * Get action buttons for user
     */
    private function getUserActions(User $user)
    {
        $actions = '<div class="btn-group" role="group">';
        
        // View button
        $actions .= '<a href="' . route('superadmin.users.show', $user) . '" 
                        class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                    </a>';
        
        // Edit button
        $actions .= '<a href="' . route('superadmin.users.edit', $user) . '" 
                        class="btn btn-sm btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>';
        
        // Toggle status button (not for super admin)
        if (!$user->isSuperAdmin()) {
            $statusClass = $user->is_active ? 'btn-warning' : 'btn-success';
            $statusIcon = $user->is_active ? 'fa-pause' : 'fa-play';
            $statusTitle = $user->is_active ? 'Deactivate' : 'Activate';
            
            $actions .= '<button type="button" 
                            class="btn btn-sm ' . $statusClass . ' toggle-status-btn" 
                            data-url="' . route('superadmin.users.toggle-status', $user) . '"
                            title="' . $statusTitle . '">
                            <i class="fas ' . $statusIcon . '"></i>
                        </button>';
        }
        
        // Delete button (not for super admin and users with associated data)
        if (!$user->isSuperAdmin()) {
            $hasAssociatedData = false;
            
            if ($user->isTeacher() && $user->teacher && $user->teacher->classes()->exists()) {
                $hasAssociatedData = true;
            } elseif ($user->isStudent() && $user->student && $user->student->grades()->exists()) {
                $hasAssociatedData = true;
            }
            
            if (!$hasAssociatedData) {
                $actions .= '<button type="button" 
                                class="btn btn-sm btn-danger delete-btn" 
                                data-url="' . route('superadmin.users.destroy', $user) . '"
                                title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>';
            }
        }
        
        $actions .= '</div>';
        
        return $actions;
    }
}