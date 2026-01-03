<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;
use App\Models\Student;
use App\Models\Grade;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StudentFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\SchoolContext::class);
    }

    private function getCurrentSchoolId()
    {
        return Session::get('current_school_id');
    }

    public function index(Request $request)
    {
        $schoolId = $this->getCurrentSchoolId();
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $query = StudentFee::where('school_id', $schoolId)
            ->with(['student.grade']);

        if ($request->filled('month')) {
            $query->where('fee_month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('fee_year', $request->year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $fees = $query->orderBy('fee_year', 'desc')
            ->orderBy('fee_month', 'desc')
            ->paginate(20);

        $students = Student::where('school_id', $schoolId)->orderBy('name')->get();
        $grades = Grade::where('school_id', $schoolId)->orderBy('name')->get();

        return view('student-fees.index', compact('fees', 'students', 'grades'));
    }

    public function create()
    {
        $schoolId = $this->getCurrentSchoolId();
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $students = Student::where('school_id', $schoolId)->with('grade')->orderBy('name')->get();
        $grades = Grade::where('school_id', $schoolId)->orderBy('name')->get();

        return view('student-fees.create', compact('students', 'grades'));
    }

    public function store(Request $request)
    {
        $schoolId = $this->getCurrentSchoolId();
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_month' => 'required|integer|min:1|max:12',
            'fee_year' => 'required|integer|min:2020|max:2050',
            'tuition_fee' => 'nullable|numeric|min:0',
            'admission_fee' => 'nullable|numeric|min:0',
            'exam_fee' => 'nullable|numeric|min:0',
            'transport_fee' => 'nullable|numeric|min:0',
            'library_fee' => 'nullable|numeric|min:0',
            'sports_fee' => 'nullable|numeric|min:0',
            'computer_fee' => 'nullable|numeric|min:0',
            'other_fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'fine' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        // Check if fee already exists
        $exists = StudentFee::where('school_id', $schoolId)
            ->where('student_id', $validated['student_id'])
            ->where('fee_month', $validated['fee_month'])
            ->where('fee_year', $validated['fee_year'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Fee record already exists for this student and month.');
        }

        $data = $this->calculateFeeAmounts($validated);
        $data['school_id'] = $schoolId;

        if ($data['paid_amount'] > 0) {
            $data['receipt_number'] = StudentFee::generateReceiptNumber($schoolId);
        }

        StudentFee::create($data);

        return redirect()->route('student-fees.index')->with('success', 'Student fee created successfully!');
    }

    public function show(StudentFee $studentFee)
    {
        $studentFee->load(['student.grade', 'school']);
        return view('student-fees.show', compact('studentFee'));
    }

    public function edit(StudentFee $studentFee)
    {
        $schoolId = $this->getCurrentSchoolId();
        $students = Student::where('school_id', $schoolId)->with('grade')->orderBy('name')->get();

        return view('student-fees.edit', compact('studentFee', 'students'));
    }

    public function update(Request $request, StudentFee $studentFee)
    {
        $validated = $request->validate([
            'tuition_fee' => 'nullable|numeric|min:0',
            'admission_fee' => 'nullable|numeric|min:0',
            'exam_fee' => 'nullable|numeric|min:0',
            'transport_fee' => 'nullable|numeric|min:0',
            'library_fee' => 'nullable|numeric|min:0',
            'sports_fee' => 'nullable|numeric|min:0',
            'computer_fee' => 'nullable|numeric|min:0',
            'other_fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'fine' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $data = $this->calculateFeeAmounts($validated);

        if ($data['paid_amount'] > 0 && !$studentFee->receipt_number) {
            $data['receipt_number'] = StudentFee::generateReceiptNumber($studentFee->school_id);
        }

        $studentFee->update($data);

        return redirect()->route('student-fees.index')->with('success', 'Student fee updated successfully!');
    }

    public function destroy(StudentFee $studentFee)
    {
        $studentFee->delete();
        return redirect()->route('student-fees.index')->with('success', 'Student fee deleted successfully!');
    }

    private function calculateFeeAmounts(array $data): array
    {
        $totalFees = ($data['tuition_fee'] ?? 0) + ($data['admission_fee'] ?? 0) +
            ($data['exam_fee'] ?? 0) + ($data['transport_fee'] ?? 0) +
            ($data['library_fee'] ?? 0) + ($data['sports_fee'] ?? 0) +
            ($data['computer_fee'] ?? 0) + ($data['other_fee'] ?? 0);

        $discount = $data['discount'] ?? 0;
        $fine = $data['fine'] ?? 0;
        $totalAmount = $totalFees - $discount + $fine;
        $paidAmount = $data['paid_amount'] ?? 0;
        $balance = $totalAmount - $paidAmount;

        $status = 'unpaid';
        if ($paidAmount >= $totalAmount && $totalAmount > 0) {
            $status = 'paid';
        } elseif ($paidAmount > 0) {
            $status = 'partial';
        }

        $data['total_amount'] = $totalAmount;
        $data['balance'] = $balance;
        $data['status'] = $status;

        return $data;
    }

    public function printSlip(StudentFee $studentFee)
    {
        $studentFee->load(['student.grade', 'school']);
        $school = School::find($studentFee->school_id);

        return view('student-fees.print-slip', compact('studentFee', 'school'));
    }

    public function collectFee(Request $request)
    {
        $schoolId = $this->getCurrentSchoolId();
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $students = Student::where('school_id', $schoolId)->with('grade')->orderBy('name')->get();
        $grades = Grade::where('school_id', $schoolId)->orderBy('name')->get();

        return view('student-fees.collect', compact('students', 'grades'));
    }

    public function bulkCreate(Request $request)
    {
        $schoolId = $this->getCurrentSchoolId();
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'fee_month' => 'required|integer|min:1|max:12',
            'fee_year' => 'required|integer|min:2020|max:2050',
            'tuition_fee' => 'nullable|numeric|min:0',
            'other_fee' => 'nullable|numeric|min:0',
        ]);

        $grade = Grade::find($validated['grade_id']);
        $students = Student::where('school_id', $schoolId)
            ->where('grade_id', $validated['grade_id'])
            ->get();

        $created = 0;
        foreach ($students as $student) {
            $exists = StudentFee::where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('fee_month', $validated['fee_month'])
                ->where('fee_year', $validated['fee_year'])
                ->exists();

            if (!$exists) {
                $data = $this->calculateFeeAmounts([
                    'student_id' => $student->id,
                    'fee_month' => $validated['fee_month'],
                    'fee_year' => $validated['fee_year'],
                    'tuition_fee' => $validated['tuition_fee'] ?? 0,
                    'other_fee' => $validated['other_fee'] ?? 0,
                ]);
                $data['school_id'] = $schoolId;
                $data['student_id'] = $student->id;

                StudentFee::create($data);
                $created++;
            }
        }

        return redirect()->route('student-fees.index')
            ->with('success', "Created fee records for {$created} students in {$grade->name}.");
    }
}
