<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\SchoolContext::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schoolId = session('current_school_id');

        $query = Attendance::with('student.grade')
            ->whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });

        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $query->whereDate('attendance_date', $request->date);
        } else {
            // Default to today
            $query->whereDate('attendance_date', Carbon::today());
        }

        $attendances = $query->orderBy('attendance_date', 'desc')->get();
        $grades = Grade::where('school_id', $schoolId)->get();

        return view('attendances.index', compact('attendances', 'grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $schoolId = session('current_school_id');

        $grades = Grade::where('school_id', $schoolId)->get();
        $students = [];

        if ($request->has('grade_id') && $request->grade_id) {
            $students = Student::where('grade_id', $request->grade_id)
                ->where('school_id', $schoolId)->get();
        }

        return view('attendances.create', compact('grades', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|array',
        ]);

        foreach ($request->student_ids as $student_id) {
            // Check if attendance already exists
            $existing = Attendance::where('student_id', $student_id)
                ->whereDate('attendance_date', $request->attendance_date)
                ->first();

            if ($existing) {
                $existing->update([
                    'status' => $request->status[$student_id] ?? 'absent',
                    'note' => $request->note[$student_id] ?? null,
                ]);
            } else {
                Attendance::create([
                    'student_id' => $student_id,
                    'attendance_date' => $request->attendance_date,
                    'status' => $request->status[$student_id] ?? 'absent',
                    'note' => $request->note[$student_id] ?? null,
                ]);
            }
        }

        if ($request->has('save_and_continue')) {
            return redirect()->route('attendances.create')
                ->with('success', 'Attendance marked successfully. Select another class to continue.');
        }

        return redirect()->route('attendances.index', ['date' => $request->attendance_date])
            ->with('success', 'Attendance marked successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attendance = Attendance::with('student')->findOrFail($id);

        return view('attendances.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance deleted successfully.',
            ]);
        }

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    /**
     * Return a single attendance record as JSON for AJAX editing.
     */
    public function getAttendanceById(string $id)
    {
        $attendance = Attendance::with('student.grade')->findOrFail($id);

        $grade = $attendance->student->grade ?? null;

        return response()->json([
            'id' => $attendance->id,
            'student_id' => $attendance->student->id,
            'student_name' => $attendance->student->name,
            'grade_id' => $grade ? $grade->id : null,
            'grade_name' => $grade ? ($grade->name . ($grade->section ? ' - ' . $grade->section : '')) : null,
            'attendance_date' => $attendance->attendance_date instanceof \Carbon\Carbon ? $attendance->attendance_date->format('Y-m-d') : \Carbon\Carbon::parse($attendance->attendance_date)->format('Y-m-d'),
            'status' => $attendance->status,
            'note' => $attendance->note,
        ]);
    }

    /**
     * Get attendance data for AJAX requests
     */
    public function getAttendanceData(Request $request)
    {
        $schoolId = session('current_school_id');
        $date = $request->get('date', date('Y-m-d'));

        $query = Attendance::with('student.grade')
            ->whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->whereDate('attendance_date', $date);

        $attendances = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary
        $totalStudents = $attendances->count();
        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $excusedCount = $attendances->where('status', 'excused')->count();

        $data = [
            'attendances' => $attendances->map(function ($attendance) {
                $grade = $attendance->student->grade ?? null;
                $gradeName = 'N/A';
                if ($grade) {
                    $gradeName = $grade->name;
                    if (!empty($grade->section)) {
                        $gradeName .= ' - ' . $grade->section;
                    }
                }

                // Ensure attendance_date is a Carbon instance before formatting
                $dateValue = $attendance->attendance_date;
                if (!($dateValue instanceof \Carbon\Carbon)) {
                    $dateValue = \Carbon\Carbon::parse($dateValue);
                }

                return [
                    'id' => $attendance->id,
                    'student_name' => $attendance->student->name,
                    'grade_name' => $gradeName,
                    'date' => $dateValue->format('d M Y'),
                    'status' => $attendance->status,
                    'note' => $attendance->note ?? '',
                ];
            }),
            'summary' => [
                'total' => $totalStudents,
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'excused' => $excusedCount,
                'present_percentage' => $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100) : 0,
            ],
            'date' => $date,
            'formatted_date' => \Carbon\Carbon::parse($date)->format('l, F j, Y'),
        ];

        return response()->json($data);
    }

    /**
     * Save attendance via AJAX
     */
    public function saveAjax(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|array',
        ]);

        $saved = 0;
        $updated = 0;

        foreach ($request->student_ids as $student_id) {
            // Check if attendance already exists
            $existing = Attendance::where('student_id', $student_id)
                ->whereDate('attendance_date', $request->attendance_date)
                ->first();

            if ($existing) {
                $existing->update([
                    'status' => $request->status[$student_id] ?? 'absent',
                    'note' => $request->note[$student_id] ?? null,
                ]);
                $updated++;
            } else {
                Attendance::create([
                    'student_id' => $student_id,
                    'attendance_date' => $request->attendance_date,
                    'status' => $request->status[$student_id] ?? 'absent',
                    'note' => $request->note[$student_id] ?? null,
                ]);
                $saved++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Attendance saved successfully. {$saved} new records, {$updated} updated.",
            'saved' => $saved,
            'updated' => $updated,
        ]);
    }

    /**
     * Get students by grade for AJAX
     */
    public function getStudentsByGrade(Request $request, $gradeId)
    {
        $schoolId = session('current_school_id');
        $date = $request->get('date', date('Y-m-d'));

        $students = Student::where('grade_id', $gradeId)
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get(['id', 'name', 'roll_number']);

        // Load existing attendance records for these students on the given date
        $attendanceRecords = Attendance::whereIn('student_id', $students->pluck('id')->toArray())
            ->whereDate('attendance_date', $date)
            ->get()
            ->keyBy('student_id');

        return response()->json([
            'students' => $students->map(function ($student) use ($attendanceRecords) {
                $att = $attendanceRecords->get($student->id);
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'roll_number' => $student->roll_number,
                    'attendance' => $att ? [
                        'id' => $att->id,
                        'status' => $att->status,
                        'note' => $att->note,
                    ] : null,
                ];
            })
        ]);
    }
}
