<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\Grade;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_schools' => School::count(),
            'total_teachers' => Teacher::count(),
            'total_students' => Student::count(),
            'total_classes' => \App\Models\ClassModel::count(),
            'total_subjects' => \App\Models\Subject::count(),
            'total_parents' => \App\Models\ParentModel::count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    public function students(Request $request)
    {
        $query = Student::with(['user', 'school', 'classModel']);

        if ($request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        if ($request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        $students = $query->paginate(20);
        $schools = School::where('is_active', true)->get();
        $classes = \App\Models\ClassModel::where('is_active', true)->get();

        return view('admin.reports.students', compact('students', 'schools', 'classes'));
    }

    public function teachers(Request $request)
    {
        $query = Teacher::with(['user', 'school']);

        if ($request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->qualification) {
            $query->where('qualification', 'like', '%' . $request->qualification . '%');
        }

        if ($request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        $teachers = $query->paginate(20);
        $schools = School::where('is_active', true)->get();

        return view('admin.reports.teachers', compact('teachers', 'schools'));
    }

    public function attendance(Request $request)
    {
        $query = Attendance::with(['student.user', 'classModel']);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);
        $classes = \App\Models\ClassModel::with('school')->where('is_active', true)->get();

        // Calculate attendance statistics
        $stats = [
            'total_records' => $query->count(),
            'present' => $query->where('status', 'present')->count(),
            'absent' => $query->where('status', 'absent')->count(),
            'late' => $query->where('status', 'late')->count(),
            'excused' => $query->where('status', 'excused')->count(),
        ];

        return view('admin.reports.attendance', compact('attendances', 'classes', 'stats'));
    }

    public function grades(Request $request)
    {
        $query = Grade::with(['student.user', 'subject', 'classModel']);

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->exam_type) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->date_from) {
            $query->whereDate('exam_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('exam_date', '<=', $request->date_to);
        }

        $grades = $query->orderBy('exam_date', 'desc')->paginate(20);
        $classes = \App\Models\ClassModel::where('is_active', true)->get();
        $subjects = \App\Models\Subject::where('is_active', true)->get();

        // Calculate grade statistics
        $stats = [
            'total_records' => $query->count(),
            'average_marks' => $query->avg('marks_obtained'),
            'highest_marks' => $query->max('marks_obtained'),
            'lowest_marks' => $query->min('marks_obtained'),
        ];

        return view('admin.reports.grades', compact('grades', 'classes', 'subjects', 'stats'));
    }

    public function exportStudents(Request $request)
    {
        // Implementation for exporting student data to Excel/PDF
        return response()->json([
            'success' => true,
            'message' => 'Export functionality will be implemented'
        ]);
    }

    public function exportAttendance(Request $request)
    {
        // Implementation for exporting attendance data to Excel/PDF
        return response()->json([
            'success' => true,
            'message' => 'Export functionality will be implemented'
        ]);
    }

    public function exportGrades(Request $request)
    {
        // Implementation for exporting grades data to Excel/PDF
        return response()->json([
            'success' => true,
            'message' => 'Export functionality will be implemented'
        ]);
    }
}
