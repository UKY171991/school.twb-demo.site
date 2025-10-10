<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'user_type' => 'nullable|in:admin,teacher,student,parent',
            'school_id' => 'nullable|exists:schools,id'
        ]);

        try {
            $query = User::with(['school']);

            if ($request->user_type) {
                $query->where('user_type', $request->user_type);
            }

            if ($request->school_id) {
                $query->where('school_id', $request->school_id);
            }

            $users = $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query . '%')
                  ->orWhere('email', 'like', '%' . $request->query . '%');
            })
            ->where('is_active', true)
            ->limit(20)
            ->get();

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchStudents(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'class_id' => 'nullable|exists:classes,id',
            'school_id' => 'nullable|exists:schools,id'
        ]);

        try {
            $query = Student::with(['user', 'classModel', 'school']);

            if ($request->class_id) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->school_id) {
                $query->where('school_id', $request->school_id);
            }

            $students = $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query . '%')
                  ->orWhere('email', 'like', '%' . $request->query . '%');
            })
            ->orWhere('student_id', 'like', '%' . $request->query . '%')
            ->where('is_active', true)
            ->limit(20)
            ->get();

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchTeachers(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'school_id' => 'nullable|exists:schools,id'
        ]);

        try {
            $query = Teacher::with(['user', 'school']);

            if ($request->school_id) {
                $query->where('school_id', $request->school_id);
            }

            $teachers = $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query . '%')
                  ->orWhere('email', 'like', '%' . $request->query . '%');
            })
            ->orWhere('employee_id', 'like', '%' . $request->query . '%')
            ->where('is_active', true)
            ->limit(20)
            ->get();

            return response()->json([
                'success' => true,
                'data' => $teachers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchSchools(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        try {
            $schools = School::where('name', 'like', '%' . $request->query . '%')
                ->orWhere('address', 'like', '%' . $request->query . '%')
                ->where('is_active', true)
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $schools
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
