<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Teacher;
use App\Models\User;
use App\Models\ClassModel;
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

class TeacherController extends BaseController
{
    /**
     * Display a listing of teachers
     */
    public function index(): View
    {
        // Ensure user is admin
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Teacher Management',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Teachers', 'url' => null]
            ],
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'statistics' => $this->getTeacherStatistics()
        ];

        return view('admin.teachers.index', $data);
    }

    /**
     * Show the form for creating a new teacher
     */
    public function create(): View
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Add New Teacher',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Teachers', 'url' => route('admin.teachers.index')],
                ['title' => 'Add Teacher', 'url' => null]
            ],
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'genders' => ['male', 'female', 'other'],
            'qualifications' => [
                'Bachelor\'s Degree', 'Master\'s Degree', 'PhD', 'Diploma',
                'Certificate', 'Associate Degree', 'Professional Certification'
            ]
        ];

        return view('admin.teachers.create', $data);
    }

    /**
     * Store a newly created teacher
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
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|integer|min:0|max:50',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:class_models,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            DB::beginTransaction();
            
            try {
                // Create user account
                $user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make('teacher123'), // Default password
                    'user_type' => 'teacher',
                    'school_id' => $this->getCurrentSchoolId(),
                    'is_active' => true
                ]);

                // Generate unique employee ID
                $employeeId = $this->generateEmployeeId();

                // Handle photo upload
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('teachers/photos', 'public');
                }

                // Create teacher record
                $teacher = Teacher::create([
                    'school_id' => $this->getCurrentSchoolId(),
                    'user_id' => $user->id,
                    'employee_id' => $employeeId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'qualification' => $request->qualification,
                    'experience' => $request->experience,
                    'salary' => $request->salary,
                    'joining_date' => $request->joining_date,
                    'photo' => $photoPath,
                    'is_active' => true
                ]);

                // Assign subjects if provided
                if ($request->subjects) {
                    Subject::whereIn('id', $request->subjects)
                           ->update(['teacher_id' => $teacher->id]);
                }

                // Assign classes if provided
                if ($request->classes) {
                    ClassModel::whereIn('id', $request->classes)
                              ->update(['teacher_id' => $teacher->id]);
                }

                DB::commit();

                return [
                    'message' => 'Teacher created successfully',
                    'teacher' => $teacher->load(['user', 'subjects', 'classes']),
                    'redirect' => route('admin.teachers.show', $teacher)
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Display the specified teacher
     */
    public function show(Teacher $teacher): View
    {
        if (!$this->user->isAdmin() || $teacher->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $teacher->load(['user', 'subjects', 'classes.students', 'grades.student', 'grades.subject']);

        $data = [
            'page_title' => 'Teacher Profile - ' . $teacher->full_name,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Teachers', 'url' => route('admin.teachers.index')],
                ['title' => $teacher->full_name, 'url' => null]
            ],
            'teacher' => $teacher,
            'professionalInfo' => $teacher->getProfessionalInfo(),
            'teachingStats' => $teacher->getTeachingStatistics(),
            'currentWorkload' => $teacher->getCurrentWorkload(),
            'recentGrades' => $teacher->getRecentGrades(30),
            'classPerformance' => $teacher->getClassPerformanceSummary(),
            'performanceMetrics' => $teacher->getPerformanceMetrics(),
            'todaySchedule' => $teacher->getTodaySchedule(),
            'contactInfo' => $teacher->getContactInfo()
        ];

        return view('admin.teachers.show', $data);
    }

    /**
     * Show the form for editing the specified teacher
     */
    public function edit(Teacher $teacher): View
    {
        if (!$this->user->isAdmin() || $teacher->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $teacher->load(['user', 'subjects', 'classes']);

        $data = [
            'page_title' => 'Edit Teacher - ' . $teacher->full_name,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Teachers', 'url' => route('admin.teachers.index')],
                ['title' => $teacher->full_name, 'url' => route('admin.teachers.show', $teacher)],
                ['title' => 'Edit', 'url' => null]
            ],
            'teacher' => $teacher,
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'genders' => ['male', 'female', 'other'],
            'qualifications' => [
                'Bachelor\'s Degree', 'Master\'s Degree', 'PhD', 'Diploma',
                'Certificate', 'Associate Degree', 'Professional Certification'
            ]
        ];

        return view('admin.teachers.edit', $data);
    }

    /**
     * Update the specified teacher
     */
    public function update(Request $request, Teacher $teacher): JsonResponse
    {
        if (!$this->user->isAdmin() || $teacher->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($teacher->user_id)
            ],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|integer|min:0|max:50',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'address' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:class_models,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request, $teacher) {
            DB::beginTransaction();
            
            try {
                // Update user account
                if ($teacher->user) {
                    $teacher->user->update([
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'email' => $request->email,
                        'is_active' => $request->is_active
                    ]);
                }

                // Handle photo upload
                if ($request->hasFile('photo')) {
                    // Delete old photo
                    if ($teacher->photo) {
                        Storage::disk('public')->delete($teacher->photo);
                    }
                    $teacher->photo = $request->file('photo')->store('teachers/photos', 'public');
                }

                // Update teacher record
                $teacher->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'qualification' => $request->qualification,
                    'experience' => $request->experience,
                    'salary' => $request->salary,
                    'joining_date' => $request->joining_date,
                    'is_active' => $request->is_active
                ]);

                // Update subject assignments
                // First, remove current assignments
                Subject::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);
                // Then assign new subjects
                if ($request->subjects) {
                    Subject::whereIn('id', $request->subjects)
                           ->update(['teacher_id' => $teacher->id]);
                }

                // Update class assignments
                // First, remove current assignments
                ClassModel::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);
                // Then assign new classes
                if ($request->classes) {
                    ClassModel::whereIn('id', $request->classes)
                              ->update(['teacher_id' => $teacher->id]);
                }

                DB::commit();

                return [
                    'message' => 'Teacher updated successfully',
                    'teacher' => $teacher->fresh()->load(['user', 'subjects', 'classes'])
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Remove the specified teacher
     */
    public function destroy(Teacher $teacher): JsonResponse
    {
        if (!$this->user->isAdmin() || $teacher->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($teacher) {
            DB::beginTransaction();
            
            try {
                // Delete photo if exists
                if ($teacher->photo) {
                    Storage::disk('public')->delete($teacher->photo);
                }

                // Remove assignments
                Subject::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);
                ClassModel::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);

                // Delete associated user account if exists
                if ($teacher->user) {
                    $teacher->user->delete();
                }

                // Delete teacher record
                $teacher->delete();

                DB::commit();

                return [
                    'message' => 'Teacher deleted successfully'
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Get teachers data for DataTables
     */
    public function getData(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($request) {
            $query = Teacher::with(['user', 'subjects', 'classes'])
                           ->where('school_id', $this->getCurrentSchoolId());

            // Apply filters
            if ($request->subject_id) {
                $query->whereHas('subjects', function($q) use ($request) {
                    $q->where('id', $request->subject_id);
                });
            }

            if ($request->class_id) {
                $query->whereHas('classes', function($q) use ($request) {
                    $q->where('id', $request->class_id);
                });
            }

            if ($request->status !== null) {
                $query->where('is_active', $request->status);
            }

            if ($request->gender) {
                $query->where('gender', $request->gender);
            }

            if ($request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Get paginated results
            $teachers = $query->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
                            ->paginate($request->per_page ?? 25);

            return [
                'data' => $teachers->items(),
                'pagination' => [
                    'current_page' => $teachers->currentPage(),
                    'last_page' => $teachers->lastPage(),
                    'per_page' => $teachers->perPage(),
                    'total' => $teachers->total()
                ]
            ];
        });
    }

    /**
     * Toggle teacher status
     */
    public function toggleStatus(Teacher $teacher): JsonResponse
    {
        if (!$this->user->isAdmin() || $teacher->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($teacher) {
            $newStatus = !$teacher->is_active;
            $teacher->update(['is_active' => $newStatus]);

            // Also update user status
            if ($teacher->user) {
                $teacher->user->update(['is_active' => $newStatus]);
            }

            return [
                'message' => "Teacher status changed to " . ($newStatus ? 'active' : 'inactive'),
                'status' => $newStatus
            ];
        });
    }

    /**
     * Bulk operations on teachers
     */
    public function bulkAction(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,assign_subject,assign_class',
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:teachers,id',
            'subject_id' => 'required_if:action,assign_subject|exists:subjects,id',
            'class_id' => 'required_if:action,assign_class|exists:class_models,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            $teachers = Teacher::whereIn('id', $request->teacher_ids)
                              ->where('school_id', $this->getCurrentSchoolId())
                              ->get();

            if ($teachers->isEmpty()) {
                throw new \Exception('No valid teachers found');
            }

            $count = 0;
            
            foreach ($teachers as $teacher) {
                switch ($request->action) {
                    case 'activate':
                        $teacher->update(['is_active' => true]);
                        if ($teacher->user) {
                            $teacher->user->update(['is_active' => true]);
                        }
                        $count++;
                        break;
                    case 'deactivate':
                        $teacher->update(['is_active' => false]);
                        if ($teacher->user) {
                            $teacher->user->update(['is_active' => false]);
                        }
                        $count++;
                        break;
                    case 'delete':
                        if ($teacher->photo) {
                            Storage::disk('public')->delete($teacher->photo);
                        }
                        Subject::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);
                        ClassModel::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);
                        if ($teacher->user) {
                            $teacher->user->delete();
                        }
                        $teacher->delete();
                        $count++;
                        break;
                    case 'assign_subject':
                        Subject::where('id', $request->subject_id)
                               ->update(['teacher_id' => $teacher->id]);
                        $count++;
                        break;
                    case 'assign_class':
                        ClassModel::where('id', $request->class_id)
                                  ->update(['teacher_id' => $teacher->id]);
                        $count++;
                        break;
                }
            }

            $actionName = match($request->action) {
                'activate' => 'activated',
                'deactivate' => 'deactivated',
                'delete' => 'deleted',
                'assign_subject' => 'assigned to subject',
                'assign_class' => 'assigned to class'
            };

            return [
                'message' => "{$count} teachers {$actionName} successfully"
            ];
        });
    }

    /**
     * Get teacher performance data
     */
    public function getPerformanceData(Teacher $teacher): JsonResponse
    {
        if (!$this->user->isAdmin() || $teacher->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($teacher) {
            return [
                'teaching_stats' => $teacher->getTeachingStatistics(),
                'workload' => $teacher->getCurrentWorkload(),
                'performance_metrics' => $teacher->getPerformanceMetrics(),
                'class_performance' => $teacher->getClassPerformanceSummary(),
                'recent_grades' => $teacher->getRecentGrades(30)
            ];
        });
    }

    /**
     * Check for schedule conflicts
     */
    public function checkScheduleConflicts(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:class_models,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            $teacher = Teacher::find($request->teacher_id);
            $newClasses = ClassModel::whereIn('id', $request->class_ids)->get();
            $currentClasses = $teacher->classes;
            
            // Simple conflict detection based on class capacity
            $conflicts = [];
            $totalStudents = $currentClasses->sum(function($class) {
                return $class->students()->count();
            }) + $newClasses->sum(function($class) {
                return $class->students()->count();
            });

            if ($totalStudents > 150) { // Arbitrary threshold
                $conflicts[] = [
                    'type' => 'workload',
                    'message' => "High student load: {$totalStudents} students total"
                ];
            }

            return [
                'conflicts' => $conflicts,
                'total_classes' => $currentClasses->count() + $newClasses->count(),
                'total_students' => $totalStudents
            ];
        });
    }

    /**
     * Generate unique employee ID
     */
    private function generateEmployeeId(): string
    {
        $schoolId = $this->getCurrentSchoolId();
        $year = date('Y');
        $prefix = "EMP{$schoolId}{$year}";
        
        $lastTeacher = Teacher::where('school_id', $schoolId)
                             ->where('employee_id', 'like', "{$prefix}%")
                             ->orderBy('employee_id', 'desc')
                             ->first();

        if ($lastTeacher) {
            $lastNumber = (int) substr($lastTeacher->employee_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get teacher statistics
     */
    private function getTeacherStatistics(): array
    {
        $schoolId = $this->getCurrentSchoolId();
        
        $total = Teacher::where('school_id', $schoolId)->count();
        $active = Teacher::where('school_id', $schoolId)->where('is_active', true)->count();
        $inactive = Teacher::where('school_id', $schoolId)->where('is_active', false)->count();
        $thisMonth = Teacher::where('school_id', $schoolId)
                           ->where('created_at', '>=', Carbon::now()->startOfMonth())
                           ->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'new_this_month' => $thisMonth,
            'male' => Teacher::where('school_id', $schoolId)->where('gender', 'male')->count(),
            'female' => Teacher::where('school_id', $schoolId)->where('gender', 'female')->count(),
            'avg_experience' => Teacher::where('school_id', $schoolId)->avg('experience') ?? 0
        ];
    }
}