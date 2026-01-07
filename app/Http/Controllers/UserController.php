<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'master') {
            $users = User::all();
        } elseif ($user->role === 'admin') {
            $users = User::where('created_by', $user->id)->get();
        } else {
            abort(403, 'Unauthorized action.');
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!in_array(Auth::user()->role, ['master', 'admin'])) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax()) {
            return view('users.create')->renderSections()['content'];
        }

        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['master', 'admin'])) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:admin,user'],
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (Auth::user()->role === 'admin' && $request->role === 'admin') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'created_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User created successfully.']);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_to_show = User::findOrFail($id);
        $current_user = Auth::user();

        if ($current_user->role === 'master') {
            // Master can see any user
        } elseif ($current_user->role === 'admin') {
            if ($user_to_show->created_by !== $current_user->id && $user_to_show->id !== $current_user->id) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            if ($user_to_show->id !== $current_user->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        return view('users.show', compact('user_to_show'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $user_to_edit = User::findOrFail($id);
        $current_user = Auth::user();

        if ($current_user->role === 'master') {
            // Master can edit any user
        } elseif ($current_user->role === 'admin') {
            if ($user_to_edit->created_by !== $current_user->id) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
                }
                abort(403, 'Unauthorized action.');
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax()) {
            return view('users.edit', compact('user_to_edit'))->renderSections()['content'];
        }

        return view('users.edit', compact('user_to_edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user_to_edit = User::findOrFail($id);
        $current_user = Auth::user();

        if ($current_user->role === 'master') {
            // Master can edit any user
        } elseif ($current_user->role === 'admin') {
            if ($user_to_edit->created_by !== $current_user->id) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
                }
                abort(403, 'Unauthorized action.');
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user_to_edit->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:master,admin,user'],
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($current_user->role === 'admin' && $request->role === 'admin') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }
        
        if ($user_to_edit->role === 'master' && $request->role !== 'master') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot change the role of a master user.'], 403);
            }
            abort(403, 'Cannot change the role of a master user.');
        }

        $data = $request->only('name', 'email', 'role');
        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user_to_edit->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User updated successfully.']);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user_to_delete = User::findOrFail($id);
        $current_user = Auth::user();

        if ($current_user->id === $user_to_delete->id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'You cannot delete yourself.'], 422);
            }
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        if ($user_to_delete->role === 'master') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete a master user.'], 403);
            }
            abort(403, 'Cannot delete a master user.');
        }

        if ($current_user->role === 'master') {
            // Master can delete any user
        } elseif ($current_user->role === 'admin') {
            if ($user_to_delete->created_by !== $current_user->id) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
                }
                abort(403, 'Unauthorized action.');
            }
        } else {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $user_to_delete->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        }

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
