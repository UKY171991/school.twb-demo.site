<?php

namespace App\Http\Controllers;

use App\Models\TeacherSalary;
use App\Models\Teacher;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TeacherSalaryController extends Controller
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

        $query = TeacherSalary::where('school_id', $schoolId)->with('teacher');

        if ($request->filled('month')) {
            $query->where('salary_month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('salary_year', $request->year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $salaries = $query->orderBy('salary_year', 'desc')
            ->orderBy('salary_month', 'desc')
            ->paginate(20);

        $teachers = Teacher::where('school_id', $schoolId)->orderBy('name')->get();

        return view('teacher-salaries.index', compact('salaries', 'teachers'));
    }

    public function create()
    {
        $schoolId = $this->getCurrentSchoolId();
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $teachers = Teacher::where('school_id', $schoolId)->orderBy('name')->get();

        return view('teacher-salaries.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $schoolId = $this->getCurrentSchoolId();
        if (!$schoolId) {
            return redirect()->route('schools.index')->with('error', 'Please select a school first.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'salary_month' => 'required|integer|min:1|max:12',
            'salary_year' => 'required|integer|min:2020|max:2050',
            'basic_salary' => 'required|numeric|min:0',
            'house_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'deduction_tax' => 'nullable|numeric|min:0',
            'deduction_pf' => 'nullable|numeric|min:0',
            'deduction_loan' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'status' => 'required|in:paid,pending,cancelled',
            'remarks' => 'nullable|string',
        ]);

        $exists = TeacherSalary::where('school_id', $schoolId)
            ->where('teacher_id', $validated['teacher_id'])
            ->where('salary_month', $validated['salary_month'])
            ->where('salary_year', $validated['salary_year'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Salary record already exists for this teacher and month.');
        }

        $data = $this->calculateSalaryAmounts($validated);
        $data['school_id'] = $schoolId;

        if ($data['status'] === 'paid') {
            $data['slip_number'] = TeacherSalary::generateSlipNumber($schoolId);
        }

        TeacherSalary::create($data);

        return redirect()->route('teacher-salaries.index')->with('success', 'Teacher salary created successfully!');
    }

    public function show(TeacherSalary $teacherSalary)
    {
        $teacherSalary->load(['teacher', 'school']);
        return view('teacher-salaries.show', compact('teacherSalary'));
    }

    public function edit(TeacherSalary $teacherSalary)
    {
        $schoolId = $this->getCurrentSchoolId();
        $teachers = Teacher::where('school_id', $schoolId)->orderBy('name')->get();

        return view('teacher-salaries.edit', compact('teacherSalary', 'teachers'));
    }

    public function update(Request $request, TeacherSalary $teacherSalary)
    {
        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'house_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'deduction_tax' => 'nullable|numeric|min:0',
            'deduction_pf' => 'nullable|numeric|min:0',
            'deduction_loan' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'status' => 'required|in:paid,pending,cancelled',
            'remarks' => 'nullable|string',
        ]);

        $data = $this->calculateSalaryAmounts($validated);

        if ($data['status'] === 'paid' && !$teacherSalary->slip_number) {
            $data['slip_number'] = TeacherSalary::generateSlipNumber($teacherSalary->school_id);
        }

        $teacherSalary->update($data);

        return redirect()->route('teacher-salaries.index')->with('success', 'Teacher salary updated successfully!');
    }

    public function destroy(TeacherSalary $teacherSalary)
    {
        $teacherSalary->delete();
        return redirect()->route('teacher-salaries.index')->with('success', 'Teacher salary deleted successfully!');
    }

    private function calculateSalaryAmounts(array $data): array
    {
        $grossSalary = ($data['basic_salary'] ?? 0) + ($data['house_allowance'] ?? 0) +
            ($data['transport_allowance'] ?? 0) + ($data['medical_allowance'] ?? 0) +
            ($data['other_allowance'] ?? 0) + ($data['bonus'] ?? 0) + ($data['overtime'] ?? 0);

        $totalDeductions = ($data['deduction_tax'] ?? 0) + ($data['deduction_pf'] ?? 0) +
            ($data['deduction_loan'] ?? 0) + ($data['other_deduction'] ?? 0);

        $netSalary = $grossSalary - $totalDeductions;

        $data['gross_salary'] = $grossSalary;
        $data['total_deductions'] = $totalDeductions;
        $data['net_salary'] = $netSalary;

        return $data;
    }

    public function printSlip(TeacherSalary $teacherSalary)
    {
        $teacherSalary->load(['teacher', 'school']);
        $school = School::find($teacherSalary->school_id);

        return view('teacher-salaries.print-slip', compact('teacherSalary', 'school'));
    }
}
