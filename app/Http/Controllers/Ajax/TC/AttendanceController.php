<?php

namespace App\Http\Controllers\Ajax\TC;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function mark(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendance_data' => 'required|array',
            'attendance_data.*.student_id' => 'required|exists:students,id',
            'attendance_data.*.status' => 'required|in:present,absent,late,excused',
            'attendance_data.*.remarks' => 'nullable|string|max:255'
        ]);

        try {
            // Verify teacher has access to this class
            $teacher = auth()->user()->teacher;
            $class = \App\Models\ClassModel::where('id', $request->class_id)
                ->where('teacher_id', $teacher->id)
                ->firstOrFail();

            $date = Carbon::parse($request->date);
            
            // Delete existing attendance for this class and date
            Attendance::where('class_id', $request->class_id)
                ->whereDate('date', $date)
                ->delete();

            // Create new attendance records
            foreach ($request->attendance_data as $data) {
                Attendance::create([
                    'student_id' => $data['student_id'],
                    'class_id' => $request->class_id,
                    'date' => $date,
                    'status' => $data['status'],
                    'remarks' => $data['remarks'] ?? null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendance,id',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:255'
        ]);

        try {
            $attendance = Attendance::findOrFail($request->attendance_id);
            
            // Verify teacher has access to this attendance record
            $teacher = auth()->user()->teacher;
            $classIds = \App\Models\ClassModel::where('teacher_id', $teacher->id)
                ->where('is_active', true)
                ->pluck('id');

            if (!in_array($attendance->class_id, $classIds->toArray())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this attendance record'
                ], 403);
            }

            $attendance->update([
                'status' => $request->status,
                'remarks' => $request->remarks
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully',
                'data' => $attendance->load(['student.user', 'classModel'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update attendance: ' . $e->getMessage()
            ], 500);
        }
    }
}
