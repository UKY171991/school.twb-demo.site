<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Attendance::with(['student.user', 'classModel'])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d M, Y');
                })
                ->editColumn('status', function ($row) {
                    $status = 'badge-secondary';
                    switch ($row->status) {
                        case 'present':
                            $status = 'badge-success';
                            break;
                        case 'absent':
                            $status = 'badge-danger';
                            break;
                        case 'late':
                            $status = 'badge-warning';
                            break;
                        case 'excused':
                            $status = 'badge-info';
                            break;
                    }
                    return '<span class="badge ' . $status . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('actions', function($row){
                    $actionBtn = '<div class="btn-group" role="group">';
                    $actionBtn .= '<a href="'.route('admin.attendance.show', $row).'" class="btn btn-info btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a>';
                    $actionBtn .= '<button type="button" class="btn btn-warning btn-sm edit-btn" data-id="'.$row->id.'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></button>';
                    $actionBtn .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-url="'.route('admin.attendance.destroy', $row).'" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.attendance.index');
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
        return view('admin.attendance._form', compact('attendance'))->render();
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
