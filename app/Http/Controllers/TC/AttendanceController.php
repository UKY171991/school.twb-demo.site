<?php

namespace App\Http\Controllers\TC;

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
        $teacher = auth()->user()->teacher;
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $attendances = Attendance::with(['student.user', 'classModel'])
            ->whereIn('class_id', $classIds)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('tc.attendance.index', compact('attendances'));
    }

    public function create()
    {
        $teacher = auth()->user()->teacher;
        $classes = ClassModel::with('school')
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        return view('tc.attendance.create', compact('classes'));
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

        // Verify teacher has access to this class
        $teacher = auth()->user()->teacher;
        $class = ClassModel::where('id', $request->class_id)
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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully'
            ]);
        }

        return redirect()->route('teacher.attendance.index')
            ->with('success', 'Attendance marked successfully.');
    }

    public function show(Request $request, ClassModel $class)
    {
        // Verify teacher has access to this class
        $teacher = auth()->user()->teacher;
        if ($class->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this class.');
        }

        $date = $request->date ? Carbon::parse($request->date) : today();
        
        $attendance = Attendance::with(['student.user'])
            ->where('class_id', $class->id)
            ->whereDate('date', $date)
            ->get();

        $students = Student::with('user')
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->get();

        // Create attendance map for easy lookup
        $attendanceMap = $attendance->keyBy('student_id');

        return view('tc.attendance.show', compact('class', 'date', 'attendance', 'students', 'attendanceMap'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        // Verify teacher has access to this attendance record
        $teacher = auth()->user()->teacher;
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        if (!in_array($attendance->class_id, $classIds->toArray())) {
            abort(403, 'Unauthorized access to this attendance record.');
        }

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

        return redirect()->back()->with('success', 'Attendance updated successfully.');
    }

    public function getClassStudents(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $students = Student::with('user')
            ->where('class_id', $class->id)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    public function getAttendanceByDate(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $attendance = Attendance::with(['student.user'])
            ->where('class_id', $class->id)
            ->whereDate('date', $request->date)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }
}
