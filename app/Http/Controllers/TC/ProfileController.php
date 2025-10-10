<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $teacher = auth()->user()->teacher;
        $teacher->load('school');
        
        // Get teacher statistics
        $stats = [
            'total_classes' => \App\Models\ClassModel::where('teacher_id', $teacher->id)
                ->where('is_active', true)
                ->count(),
            'total_students' => \App\Models\Student::whereHas('classModel', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->where('is_active', true)->count(),
            'total_subjects' => \App\Models\Subject::where('teacher_id', $teacher->id)
                ->where('is_active', true)
                ->count(),
            'total_grades_recorded' => \App\Models\Grade::whereHas('subject', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })->count(),
        ];

        return view('tc.profile.show', compact('teacher', 'stats'));
    }

    public function edit()
    {
        $teacher = auth()->user()->teacher;
        $teacher->load('school');
        return view('tc.profile.edit', compact('teacher'));
    }

    public function update(Request $request)
    {
        $teacher = auth()->user()->teacher;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'subject_specialization' => 'required|string|max:255',
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

        // Update teacher information
        $teacherData = [
            'phone' => $request->phone,
            'address' => $request->address,
            'qualification' => $request->qualification,
            'experience_years' => $request->experience_years,
            'subject_specialization' => $request->subject_specialization,
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($teacher->profile_image && Storage::disk('public')->exists($teacher->profile_image)) {
                Storage::disk('public')->delete($teacher->profile_image);
            }

            $teacherData['profile_image'] = $request->file('profile_image')->store('teacher-profiles', 'public');
        }

        $teacher->update($teacherData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $teacher->load('school')
            ]);
        }

        return redirect()->route('teacher.profile')
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
