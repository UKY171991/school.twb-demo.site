<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $student = auth()->user()->student;
        $student->load(['school', 'classModel', 'user']);
        
        // Get student statistics
        $stats = [
            'total_days_present' => \App\Models\Attendance::where('student_id', $student->id)
                ->where('status', 'present')
                ->count(),
            'total_days_absent' => \App\Models\Attendance::where('student_id', $student->id)
                ->where('status', 'absent')
                ->count(),
            'total_grades' => \App\Models\Grade::where('student_id', $student->id)->count(),
            'average_grade' => \App\Models\Grade::where('student_id', $student->id)->avg('marks_obtained'),
        ];

        return view('student.profile.show', compact('student', 'stats'));
    }

    public function edit()
    {
        $student = auth()->user()->student;
        $student->load(['school', 'classModel']);
        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = auth()->user()->student;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update user information
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        auth()->user()->update($userData);

        // Update student information
        $studentData = [
            'phone' => $request->phone,
            'address' => $request->address,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($student->profile_image && Storage::disk('public')->exists($student->profile_image)) {
                Storage::disk('public')->delete($student->profile_image);
            }

            $studentData['profile_image'] = $request->file('profile_image')->store('student-profiles', 'public');
        }

        $student->update($studentData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $student->load(['school', 'classModel'])
            ]);
        }

        return redirect()->route('student.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
