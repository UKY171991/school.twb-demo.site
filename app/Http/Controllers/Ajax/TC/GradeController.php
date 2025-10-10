<?php

namespace App\Http\Controllers\Ajax\TC;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'exam_type' => 'required|in:quiz,midterm,final,assignment,project',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'grade' => 'required|string|max:5',
            'remarks' => 'nullable|string|max:255',
            'exam_date' => 'required|date'
        ]);

        try {
            // Verify teacher has access to this subject
            $teacher = auth()->user()->teacher;
            $subject = \App\Models\Subject::where('id', $request->subject_id)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            $grade = Grade::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Grade saved successfully',
                'data' => $grade->load(['student.user', 'subject', 'classModel'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save grade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'exam_type' => 'required|in:quiz,midterm,final,assignment,project',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'grade' => 'required|string|max:5',
            'remarks' => 'nullable|string|max:255',
            'exam_date' => 'required|date'
        ]);

        try {
            $grade = Grade::findOrFail($request->grade_id);
            
            // Verify teacher has access to this grade
            $teacher = auth()->user()->teacher;
            $subject = \App\Models\Subject::where('id', $grade->subject_id)
                ->where('teacher_id', $teacher->id)
                ->first();

            if (!$subject) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this grade'
                ], 403);
            }

            $grade->update($request->except(['grade_id']));

            return response()->json([
                'success' => true,
                'message' => 'Grade updated successfully',
                'data' => $grade->load(['student.user', 'subject', 'classModel'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update grade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id'
        ]);

        try {
            $grade = Grade::findOrFail($request->grade_id);
            
            // Verify teacher has access to this grade
            $teacher = auth()->user()->teacher;
            $subject = \App\Models\Subject::where('id', $grade->subject_id)
                ->where('teacher_id', $teacher->id)
                ->first();

            if (!$subject) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this grade'
                ], 403);
            }

            $grade->delete();

            return response()->json([
                'success' => true,
                'message' => 'Grade deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete grade: ' . $e->getMessage()
            ], 500);
        }
    }
}
