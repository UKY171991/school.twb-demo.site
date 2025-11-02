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

        // Get statistics for the dashboard
        $stats = $this->getAttendanceStats($teacher);

        $attendances = Attendance::with(['student.user', 'class'])
            ->whereIn('class_id', $classIds)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('tc.attendance.index', compact('attendances', 'stats'));
    }

    public function create()
    {
        $teacher = auth()->user()->teacher;
        $classes = ClassModel::with(['school', 'students' => function($query) {
                $query->where('status', 'active');
            }])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->withCount(['students' => function($query) {
                $query->where('status', 'active');
            }])
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
                'school_id' => $class->school_id,
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

    /**
     * Get attendance statistics for teacher dashboard
     */
    private function getAttendanceStats($teacher)
    {
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $totalClasses = $classIds->count();
        $totalStudents = Student::whereIn('class_id', $classIds)
            ->where('status', 'active')
            ->count();

        $today = today();
        $todayAttendance = Attendance::whereIn('class_id', $classIds)
            ->whereDate('date', $today)
            ->get();

        $todayPresent = $todayAttendance->whereIn('status', ['present', 'late', 'excused'])->count();
        $todayAbsent = $todayAttendance->where('status', 'absent')->count();

        return [
            'total_classes' => $totalClasses,
            'total_students' => $totalStudents,
            'today_present' => $todayPresent,
            'today_absent' => $todayAbsent,
        ];
    }

    /**
     * Get attendance data for AJAX requests
     */
    public function getAttendanceData(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $date = $request->date ? Carbon::parse($request->date) : today();
        
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        // Get attendance records grouped by class
        $attendanceData = Attendance::with(['class', 'student'])
            ->whereIn('class_id', $classIds)
            ->whereDate('date', $date)
            ->get()
            ->groupBy('class_id')
            ->map(function($classAttendance, $classId) use ($date) {
                $class = $classAttendance->first()->class;
                $totalStudents = Student::where('class_id', $classId)
                    ->where('status', 'active')
                    ->count();
                
                $presentCount = $classAttendance->whereIn('status', ['present', 'late', 'excused'])->count();
                $absentCount = $classAttendance->where('status', 'absent')->count();
                $lateCount = $classAttendance->where('status', 'late')->count();
                
                return [
                    'class_id' => $classId,
                    'class_name' => $class->full_name,
                    'date' => $date->format('Y-m-d'),
                    'total_students' => $totalStudents,
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                    'late_count' => $lateCount,
                ];
            })
            ->values();

        $stats = $this->getAttendanceStats($teacher);

        return response()->json([
            'success' => true,
            'data' => $attendanceData,
            'stats' => $stats
        ]);
    }

    /**
     * Get students for attendance marking
     */
    public function getStudentsForAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date'
        ]);

        $teacher = auth()->user()->teacher;
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $date = Carbon::parse($request->date);
        
        // Get students in the class
        $students = Student::with('user')
            ->where('class_id', $class->id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'full_name' => $student->full_name,
                    'photo_url' => $student->photo_url,
                ];
            });

        // Get existing attendance for this date
        $existingAttendance = Attendance::where('class_id', $class->id)
            ->whereDate('date', $date)
            ->get()
            ->keyBy('student_id')
            ->map(function($attendance) {
                return [
                    'status' => $attendance->status,
                    'remarks' => $attendance->remarks,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'students' => $students,
                'existing_attendance' => $existingAttendance,
                'class' => [
                    'id' => $class->id,
                    'name' => $class->full_name,
                ],
                'date' => $date->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Get attendance by date for viewing
     */
    public function getAttendanceByDateForView(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date'
        ]);

        $teacher = auth()->user()->teacher;
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $date = Carbon::parse($request->date);
        
        $attendance = Attendance::with(['student.user'])
            ->where('class_id', $class->id)
            ->whereDate('date', $date)
            ->get()
            ->map(function($record) {
                return [
                    'id' => $record->id,
                    'student_id' => $record->student_id,
                    'student_name' => $record->student->full_name,
                    'status' => $record->status,
                    'remarks' => $record->remarks,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    /**
     * Generate attendance reports
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:classes,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel,csv'
        ]);

        $teacher = auth()->user()->teacher;
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $query = Attendance::with(['student.user', 'class'])
            ->whereIn('class_id', $classIds)
            ->whereBetween('date', [$request->start_date, $request->end_date]);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        $attendanceRecords = $query->orderBy('date', 'desc')
            ->orderBy('class_id')
            ->get();

        // Generate report based on format
        switch ($request->format) {
            case 'pdf':
                return $this->generatePdfReport($attendanceRecords, $request);
            case 'excel':
                return $this->generateExcelReport($attendanceRecords, $request);
            case 'csv':
                return $this->generateCsvReport($attendanceRecords, $request);
        }
    }

    /**
     * Get attendance analytics
     */
    public function getAnalytics(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $classIds = ClassModel::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('id');

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        // Daily attendance trends
        $dailyTrends = Attendance::whereIn('class_id', $classIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('DATE(date) as date, status, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // Class-wise attendance summary
        $classSummary = Attendance::with('class')
            ->whereIn('class_id', $classIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('class_id, status, COUNT(*) as count')
            ->groupBy('class_id', 'status')
            ->get()
            ->groupBy('class_id');

        // Student attendance patterns (students with low attendance)
        $lowAttendanceStudents = Student::with(['user', 'class'])
            ->whereIn('class_id', $classIds)
            ->whereHas('attendance', function($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->withCount([
                'attendance as total_days' => function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                },
                'attendance as present_days' => function($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate])
                          ->whereIn('status', ['present', 'late', 'excused']);
                }
            ])
            ->get()
            ->map(function($student) {
                $attendancePercentage = $student->total_days > 0 
                    ? round(($student->present_days / $student->total_days) * 100, 2) 
                    : 0;
                
                return [
                    'student' => $student,
                    'attendance_percentage' => $attendancePercentage,
                    'total_days' => $student->total_days,
                    'present_days' => $student->present_days,
                ];
            })
            ->where('attendance_percentage', '<', 75)
            ->sortBy('attendance_percentage')
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'daily_trends' => $dailyTrends,
                'class_summary' => $classSummary,
                'low_attendance_students' => $lowAttendanceStudents,
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ]
            ]
        ]);
    }

    // Helper methods for report generation would go here
    // These are placeholder methods - full implementation would depend on specific requirements
    private function generatePdfReport($records, $request)
    {
        // PDF generation logic
        return response()->json(['message' => 'PDF report generation not implemented yet']);
    }

    private function generateExcelReport($records, $request)
    {
        // Excel generation logic
        return response()->json(['message' => 'Excel report generation not implemented yet']);
    }

    private function generateCsvReport($records, $request)
    {
        // CSV generation logic
        return response()->json(['message' => 'CSV report generation not implemented yet']);
    }

    /**
     * Show attendance reports page
     */
    public function reports()
    {
        return view('tc.attendance.reports');
    }
}
