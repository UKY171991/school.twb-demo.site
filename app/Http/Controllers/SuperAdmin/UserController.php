<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'school'])
            ->latest()
            ->paginate(20);
        
        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $schools = School::where('is_active', true)->get();
        $roles = Role::all();
        
        return view('superadmin.users.create', compact('schools', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
            'school_id' => 'nullable|exists:schools,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'school_id' => $validated['school_id'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $user->load(['role', 'school']);
        return view('superadmin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $schools = School::where('is_active', true)->get();
        $roles = Role::all();
        
        return view('superadmin.users.edit', compact('user', 'schools', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role_id' => 'required|exists:roles,id',
            'school_id' => 'nullable|exists:schools,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'school_id' => $validated['school_id'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_active' => $request->has('is_active') ? $validated['is_active'] : $user->is_active,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('superadmin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.users.index')->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'User deleted successfully!');
    }
}
