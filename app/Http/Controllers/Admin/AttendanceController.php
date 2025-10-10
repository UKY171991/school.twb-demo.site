<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['student.user', 'classModel'])
            ->orderBy('date', 'desc')
            ->paginate(20);
        return view('admin.attendance.index', compact('attendances'));
    }

    public function create()
    {
        $classes = ClassModel::with('school')->where('is_active', true)->get();
        return view('admin.attendance.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendance_data' => 'required|array',
            'attendance_data.*.student_id' => 'required|exists:students,id',
            'attendance_data.*.status' => 'required|in:present,absent,late,excused',
            'attendance_data.*.remarks' => 'nullable|string|max:255'
        ]);

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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully'
            ]);
        }

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance marked successfully.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['student.user', 'classModel']);
        return view('admin.attendance.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $attendance->load(['student.user', 'classModel']);
        return view('admin.attendance.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:255'
        ]);

        $attendance->update($request->only(['status', 'remarks']));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully',
                'data' => $attendance->load(['student.user', 'classModel'])
            ]);
        }

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance deleted successfully'
            ]);
        }

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    public function reports()
    {
        $classes = ClassModel::with('school')->where('is_active', true)->get();
        return view('admin.attendance.reports', compact('classes'));
    }

    public function getClassStudents(Request $request)
    {
        $students = Student::with('user')
            ->where('class_id', $request->class_id)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    public function getAttendanceByDate(Request $request)
    {
        $attendance = Attendance::with(['student.user'])
            ->where('class_id', $request->class_id)
            ->whereDate('date', $request->date)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }
}
