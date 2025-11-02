<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassController extends BaseController
{
    /**
     * Display a listing of classes
     */
    public function index(): View
    {
        // Ensure user is admin
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Class Management',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Classes', 'url' => null]
            ],
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'statistics' => $this->getClassStatistics()
        ];

        return view('admin.classes.index', $data);
    }

    /**
     * Show the form for creating a new class
     */
    public function create(): View
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Create New Class',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Classes', 'url' => route('admin.classes.index')],
                ['title' => 'Create Class', 'url' => null]
            ],
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get()
        ];

        return view('admin.classes.create', $data);
    }

    /**
     * Store a newly created class
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied. School Admin privileges required.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:10',
            'capacity' => 'required|integer|min:1|max:200',
            'room_number' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'teacher_id' => 'nullable|exists:teachers,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            DB::beginTransaction();
            
            try {
                // Check for duplicate class name in the same school
                $existingClass = ClassModel::where('school_id', $this->getCurrentSchoolId())
                                         ->where('name', $request->name)
                                         ->where('section', $request->section)
                                         ->first();

                if ($existingClass) {
                    throw new \Exception('A class with this name and section already exists');
                }

                // Create class record
                $class = ClassModel::create([
                    'school_id' => $this->getCurrentSchoolId(),
                    'teacher_id' => $request->teacher_id,
                    'name' => $request->name,
                    'section' => $request->section,
                    'capacity' => $request->capacity,
                    'room_number' => $request->room_number,
                    'description' => $request->description,
                    'is_active' => true
                ]);

                // Assign subjects if provided
                if ($request->subjects) {
                    $class->subjects()->attach($request->subjects);
                }

                DB::commit();

                return [
                    'message' => 'Class created successfully',
                    'class' => $class->load(['teacher', 'subjects']),
                    'redirect' => route('admin.classes.show', $class)
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Display the specified class
     */
    public function show(ClassModel $class): View
    {
        if (!$this->user->isAdmin() || $class->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $class->load(['teacher', 'subjects', 'students.user']);

        $data = [
            'page_title' => 'Class Details - ' . $class->full_name,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Classes', 'url' => route('admin.classes.index')],
                ['title' => $class->full_name, 'url' => null]
            ],
            'class' => $class,
            'statistics' => $class->getStatistics(),
            'academicPerformance' => $class->getAcademicPerformance(),
            'attendanceSummary' => $class->getAttendanceSummary(),
            'availableStudents' => Student::where('school_id', $this->getCurrentSchoolId())
                                         ->where('status', 'active')
                                         ->whereNull('class_id')
                                         ->with('user')
                                         ->get()
        ];

        return view('admin.classes.show', $data);
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit(ClassModel $class): View
    {
        if (!$this->user->isAdmin() || $class->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $class->load(['teacher', 'subjects']);

        $data = [
            'page_title' => 'Edit Class - ' . $class->full_name,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Classes', 'url' => route('admin.classes.index')],
                ['title' => $class->full_name, 'url' => route('admin.classes.show', $class)],
                ['title' => 'Edit', 'url' => null]
            ],
            'class' => $class,
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get()
        ];

        return view('admin.classes.edit', $data);
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, ClassModel $class): JsonResponse
    {
        if (!$this->user->isAdmin() || $class->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:10',
            'capacity' => 'required|integer|min:1|max:200',
            'room_number' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'teacher_id' => 'nullable|exists:teachers,id',
            'is_active' => 'required|boolean',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request, $class) {
            DB::beginTransaction();
            
            try {
                // Check for duplicate class name (excluding current class)
                $existingClass = ClassModel::where('school_id', $this->getCurrentSchoolId())
                                         ->where('name', $request->name)
                                         ->where('section', $request->section)
                                         ->where('id', '!=', $class->id)
                                         ->first();

                if ($existingClass) {
                    throw new \Exception('A class with this name and section already exists');
                }

                // Check if reducing capacity below current student count
                $currentStudentCount = $class->student_count;
                if ($request->capacity < $currentStudentCount) {
                    throw new \Exception("Cannot reduce capacity below current student count ({$currentStudentCount})");
                }

                // Update class record
                $class->update([
                    'teacher_id' => $request->teacher_id,
                    'name' => $request->name,
                    'section' => $request->section,
                    'capacity' => $request->capacity,
                    'room_number' => $request->room_number,
                    'description' => $request->description,
                    'is_active' => $request->is_active
                ]);

                // Update subject assignments
                if ($request->has('subjects')) {
                    $class->subjects()->sync($request->subjects ?? []);
                }

                DB::commit();

                return [
                    'message' => 'Class updated successfully',
                    'class' => $class->fresh()->load(['teacher', 'subjects'])
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Remove the specified class
     */
    public function destroy(ClassModel $class): JsonResponse
    {
        if (!$this->user->isAdmin() || $class->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($class) {
            // Check if class has students
            if ($class->students()->exists()) {
                throw new \Exception('Cannot delete class with enrolled students. Please transfer students first.');
            }

            // Check if class has grades
            if ($class->grades()->exists()) {
                throw new \Exception('Cannot delete class with existing grades. Please archive the class instead.');
            }

            $class->delete();

            return [
                'message' => 'Class deleted successfully'
            ];
        });
    }

    /**
     * Get classes data for DataTables
     */
    public function getData(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($request) {
            $query = ClassModel::with(['teacher', 'subjects'])
                              ->withCount('students')
                              ->where('school_id', $this->getCurrentSchoolId());

            // Apply filters
            if ($request->teacher_id) {
                $query->where('teacher_id', $request->teacher_id);
            }

            if ($request->has_teacher !== null) {
                if ($request->has_teacher) {
                    $query->whereNotNull('teacher_id');
                } else {
                    $query->whereNull('teacher_id');
                }
            }

            if ($request->status !== null) {
                $query->where('is_active', $request->status);
            }

            if ($request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('section', 'like', "%{$search}%")
                      ->orWhere('room_number', 'like', "%{$search}%");
                });
            }

            // Get paginated results
            $classes = $query->orderBy($request->sort_by ?? 'name', $request->sort_order ?? 'asc')
                           ->paginate($request->per_page ?? 25);

            return [
                'data' => $classes->items(),
                'pagination' => [
                    'current_page' => $classes->currentPage(),
                    'last_page' => $classes->lastPage(),
                    'per_page' => $classes->perPage(),
                    'total' => $classes->total()
                ]
            ];
        });
    }

    /**
     * Toggle class status
     */
    public function toggleStatus(ClassModel $class): JsonResponse
    {
        if (!$this->user->isAdmin() || $class->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($class) {
            $newStatus = !$class->is_active;
            $class->update(['is_active' => $newStatus]);

            return [
                'message' => "Class status changed to " . ($newStatus ? 'active' : 'inactive'),
                'status' => $newStatus
            ];
        });
    }

    /**
     * Enroll students to class
     */
    public function enrollStudents(Request $request, ClassModel $class): JsonResponse
    {
        if (!$this->user->isAdmin() || $class->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request, $class) {
            $studentIds = $request->student_ids;
            $studentsToEnroll = Student::whereIn('id', $studentIds)
                                     ->where('school_id', $this->getCurrentSchoolId())
                                     ->where('status', 'active')
                                     ->get();

            if ($studentsToEnroll->isEmpty()) {
                throw new \Exception('No valid students found for enrollment');
            }

            // Check capacity
            if (!$class->hasCapacity($studentsToEnroll->count())) {
                throw new \Exception('Class does not have enough capacity for all selected students');
            }

            // Enroll students
            $enrolledCount = 0;
            foreach ($studentsToEnroll as $student) {
                if (!$student->class_id) { // Only enroll if not already in a class
                    $student->update(['class_id' => $class->id]);
                    $enrolledCount++;
                }
            }

            return [
                'message' => "{$enrolledCount} students enrolled successfully",
                'enrolled_count' => $enrolledCount
            ];
        });
    }

    /**
     * Remove students from class
     */
    public function removeStudents(Request $request, ClassModel $class): JsonResponse
    {
        if (!$this->user->isAdmin() || $class->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request, $class) {
            $studentIds = $request->student_ids;
            $studentsToRemove = Student::whereIn('id', $studentIds)
                                     ->where('class_id', $class->id)
                                     ->get();

            if ($studentsToRemove->isEmpty()) {
                throw new \Exception('No valid students found in this class');
            }

            // Remove students from class
            $removedCount = 0;
            foreach ($studentsToRemove as $student) {
                $student->update(['class_id' => null]);
                $removedCount++;
            }

            return [
                'message' => "{$removedCount} students removed from class successfully",
                'removed_count' => $removedCount
            ];
        });
    }

    /**
     * Get class performance data
     */
    public function getPerformanceData(ClassModel $class): JsonResponse
    {
        if (!$this->user->isAdmin() || $class->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($class) {
            return [
                'statistics' => $class->getStatistics(),
                'academic_performance' => $class->getAcademicPerformance(),
                'attendance_summary' => $class->getAttendanceSummary()
            ];
        });
    }

    /**
     * Bulk operations on classes
     */
    public function bulkAction(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,assign_teacher',
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:class_models,id',
            'teacher_id' => 'required_if:action,assign_teacher|exists:teachers,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            $classes = ClassModel::whereIn('id', $request->class_ids)
                                ->where('school_id', $this->getCurrentSchoolId())
                                ->get();

            if ($classes->isEmpty()) {
                throw new \Exception('No valid classes found');
            }

            $count = 0;
            
            foreach ($classes as $class) {
                switch ($request->action) {
                    case 'activate':
                        $class->update(['is_active' => true]);
                        $count++;
                        break;
                    case 'deactivate':
                        $class->update(['is_active' => false]);
                        $count++;
                        break;
                    case 'delete':
                        if (!$class->students()->exists() && !$class->grades()->exists()) {
                            $class->delete();
                            $count++;
                        }
                        break;
                    case 'assign_teacher':
                        $class->update(['teacher_id' => $request->teacher_id]);
                        $count++;
                        break;
                }
            }

            $actionName = match($request->action) {
                'activate' => 'activated',
                'deactivate' => 'deactivated',
                'delete' => 'deleted',
                'assign_teacher' => 'assigned to teacher'
            };

            return [
                'message' => "{$count} classes {$actionName} successfully"
            ];
        });
    }

    /**
     * Get class statistics
     */
    private function getClassStatistics(): array
    {
        $schoolId = $this->getCurrentSchoolId();
        
        $total = ClassModel::where('school_id', $schoolId)->count();
        $active = ClassModel::where('school_id', $schoolId)->where('is_active', true)->count();
        $inactive = ClassModel::where('school_id', $schoolId)->where('is_active', false)->count();
        $withTeacher = ClassModel::where('school_id', $schoolId)->whereNotNull('teacher_id')->count();
        $withoutTeacher = ClassModel::where('school_id', $schoolId)->whereNull('teacher_id')->count();
        $totalCapacity = ClassModel::where('school_id', $schoolId)->sum('capacity');
        $totalStudents = Student::where('school_id', $schoolId)->whereNotNull('class_id')->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'with_teacher' => $withTeacher,
            'without_teacher' => $withoutTeacher,
            'total_capacity' => $totalCapacity,
            'total_enrolled' => $totalStudents,
            'capacity_utilization' => $totalCapacity > 0 ? round(($totalStudents / $totalCapacity) * 100, 2) : 0
        ];
    }
}