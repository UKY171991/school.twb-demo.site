<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function index()
    {
        $parents = ParentModel::with(['user', 'students.user'])->paginate(10);
        return view('admin.parents.index', compact('parents'));
    }

    public function create()
    {
        $students = Student::with('user')->where('is_active', true)->get();
        return view('admin.parents.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'occupation' => 'required|string|max:255',
            'relationship' => 'required|string|max:50',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'is_active' => 'boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'parent',
            'school_id' => $request->students[0] ? Student::find($request->students[0])->school_id : null,
            'is_active' => $request->is_active ?? true
        ]);

        $parent = ParentModel::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
            'occupation' => $request->occupation,
            'relationship' => $request->relationship,
            'is_active' => $request->is_active ?? true
        ]);

        // Attach students to parent
        $parent->students()->attach($request->students);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Parent created successfully',
                'data' => $parent->load('user')
            ]);
        }

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent created successfully.');
    }

    public function show(ParentModel $parent)
    {
        $parent->load(['user', 'students.user.school']);
        return view('admin.parents.show', compact('parent'));
    }

    public function edit(ParentModel $parent)
    {
        $students = Student::with('user')->where('is_active', true)->get();
        $parent->load('user');
        return view('admin.parents.edit', compact('parent', 'students'));
    }

    public function update(Request $request, ParentModel $parent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->user_id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'occupation' => 'required|string|max:255',
            'relationship' => 'required|string|max:50',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'is_active' => 'boolean'
        ]);

        $parent->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->is_active
        ]);

        $parent->update($request->except(['name', 'email']));

        // Sync students
        $parent->students()->sync($request->students);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Parent updated successfully',
                'data' => $parent->load('user')
            ]);
        }

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent updated successfully.');
    }

    public function destroy(ParentModel $parent)
    {
        $parent->students()->detach();
        $parent->user->delete();
        $parent->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Parent deleted successfully'
            ]);
        }

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent deleted successfully.');
    }

    public function toggleStatus(ParentModel $parent)
    {
        $parent->update(['is_active' => !$parent->is_active]);
        $parent->user->update(['is_active' => !$parent->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Parent status updated successfully',
            'is_active' => $parent->is_active
        ]);
    }
}
