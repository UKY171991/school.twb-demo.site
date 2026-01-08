<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\School;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'schools' => School::count(),
            'students' => Student::count(),
            'teachers' => Teacher::count(),
            'subjects' => Subject::count(),
            'classrooms' => Classroom::count(),
            'enrollments' => Enrollment::count(),
            'grades' => Grade::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // Students CRUD
    public function students()
    {
        $students = Student::with('user')->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function createStudent()
    {
        return view('admin.students.create');
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'student_id' => 'required|unique:students',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable',
            'phone' => 'nullable',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.students')->with('success', 'Student created successfully');
    }

    public function editStudent(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'student_id' => 'required|unique:students,student_id,' . $student->id,
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable',
            'phone' => 'nullable',
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $student->update([
            'student_id' => $request->student_id,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.students')->with('success', 'Student updated successfully');
    }

    public function destroyStudent(Student $student)
    {
        $student->user->delete();
        return redirect()->route('admin.students')->with('success', 'Student deleted successfully');
    }

    // Teachers CRUD
    public function teachers()
    {
        $teachers = Teacher::with('user')->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function createTeacher()
    {
        return view('admin.teachers.create');
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'employee_id' => 'required|unique:teachers',
            'department' => 'nullable',
            'bio' => 'nullable',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'bio' => $request->bio,
        ]);

        return redirect()->route('admin.teachers')->with('success', 'Teacher created successfully');
    }

    public function editTeacher(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function updateTeacher(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'employee_id' => 'required|unique:teachers,employee_id,' . $teacher->id,
            'department' => 'nullable',
            'bio' => 'nullable',
        ]);

        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $teacher->update([
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'bio' => $request->bio,
        ]);

        return redirect()->route('admin.teachers')->with('success', 'Teacher updated successfully');
    }

    public function destroyTeacher(Teacher $teacher)
    {
        $teacher->user->delete();
        return redirect()->route('admin.teachers')->with('success', 'Teacher deleted successfully');
    }

    // Subjects CRUD
    public function subjects()
    {
        $subjects = Subject::paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function createSubject()
    {
        return view('admin.subjects.create');
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:subjects',
            'description' => 'nullable',
        ]);

        Subject::create($request->only(['name', 'description']));

        return redirect()->route('admin.subjects')->with('success', 'Subject created successfully');
    }

    public function editSubject(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function updateSubject(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|unique:subjects,name,' . $subject->id,
            'description' => 'nullable',
        ]);

        $subject->update($request->only(['name', 'description']));

        return redirect()->route('admin.subjects')->with('success', 'Subject updated successfully');
    }

    public function destroySubject(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects')->with('success', 'Subject deleted successfully');
    }

    // Classrooms CRUD
    public function classrooms()
    {
        $classrooms = Classroom::with(['teacher.user', 'subject'])->paginate(10);
        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function createClassroom()
    {
        $teachers = Teacher::with('user')->get();
        $subjects = Subject::all();
        return view('admin.classrooms.create', compact('teachers', 'subjects'));
    }

    public function storeClassroom(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'capacity' => 'required|integer|min:1',
        ]);

        Classroom::create($request->only(['name', 'teacher_id', 'subject_id', 'capacity']));

        return redirect()->route('admin.classrooms')->with('success', 'Classroom created successfully');
    }

    public function editClassroom(Classroom $classroom)
    {
        $teachers = Teacher::with('user')->get();
        $subjects = Subject::all();
        return view('admin.classrooms.edit', compact('classroom', 'teachers', 'subjects'));
    }

    public function updateClassroom(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $classroom->update($request->only(['name', 'teacher_id', 'subject_id', 'capacity']));

        return redirect()->route('admin.classrooms')->with('success', 'Classroom updated successfully');
    }

    public function destroyClassroom(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('admin.classrooms')->with('success', 'Classroom deleted successfully');
    }

    // Grades CRUD
    public function grades()
    {
        $grades = Grade::with(['enrollment.student.user', 'enrollment.classroom'])->paginate(10);
        return view('admin.grades.index', compact('grades'));
    }

    public function createGrade()
    {
        $enrollments = Enrollment::with(['student.user', 'classroom'])->get();
        return view('admin.grades.create', compact('enrollments'));
    }

    public function storeGrade(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'grade' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable',
        ]);

        Grade::create($request->only(['enrollment_id', 'grade', 'comments']));

        return redirect()->route('admin.grades')->with('success', 'Grade created successfully');
    }

    public function editGrade(Grade $grade)
    {
        $enrollments = Enrollment::with(['student.user', 'classroom'])->get();
        return view('admin.grades.edit', compact('grade', 'enrollments'));
    }

    public function updateGrade(Request $request, Grade $grade)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'grade' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable',
        ]);

        $grade->update($request->only(['enrollment_id', 'grade', 'comments']));

        return redirect()->route('admin.grades')->with('success', 'Grade updated successfully');
    }

    public function destroyGrade(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('admin.grades')->with('success', 'Grade deleted successfully');
    }

    // Schools CRUD
    public function schools()
    {
        $schools = School::paginate(10);
        return view('admin.schools.index', compact('schools'));
    }

    public function createSchool()
    {
        return view('admin.schools.create');
    }

    public function storeSchool(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'principal_name' => 'nullable|string|max:255',
            'established_date' => 'nullable|date',
            'type' => 'required|in:public,private,charter,international',
            'level' => 'required|in:elementary,middle,high,k12,university',
            'student_capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'principal_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('schools/logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle principal signature upload
        if ($request->hasFile('principal_signature')) {
            $signaturePath = $request->file('principal_signature')->store('schools/signatures', 'public');
            $data['principal_signature'] = $signaturePath;
        }

        School::create($data);

        return redirect()->route('admin.schools')->with('success', 'School created successfully');
    }

    public function editSchool(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function updateSchool(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code,' . $school->id,
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'principal_name' => 'nullable|string|max:255',
            'established_date' => 'nullable|date',
            'type' => 'required|in:public,private,charter,international',
            'level' => 'required|in:elementary,middle,high,k12,university',
            'student_capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'principal_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($school->logo && \Storage::disk('public')->exists($school->logo)) {
                \Storage::disk('public')->delete($school->logo);
            }
            $logoPath = $request->file('logo')->store('schools/logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle principal signature upload
        if ($request->hasFile('principal_signature')) {
            // Delete old signature if exists
            if ($school->principal_signature && \Storage::disk('public')->exists($school->principal_signature)) {
                \Storage::disk('public')->delete($school->principal_signature);
            }
            $signaturePath = $request->file('principal_signature')->store('schools/signatures', 'public');
            $data['principal_signature'] = $signaturePath;
        }

        $school->update($data);

        return redirect()->route('admin.schools')->with('success', 'School updated successfully');
    }

    public function destroySchool(School $school)
    {
        $school->delete();
        return redirect()->route('admin.schools')->with('success', 'School deleted successfully');
    }
}
