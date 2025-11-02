<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\ParentModel;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StudentController extends BaseController
{
    /**
     * Display a listing of students
     */
    public function index(): View
    {
        // Ensure user is admin
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Student Management',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Students', 'url' => null]
            ],
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'parents' => ParentModel::where('school_id', $this->getCurrentSchoolId())
                                   ->with('user')
                                   ->get(),
            'statistics' => $this->getStudentStatistics()
        ];

        return view('admin.students.index', $data);
    }

    /**
     * Show the form for creating a new student
     */
    public function create(): View
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Add New Student',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Students', 'url' => route('admin.students.index')],
                ['title' => 'Add Student', 'url' => null]
            ],
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'parents' => ParentModel::where('school_id', $this->getCurrentSchoolId())
                                   ->with('user')
                                   ->get(),
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'bloodGroups' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'genders' => ['male', 'female', 'other']
        ];

        return view('admin.students.create', $data);
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied. School Admin privileges required.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'nullable|string|max:500',
            'class_id' => 'required|exists:class_models,id',
            'parent_id' => 'nullable|exists:parent_models,id',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'admission_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            DB::beginTransaction();
            
            try {
                // Create user account if email provided
                $user = null;
                if ($request->email) {
                    $user = User::create([
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'email' => $request->email,
                        'password' => Hash::make('student123'), // Default password
                        'user_type' => 'student',
                        'school_id' => $this->getCurrentSchoolId(),
                        'is_active' => true
                    ]);
                }

                // Generate unique student ID
                $studentId = $this->generateStudentId();

                // Handle photo upload
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('students/photos', 'public');
                }

                // Create student record
                $student = Student::create([
                    'school_id' => $this->getCurrentSchoolId(),
                    'user_id' => $user?->id,
                    'class_id' => $request->class_id,
                    'parent_id' => $request->parent_id,
                    'student_id' => $studentId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'blood_group' => $request->blood_group,
                    'emergency_contact' => $request->emergency_contact,
                    'emergency_phone' => $request->emergency_phone,
                    'photo' => $photoPath,
                    'status' => 'active',
                    'admission_date' => $request->admission_date
                ]);

                // Assign subjects if provided
                if ($request->subjects) {
                    $student->subjects()->attach($request->subjects);
                }

                DB::commit();

                return [
                    'message' => 'Student created successfully',
                    'student' => $student->load(['user', 'classModel', 'parent', 'subjects']),
                    'redirect' => route('admin.students.show', $student)
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Display the specified student
     */
    public function show(Student $student): View
    {
        if (!$this->user->isAdmin() || $student->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $student->load(['user', 'classModel', 'parent.user', 'subjects', 'attendance', 'grades.subject']);

        $data = [
            'page_title' => 'Student Profile - ' . $student->full_name,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Students', 'url' => route('admin.students.index')],
                ['title' => $student->full_name, 'url' => null]
            ],
            'student' => $student,
            'academicInfo' => $student->getAcademicInfo(),
            'attendanceStats' => $student->getAttendanceStatistics(),
            'gradeStats' => $student->getGradeStatistics(),
            'recentAttendance' => $student->getRecentAttendance(30),
            'recentGrades' => $student->getRecentGrades(10),
            'academicStatus' => $student->getAcademicStatus(),
            'emergencyContact' => $student->getEmergencyContactInfo()
        ];

        return view('admin.students.show', $data);
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student): View
    {
        if (!$this->user->isAdmin() || $student->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $student->load(['user', 'classModel', 'parent', 'subjects']);

        $data = [
            'page_title' => 'Edit Student - ' . $student->full_name,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Students', 'url' => route('admin.students.index')],
                ['title' => $student->full_name, 'url' => route('admin.students.show', $student)],
                ['title' => 'Edit', 'url' => null]
            ],
            'student' => $student,
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'parents' => ParentModel::where('school_id', $this->getCurrentSchoolId())
                                   ->with('user')
                                   ->get(),
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'bloodGroups' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'genders' => ['male', 'female', 'other'],
            'statuses' => ['active', 'inactive', 'graduated', 'transferred']
        ];

        return view('admin.students.edit', $data);
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student): JsonResponse
    {
        if (!$this->user->isAdmin() || $student->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($student->user_id)
            ],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'nullable|string|max:500',
            'class_id' => 'required|exists:class_models,id',
            'parent_id' => 'nullable|exists:parent_models,id',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'admission_date' => 'required|date',
            'status' => 'required|in:active,inactive,graduated,transferred',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request, $student) {
            DB::beginTransaction();
            
            try {
                // Update user account if exists
                if ($student->user) {
                    $student->user->update([
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'email' => $request->email
                    ]);
                } elseif ($request->email) {
                    // Create user account if email provided and doesn't exist
                    $user = User::create([
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'email' => $request->email,
                        'password' => Hash::make('student123'),
                        'user_type' => 'student',
                        'school_id' => $this->getCurrentSchoolId(),
                        'is_active' => true
                    ]);
                    $student->user_id = $user->id;
                }

                // Handle photo upload
                if ($request->hasFile('photo')) {
                    // Delete old photo
                    if ($student->photo) {
                        Storage::disk('public')->delete($student->photo);
                    }
                    $student->photo = $request->file('photo')->store('students/photos', 'public');
                }

                // Update student record
                $student->update([
                    'class_id' => $request->class_id,
                    'parent_id' => $request->parent_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'blood_group' => $request->blood_group,
                    'emergency_contact' => $request->emergency_contact,
                    'emergency_phone' => $request->emergency_phone,
                    'status' => $request->status,
                    'admission_date' => $request->admission_date
                ]);

                // Update subject assignments
                if ($request->has('subjects')) {
                    $student->subjects()->sync($request->subjects ?? []);
                }

                DB::commit();

                return [
                    'message' => 'Student updated successfully',
                    'student' => $student->fresh()->load(['user', 'classModel', 'parent', 'subjects'])
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student): JsonResponse
    {
        if (!$this->user->isAdmin() || $student->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($student) {
            DB::beginTransaction();
            
            try {
                // Delete photo if exists
                if ($student->photo) {
                    Storage::disk('public')->delete($student->photo);
                }

                // Delete associated user account if exists
                if ($student->user) {
                    $student->user->delete();
                }

                // Delete student record
                $student->delete();

                DB::commit();

                return [
                    'message' => 'Student deleted successfully'
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Get students data for DataTables
     */
    public function getData(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($request) {
            $query = Student::with(['user', 'classModel', 'parent.user'])
                           ->where('school_id', $this->getCurrentSchoolId());

            // Apply filters
            if ($request->class_id) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->gender) {
                $query->where('gender', $request->gender);
            }

            if ($request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Get paginated results
            $students = $query->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
                            ->paginate($request->per_page ?? 25);

            return [
                'data' => $students->items(),
                'pagination' => [
                    'current_page' => $students->currentPage(),
                    'last_page' => $students->lastPage(),
                    'per_page' => $students->perPage(),
                    'total' => $students->total()
                ]
            ];
        });
    }

    /**
     * Toggle student status
     */
    public function toggleStatus(Student $student): JsonResponse
    {
        if (!$this->user->isAdmin() || $student->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($student) {
            $newStatus = $student->status === 'active' ? 'inactive' : 'active';
            $student->update(['status' => $newStatus]);

            return [
                'message' => "Student status changed to {$newStatus}",
                'status' => $newStatus
            ];
        });
    }

    /**
     * Bulk operations on students
     */
    public function bulkAction(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,transfer_class',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'class_id' => 'required_if:action,transfer_class|exists:class_models,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            $students = Student::whereIn('id', $request->student_ids)
                              ->where('school_id', $this->getCurrentSchoolId())
                              ->get();

            if ($students->isEmpty()) {
                throw new \Exception('No valid students found');
            }

            $count = 0;
            
            foreach ($students as $student) {
                switch ($request->action) {
                    case 'activate':
                        $student->update(['status' => 'active']);
                        $count++;
                        break;
                    case 'deactivate':
                        $student->update(['status' => 'inactive']);
                        $count++;
                        break;
                    case 'delete':
                        if ($student->photo) {
                            Storage::disk('public')->delete($student->photo);
                        }
                        if ($student->user) {
                            $student->user->delete();
                        }
                        $student->delete();
                        $count++;
                        break;
                    case 'transfer_class':
                        $student->update(['class_id' => $request->class_id]);
                        $count++;
                        break;
                }
            }

            $actionName = match($request->action) {
                'activate' => 'activated',
                'deactivate' => 'deactivated',
                'delete' => 'deleted',
                'transfer_class' => 'transferred to new class'
            };

            return [
                'message' => "{$count} students {$actionName} successfully"
            ];
        });
    }

    /**
     * Generate unique student ID
     */
    private function generateStudentId(): string
    {
        $schoolId = $this->getCurrentSchoolId();
        $year = date('Y');
        $prefix = "STU{$schoolId}{$year}";
        
        $lastStudent = Student::where('school_id', $schoolId)
                             ->where('student_id', 'like', "{$prefix}%")
                             ->orderBy('student_id', 'desc')
                             ->first();

        if ($lastStudent) {
            $lastNumber = (int) substr($lastStudent->student_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get student statistics
     */
    private function getStudentStatistics(): array
    {
        $schoolId = $this->getCurrentSchoolId();
        
        $total = Student::where('school_id', $schoolId)->count();
        $active = Student::where('school_id', $schoolId)->where('status', 'active')->count();
        $inactive = Student::where('school_id', $schoolId)->where('status', 'inactive')->count();
        $graduated = Student::where('school_id', $schoolId)->where('status', 'graduated')->count();
        $thisMonth = Student::where('school_id', $schoolId)
                           ->where('created_at', '>=', Carbon::now()->startOfMonth())
                           ->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'graduated' => $graduated,
            'new_this_month' => $thisMonth,
            'male' => Student::where('school_id', $schoolId)->where('gender', 'male')->count(),
            'female' => Student::where('school_id', $schoolId)->where('gender', 'female')->count()
        ];
    }
}