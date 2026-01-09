<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\School;
use App\Models\TeacherSalary;
use App\Models\StudentFee;
use App\Models\ExamTimetable;
use App\Models\ClassTimetable;
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

        $recentStudents = Student::with('user')->latest()->take(5)->get();
        $recentTeachers = Teacher::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentStudents', 'recentTeachers'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'student',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('students/images', 'public');
        }

        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
            'image' => $imagePath,
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $data = [
            'student_id' => $request->student_id,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('image')) {
            if ($student->image && \Storage::disk('public')->exists($student->image)) {
                \Storage::disk('public')->delete($student->image);
            }
            $data['image'] = $request->file('image')->store('students/images', 'public');
        }

        $student->update($data);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'teacher',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('teachers/images', 'public');
        }

        $signaturePath = null;
        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('teachers/signatures', 'public');
        }

        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'bio' => $request->bio,
            'image' => $imagePath,
            'signature' => $signaturePath,
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $data = [
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'bio' => $request->bio,
        ];

        if ($request->hasFile('image')) {
            if ($teacher->image && \Storage::disk('public')->exists($teacher->image)) {
                \Storage::disk('public')->delete($teacher->image);
            }
            $data['image'] = $request->file('image')->store('teachers/images', 'public');
        }

        if ($request->hasFile('signature')) {
            if ($teacher->signature && \Storage::disk('public')->exists($teacher->signature)) {
                \Storage::disk('public')->delete($teacher->signature);
            }
            $data['signature'] = $request->file('signature')->store('teachers/signatures', 'public');
        }

        $teacher->update($data);

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
            'exam_name' => 'required|string',
            'grade' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:0',
            'comments' => 'nullable',
        ]);

        Grade::create($request->only(['enrollment_id', 'exam_name', 'grade', 'total_marks', 'passing_marks', 'comments']));

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
            'exam_name' => 'required|string',
            'grade' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:0',
            'comments' => 'nullable',
        ]);

        $grade->update($request->only(['enrollment_id', 'exam_name', 'grade', 'total_marks', 'passing_marks', 'comments']));

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

    // Teacher Salary
    public function salaries()
    {
        $salaries = TeacherSalary::with(['teacher.user', 'school'])->paginate(10);
        return view('admin.salaries.index', compact('salaries'));
    }

    public function createSalary()
    {
        $teachers = Teacher::with('user')->get();
        $schools = School::all();
        return view('admin.salaries.create', compact('teachers', 'schools'));
    }

    public function storeSalary(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'school_id' => 'required|exists:schools,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'year' => 'required|integer',
            'status' => 'required|in:paid,pending',
        ]);

        TeacherSalary::create($request->all());
        return redirect()->route('admin.salaries')->with('success', 'Salary record created successfully');
    }

    public function destroySalary(TeacherSalary $salary)
    {
        $salary->delete();
        return redirect()->route('admin.salaries')->with('success', 'Salary record deleted successfully');
    }

    // Student Fees
    public function fees()
    {
        $fees = StudentFee::with(['student.user', 'school'])->paginate(10);
        return view('admin.fees.index', compact('fees'));
    }

    public function createFee()
    {
        $students = Student::with('user')->get();
        $schools = School::all();
        return view('admin.fees.create', compact('students', 'schools'));
    }

    public function storeFee(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_id' => 'required|exists:schools,id',
            'amount' => 'required|numeric|min:0',
            'fee_type' => 'required|string',
            'status' => 'required|in:paid,pending',
        ]);

        StudentFee::create($request->all());
        return redirect()->route('admin.fees')->with('success', 'Fee record created successfully');
    }

    public function destroyFee(StudentFee $fee)
    {
        $fee->delete();
        return redirect()->route('admin.fees')->with('success', 'Fee record deleted successfully');
    }

    // Timetables
    public function examTimetables()
    {
        $timetables = ExamTimetable::with(['school', 'subject', 'classroom'])->paginate(10);
        $schools = School::all();
        $subjects = Subject::all();
        $classrooms = Classroom::all();
        return view('admin.timetables.exam', compact('timetables', 'schools', 'subjects', 'classrooms'));
    }

    public function classTimetables()
    {
        $timetables = ClassTimetable::with(['school', 'classroom', 'subject', 'teacher.user'])->paginate(10);
        $schools = School::all();
        $classrooms = Classroom::all();
        $subjects = Subject::all();
        $teachers = Teacher::with('user')->get();
        return view('admin.timetables.class', compact('timetables', 'schools', 'classrooms', 'subjects', 'teachers'));
    }

    public function storeExamTimetable(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_name' => 'required|string',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        ExamTimetable::create($request->all());
        return redirect()->route('admin.timetables.exam')->with('success', 'Exam timetable added');
    }

    public function storeClassTimetable(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        ClassTimetable::create($request->all());
        return redirect()->route('admin.timetables.class')->with('success', 'Class timetable added');
    }

    // Marksheets & ID Cards
    public function marksheets()
    {
        $students = Student::with(['user', 'school', 'enrollments.grades', 'enrollments.classroom.subject'])->paginate(10);
        return view('admin.marksheets.index', compact('students'));
    }

    public function viewMarksheet(Student $student, Request $request)
    {
        $student->load(['user', 'school', 'enrollments.classroom.subject', 'enrollments.grades']);
        
        $examName = $request->get('exam');
        
        // Get unique exam names for this student
        $exams = $student->enrollments->flatMap->grades->pluck('exam_name')->unique()->filter();

        $grades = collect();
        if ($examName) {
            $grades = $student->enrollments->flatMap(function($enrollment) use ($examName) {
                return $enrollment->grades->where('exam_name', $examName);
            });
        }

        return view('admin.marksheets.show', compact('student', 'exams', 'examName', 'grades'));
    }

    public function printMarksheet(Student $student, $examName)
    {
        $student->load(['user', 'school', 'enrollments.classroom.subject', 'enrollments.grades']);
        
        $grades = $student->enrollments->flatMap(function($enrollment) use ($examName) {
            return $enrollment->grades->where('exam_name', $examName);
        });

        return view('admin.marksheets.print', compact('student', 'examName', 'grades'));
    }

    public function idCards()
    {
        $students = Student::with(['user', 'school', 'enrollments.classroom'])->paginate(12);
        return view('admin.idcards.index', compact('students'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (!$query) {
            return redirect()->back();
        }

        $schools = School::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->latest()
            ->limit(10)
            ->get();

        $students = Student::whereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->orWhere('student_id', 'like', "%{$query}%")
            ->latest()
            ->limit(10)
            ->get();

        $teachers = Teacher::whereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->orWhere('employee_id', 'like', "%{$query}%")
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.search-results', compact('schools', 'students', 'teachers', 'query'));
    }
}
