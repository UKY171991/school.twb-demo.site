<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcademicYearController extends BaseController
{
    /**
     * Display a listing of academic years
     */
    public function index(): View
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Academic Year Management',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Academic Years', 'url' => null]
            ],
            'academicYears' => AcademicYear::where('school_id', $this->getCurrentSchoolId())
                                         ->orderBy('start_date', 'desc')
                                         ->get(),
            'statistics' => $this->getAcademicYearStatistics(),
            'currentYear' => AcademicYear::where('school_id', $this->getCurrentSchoolId())
                                       ->where('is_current', true)
                                       ->first()
        ];

        return view('admin.academic.years.index', $data);
    }

    /**
     * Store a newly created academic year
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied. School Admin privileges required.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'total_semesters' => 'required|integer|min:1|max:4',
            'description' => 'nullable|string|max:500'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            DB::beginTransaction();
            
            try {
                // Check for overlapping academic years
                $overlapping = AcademicYear::where('school_id', $this->getCurrentSchoolId())
                                         ->where(function($query) use ($request) {
                                             $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                                                   ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                                                   ->orWhere(function($subQuery) use ($request) {
                                                       $subQuery->where('start_date', '<=', $request->start_date)
                                                                ->where('end_date', '>=', $request->end_date);
                                                   });
                                         })
                                         ->exists();

                if ($overlapping) {
                    throw new \Exception('Academic year dates overlap with an existing academic year');
                }

                // Create academic year
                $academicYear = AcademicYear::create([
                    'school_id' => $this->getCurrentSchoolId(),
                    'name' => $request->name,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'current_semester' => 1,
                    'total_semesters' => $request->total_semesters,
                    'is_active' => true,
                    'is_current' => false,
                    'description' => $request->description
                ]);

                DB::commit();

                return [
                    'message' => 'Academic year created successfully',
                    'academic_year' => $academicYear,
                    'redirect' => route('admin.academic.years.show', $academicYear)
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Display the specified academic year
     */
    public function show(AcademicYear $year): View
    {
        if (!$this->user->isAdmin() || $year->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $year->load(['students', 'schedules', 'grades']);

        $data = [
            'page_title' => 'Academic Year Details - ' . $year->name,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Academic Years', 'url' => route('admin.academic.years.index')],
                ['title' => $year->name, 'url' => null]
            ],
            'academicYear' => $year,
            'statistics' => $year->getStatistics(),
            'semesterList' => $year->getSemesterList(),
            'enrollmentStats' => $year->getEnrollmentStatistics()
        ];

        return view('admin.academic.years.show', $data);
    }

    /**
     * Update the specified academic year
     */
    public function update(Request $request, AcademicYear $year): JsonResponse
    {
        if (!$this->user->isAdmin() || $year->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_semesters' => 'required|integer|min:1|max:4',
            'current_semester' => 'required|integer|min:1|max:' . $request->total_semesters,
            'is_active' => 'required|boolean',
            'description' => 'nullable|string|max:500'
        ]);

        return $this->handleAjaxRequest(function() use ($request, $year) {
            DB::beginTransaction();
            
            try {
                // Check for overlapping academic years (excluding current year)
                $overlapping = AcademicYear::where('school_id', $this->getCurrentSchoolId())
                                         ->where('id', '!=', $year->id)
                                         ->where(function($query) use ($request) {
                                             $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                                                   ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                                                   ->orWhere(function($subQuery) use ($request) {
                                                       $subQuery->where('start_date', '<=', $request->start_date)
                                                                ->where('end_date', '>=', $request->end_date);
                                                   });
                                         })
                                         ->exists();

                if ($overlapping) {
                    throw new \Exception('Academic year dates overlap with an existing academic year');
                }

                // Update academic year
                $year->update([
                    'name' => $request->name,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'current_semester' => $request->current_semester,
                    'total_semesters' => $request->total_semesters,
                    'is_active' => $request->is_active,
                    'description' => $request->description
                ]);

                DB::commit();

                return [
                    'message' => 'Academic year updated successfully',
                    'academic_year' => $year->fresh()
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Remove the specified academic year
     */
    public function destroy(AcademicYear $year): JsonResponse
    {
        if (!$this->user->isAdmin() || $year->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($year) {
            if (!$year->canBeDeleted()) {
                throw new \Exception('Cannot delete academic year. It has active enrollments, grades, or is currently active.');
            }

            $year->delete();

            return [
                'message' => 'Academic year deleted successfully'
            ];
        });
    }

    /**
     * Activate academic year
     */
    public function activate(AcademicYear $year): JsonResponse
    {
        if (!$this->user->isAdmin() || $year->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($year) {
            if (!$year->is_active) {
                throw new \Exception('Cannot activate an inactive academic year. Please activate it first.');
            }

            $year->activate();

            return [
                'message' => 'Academic year activated successfully. All other academic years have been deactivated.',
                'academic_year' => $year->fresh()
            ];
        });
    }

    /**
     * Progress to next semester
     */
    public function progressSemester(AcademicYear $year): JsonResponse
    {
        if (!$this->user->isAdmin() || $year->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($year) {
            if (!$year->is_current) {
                throw new \Exception('Can only progress semester for the current academic year.');
            }

            if (!$year->progressToNextSemester()) {
                throw new \Exception('Cannot progress semester. Already at the last semester.');
            }

            // Auto-enroll all active students to the new semester
            $activeStudents = Student::where('school_id', $this->getCurrentSchoolId())
                                   ->where('status', 'active')
                                   ->get();

            foreach ($activeStudents as $student) {
                $year->students()->syncWithoutDetaching([
                    $student->id => [
                        'semester' => $year->current_semester,
                        'status' => 'active',
                        'enrollment_date' => now()
                    ]
                ]);
            }

            return [
                'message' => "Successfully progressed to semester {$year->current_semester}. All active students have been enrolled.",
                'academic_year' => $year->fresh(),
                'new_semester' => $year->current_semester,
                'enrolled_students' => $activeStudents->count()
            ];
        });
    }

    /**
     * Get academic year statistics
     */
    private function getAcademicYearStatistics(): array
    {
        $schoolId = $this->getCurrentSchoolId();
        
        $total = AcademicYear::where('school_id', $schoolId)->count();
        $active = AcademicYear::where('school_id', $schoolId)->where('is_active', true)->count();
        $current = AcademicYear::where('school_id', $schoolId)->where('is_current', true)->count();
        $upcoming = AcademicYear::where('school_id', $schoolId)->upcoming()->count();
        $completed = AcademicYear::where('school_id', $schoolId)->completed()->count();
        $inProgress = AcademicYear::where('school_id', $schoolId)->inProgress()->count();

        // Get current academic year details
        $currentYear = AcademicYear::where('school_id', $schoolId)
                                 ->where('is_current', true)
                                 ->first();

        $currentYearInfo = null;
        if ($currentYear) {
            $currentYearInfo = [
                'name' => $currentYear->name,
                'current_semester' => $currentYear->current_semester,
                'total_semesters' => $currentYear->total_semesters,
                'progress_percentage' => $currentYear->progress_percentage,
                'days_remaining' => $currentYear->end_date ? $currentYear->end_date->diffInDays(Carbon::today()) : 0,
                'total_students' => $currentYear->students()->count(),
                'active_schedules' => $currentYear->schedules()->where('is_active', true)->count()
            ];
        }

        return [
            'total' => $total,
            'active' => $active,
            'current' => $current,
            'upcoming' => $upcoming,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'current_year_info' => $currentYearInfo
        ];
    }
}