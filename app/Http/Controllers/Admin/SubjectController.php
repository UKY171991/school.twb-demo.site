<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubjectController extends BaseController
{
    /**
     * Display a listing of subjects
     */
    public function index(): View
    {
        // Ensure user is admin
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Subject Management',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Subjects', 'url' => null]
            ],
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'statistics' => $this->getSubjectStatistics()
        ];

        return view('admin.subjects.index', $data);
    }

    /**
     * Show the form for creating a new subject
     */
    public function create(): View
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Create New Subject',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Subjects', 'url' => route('admin.subjects.index')],
                ['title' => 'Create Subject', 'url' => null]
            ],
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get()
        ];

        return view('admin.subjects.create', $data);
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied. School Admin privileges required.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code',
            'description' => 'nullable|string|max:500',
            'credits' => 'required|integer|min:1|max:10',
            'type' => 'required|in:core,elective,optional',
            'teacher_id' => 'nullable|exists:teachers,id',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:class_models,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            DB::beginTransaction();
            
            try {
                // Check for duplicate subject code in the same school
                $existingSubject = Subject::where('school_id', $this->getCurrentSchoolId())
                                         ->where('code', $request->code)
                                         ->first();

                if ($existingSubject) {
                    throw new \Exception('A subject with this code already exists');
                }

                // Create subject record
                $subject = Subject::create([
                    'school_id' => $this->getCurrentSchoolId(),
                    'teacher_id' => $request->teacher_id,
                    'name' => $request->name,
                    'code' => $request->code,
                    'description' => $request->description,
                    'credits' => $request->credits,
                    'type' => $request->type,
                    'is_active' => true
                ]);

                // Assign classes if provided
                if ($request->classes) {
                    $subject->classes()->attach($request->classes);
                }

                DB::commit();

                return [
                    'message' => 'Subject created successfully',
                    'subject' => $subject->load(['teacher', 'classes']),
                    'redirect' => route('admin.subjects.show', $subject)
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Display the specified subject
     */
    public function show(Subject $subject): View
    {
        if (!$this->user->isAdmin() || $subject->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $subject->load(['teacher', 'classes', 'students']);

        $data = [
            'page_title' => 'Subject Details - ' . $subject->display_name,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Subjects', 'url' => route('admin.subjects.index')],
                ['title' => $subject->display_name, 'url' => null]
            ],
            'subject' => $subject,
            'statistics' => $subject->getStatistics(),
            'academicPerformance' => $subject->getAcademicPerformance(),
            'recentGrades' => $subject->getRecentGrades(),
            'curriculumInfo' => $subject->getCurriculumInfo(),
            'workload' => $subject->getWorkload()
        ];

        return view('admin.subjects.show', $data);
    }

    /**
     * Show the form for editing the specified subject
     */
    public function edit(Subject $subject): View
    {
        if (!$this->user->isAdmin() || $subject->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $subject->load(['teacher', 'classes']);

        $data = [
            'page_title' => 'Edit Subject - ' . $subject->display_name,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Subjects', 'url' => route('admin.subjects.index')],
                ['title' => $subject->display_name, 'url' => route('admin.subjects.show', $subject)],
                ['title' => 'Edit', 'url' => null]
            ],
            'subject' => $subject,
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get()
        ];

        return view('admin.subjects.edit', $data);
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, Subject $subject): JsonResponse
    {
        if (!$this->user->isAdmin() || $subject->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string|max:500',
            'credits' => 'required|integer|min:1|max:10',
            'type' => 'required|in:core,elective,optional',
            'teacher_id' => 'nullable|exists:teachers,id',
            'is_active' => 'required|boolean',
            'classes' => 'nullable|array',
            'classes.*' => 'exists:class_models,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request, $subject) {
            DB::beginTransaction();
            
            try {
                // Check for duplicate subject code (excluding current subject)
                $existingSubject = Subject::where('school_id', $this->getCurrentSchoolId())
                                         ->where('code', $request->code)
                                         ->where('id', '!=', $subject->id)
                                         ->first();

                if ($existingSubject) {
                    throw new \Exception('A subject with this code already exists');
                }

                // Update subject record
                $subject->update([
                    'teacher_id' => $request->teacher_id,
                    'name' => $request->name,
                    'code' => $request->code,
                    'description' => $request->description,
                    'credits' => $request->credits,
                    'type' => $request->type,
                    'is_active' => $request->is_active
                ]);

                // Update class assignments
                if ($request->has('classes')) {
                    $subject->classes()->sync($request->classes ?? []);
                }

                DB::commit();

                return [
                    'message' => 'Subject updated successfully',
                    'subject' => $subject->fresh()->load(['teacher', 'classes'])
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Remove the specified subject
     */
    public function destroy(Subject $subject): JsonResponse
    {
        if (!$this->user->isAdmin() || $subject->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($subject) {
            // Check if subject has grades
            if ($subject->grades()->exists()) {
                throw new \Exception('Cannot delete subject with existing grades. Please archive the subject instead.');
            }

            // Check if subject has enrolled students
            if ($subject->students()->exists()) {
                throw new \Exception('Cannot delete subject with enrolled students. Please remove students first.');
            }

            $subject->delete();

            return [
                'message' => 'Subject deleted successfully'
            ];
        });
    }

    /**
     * Get subjects data for DataTables
     */
    public function getData(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($request) {
            $query = Subject::with(['teacher', 'classes'])
                           ->withCount(['students', 'grades'])
                           ->where('school_id', $this->getCurrentSchoolId());

            // Apply filters
            if ($request->teacher_id) {
                $query->where('teacher_id', $request->teacher_id);
            }

            if ($request->type) {
                $query->where('type', $request->type);
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
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Get paginated results
            $subjects = $query->orderBy($request->sort_by ?? 'name', $request->sort_order ?? 'asc')
                            ->paginate($request->per_page ?? 25);

            return [
                'data' => $subjects->items(),
                'pagination' => [
                    'current_page' => $subjects->currentPage(),
                    'last_page' => $subjects->lastPage(),
                    'per_page' => $subjects->perPage(),
                    'total' => $subjects->total()
                ]
            ];
        });
    }

    /**
     * Toggle subject status
     */
    public function toggleStatus(Subject $subject): JsonResponse
    {
        if (!$this->user->isAdmin() || $subject->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($subject) {
            $newStatus = !$subject->is_active;
            $subject->update(['is_active' => $newStatus]);

            return [
                'message' => "Subject status changed to " . ($newStatus ? 'active' : 'inactive'),
                'status' => $newStatus
            ];
        });
    }

    /**
     * Bulk operations on subjects
     */
    public function bulkAction(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,assign_teacher',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
            'teacher_id' => 'required_if:action,assign_teacher|exists:teachers,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            $subjects = Subject::whereIn('id', $request->subject_ids)
                              ->where('school_id', $this->getCurrentSchoolId())
                              ->get();

            if ($subjects->isEmpty()) {
                throw new \Exception('No valid subjects found');
            }

            $count = 0;
            
            foreach ($subjects as $subject) {
                switch ($request->action) {
                    case 'activate':
                        $subject->update(['is_active' => true]);
                        $count++;
                        break;
                    case 'deactivate':
                        $subject->update(['is_active' => false]);
                        $count++;
                        break;
                    case 'delete':
                        if (!$subject->grades()->exists() && !$subject->students()->exists()) {
                            $subject->delete();
                            $count++;
                        }
                        break;
                    case 'assign_teacher':
                        $subject->update(['teacher_id' => $request->teacher_id]);
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
                'message' => "{$count} subjects {$actionName} successfully"
            ];
        });
    }

    /**
     * Get subject statistics
     */
    private function getSubjectStatistics(): array
    {
        $schoolId = $this->getCurrentSchoolId();
        
        $total = Subject::where('school_id', $schoolId)->count();
        $active = Subject::where('school_id', $schoolId)->where('is_active', true)->count();
        $inactive = Subject::where('school_id', $schoolId)->where('is_active', false)->count();
        $withTeacher = Subject::where('school_id', $schoolId)->whereNotNull('teacher_id')->count();
        $withoutTeacher = Subject::where('school_id', $schoolId)->whereNull('teacher_id')->count();
        
        $typeStats = Subject::where('school_id', $schoolId)
                           ->selectRaw('type, COUNT(*) as count')
                           ->groupBy('type')
                           ->pluck('count', 'type')
                           ->toArray();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'with_teacher' => $withTeacher,
            'without_teacher' => $withoutTeacher,
            'core_subjects' => $typeStats['core'] ?? 0,
            'elective_subjects' => $typeStats['elective'] ?? 0,
            'optional_subjects' => $typeStats['optional'] ?? 0
        ];
    }
}
