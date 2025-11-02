<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\ClassSchedule;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleController extends BaseController
{
    /**
     * Display a listing of schedules
     */
    public function index(): View
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Class Schedules',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Schedules', 'url' => null]
            ],
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'academicYears' => AcademicYear::where('school_id', $this->getCurrentSchoolId())
                                         ->where('is_active', true)
                                         ->orderBy('start_date', 'desc')
                                         ->get(),
            'statistics' => $this->getScheduleStatistics(),
            'weekDays' => $this->getWeekDays()
        ];

        return view('admin.schedules.index', $data);
    }

    /**
     * Show the form for creating a new schedule
     */
    public function create(): View
    {
        if (!$this->user->isAdmin()) {
            abort(403, 'Access denied. School Admin privileges required.');
        }

        $data = [
            'page_title' => 'Create Class Schedule',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Schedules', 'url' => route('admin.schedules.index')],
                ['title' => 'Create Schedule', 'url' => null]
            ],
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'academicYears' => AcademicYear::where('school_id', $this->getCurrentSchoolId())
                                         ->where('is_active', true)
                                         ->orderBy('start_date', 'desc')
                                         ->get(),
            'weekDays' => $this->getWeekDays(),
            'timeSlots' => $this->getTimeSlots()
        ];

        return view('admin.schedules.create', $data);
    }

    /**
     * Store a newly created schedule
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied. School Admin privileges required.');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|string|max:20'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            DB::beginTransaction();
            
            try {
                // Create temporary schedule to check conflicts
                $tempSchedule = new ClassSchedule([
                    'school_id' => $this->getCurrentSchoolId(),
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'teacher_id' => $request->teacher_id,
                    'day_of_week' => $request->day_of_week,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'room_number' => $request->room_number,
                    'academic_year_id' => $request->academic_year_id,
                    'semester' => $request->semester,
                    'is_active' => true
                ]);

                // Check for conflicts
                $conflicts = $tempSchedule->getConflicts();
                if (!empty($conflicts)) {
                    $conflictMessages = array_map(fn($conflict) => $conflict['message'], $conflicts);
                    throw new \Exception('Schedule conflicts detected: ' . implode('; ', $conflictMessages));
                }

                // Create the schedule
                $schedule = ClassSchedule::create($tempSchedule->toArray());

                DB::commit();

                return [
                    'message' => 'Schedule created successfully',
                    'schedule' => $schedule->load(['class', 'subject', 'teacher', 'academicYear']),
                    'redirect' => route('admin.schedules.show', $schedule)
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Display the specified schedule
     */
    public function show(ClassSchedule $schedule): View
    {
        if (!$this->user->isAdmin() || $schedule->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $schedule->load(['class', 'subject', 'teacher', 'academicYear']);

        $data = [
            'page_title' => 'Schedule Details',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Schedules', 'url' => route('admin.schedules.index')],
                ['title' => 'Schedule Details', 'url' => null]
            ],
            'schedule' => $schedule,
            'statistics' => $schedule->getStatistics(),
            'conflicts' => $schedule->getConflicts()
        ];

        return view('admin.schedules.show', $data);
    }

    /**
     * Show the form for editing the specified schedule
     */
    public function edit(ClassSchedule $schedule): View
    {
        if (!$this->user->isAdmin() || $schedule->school_id !== $this->getCurrentSchoolId()) {
            abort(403, 'Access denied.');
        }

        $schedule->load(['class', 'subject', 'teacher', 'academicYear']);

        $data = [
            'page_title' => 'Edit Schedule',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Schedules', 'url' => route('admin.schedules.index')],
                ['title' => 'Schedule Details', 'url' => route('admin.schedules.show', $schedule)],
                ['title' => 'Edit', 'url' => null]
            ],
            'schedule' => $schedule,
            'classes' => ClassModel::where('school_id', $this->getCurrentSchoolId())
                                  ->where('is_active', true)
                                  ->orderBy('name')
                                  ->get(),
            'subjects' => Subject::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get(),
            'teachers' => Teacher::where('school_id', $this->getCurrentSchoolId())
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get(),
            'academicYears' => AcademicYear::where('school_id', $this->getCurrentSchoolId())
                                         ->where('is_active', true)
                                         ->orderBy('start_date', 'desc')
                                         ->get(),
            'weekDays' => $this->getWeekDays(),
            'timeSlots' => $this->getTimeSlots()
        ];

        return view('admin.schedules.edit', $data);
    }

    /**
     * Update the specified schedule
     */
    public function update(Request $request, ClassSchedule $schedule): JsonResponse
    {
        if (!$this->user->isAdmin() || $schedule->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|string|max:20',
            'is_active' => 'required|boolean'
        ]);

        return $this->handleAjaxRequest(function() use ($request, $schedule) {
            DB::beginTransaction();
            
            try {
                // Create temporary schedule with new data to check conflicts
                $tempSchedule = new ClassSchedule([
                    'id' => $schedule->id, // Include ID to exclude from conflict check
                    'school_id' => $this->getCurrentSchoolId(),
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'teacher_id' => $request->teacher_id,
                    'day_of_week' => $request->day_of_week,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'room_number' => $request->room_number,
                    'academic_year_id' => $request->academic_year_id,
                    'semester' => $request->semester,
                    'is_active' => $request->is_active
                ]);

                // Check for conflicts (excluding current schedule)
                $conflicts = $tempSchedule->getConflicts();
                if (!empty($conflicts)) {
                    $conflictMessages = array_map(fn($conflict) => $conflict['message'], $conflicts);
                    throw new \Exception('Schedule conflicts detected: ' . implode('; ', $conflictMessages));
                }

                // Update the schedule
                $schedule->update([
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'teacher_id' => $request->teacher_id,
                    'day_of_week' => $request->day_of_week,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'room_number' => $request->room_number,
                    'academic_year_id' => $request->academic_year_id,
                    'semester' => $request->semester,
                    'is_active' => $request->is_active
                ]);

                DB::commit();

                return [
                    'message' => 'Schedule updated successfully',
                    'schedule' => $schedule->fresh()->load(['class', 'subject', 'teacher', 'academicYear'])
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }

    /**
     * Remove the specified schedule
     */
    public function destroy(ClassSchedule $schedule): JsonResponse
    {
        if (!$this->user->isAdmin() || $schedule->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($schedule) {
            $schedule->delete();

            return [
                'message' => 'Schedule deleted successfully'
            ];
        });
    }

    /**
     * Check for schedule conflicts
     */
    public function checkConflicts(Request $request): JsonResponse
    {
        if (!$this->user->isAdmin()) {
            return $this->ajaxError('Access denied.');
        }

        $request->validate([
            'class_id' => 'required|exists:class_models,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|string|max:20',
            'exclude_id' => 'nullable|exists:class_schedules,id'
        ]);

        return $this->handleAjaxRequest(function() use ($request) {
            // Create temporary schedule to check conflicts
            $tempSchedule = new ClassSchedule([
                'id' => $request->exclude_id,
                'school_id' => $this->getCurrentSchoolId(),
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'day_of_week' => $request->day_of_week,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'room_number' => $request->room_number,
                'academic_year_id' => $request->academic_year_id,
                'semester' => $request->semester,
                'is_active' => true
            ]);

            $conflicts = $tempSchedule->getConflicts();

            return [
                'has_conflicts' => !empty($conflicts),
                'conflicts' => $conflicts,
                'conflict_count' => count($conflicts)
            ];
        });
    }

    /**
     * Get class schedule
     */
    public function getClassSchedule(ClassModel $class): JsonResponse
    {
        if (!$this->user->isAdmin() || $class->school_id !== $this->getCurrentSchoolId()) {
            return $this->ajaxError('Access denied.');
        }

        return $this->handleAjaxRequest(function() use ($class) {
            $schedules = ClassSchedule::where('class_id', $class->id)
                                    ->where('is_active', true)
                                    ->with(['subject', 'teacher', 'academicYear'])
                                    ->orderBy('day_of_week')
                                    ->orderBy('start_time')
                                    ->get();

            // Group by day of week
            $weeklySchedule = [];
            $weekDays = $this->getWeekDays();

            foreach ($weekDays as $day => $dayName) {
                $weeklySchedule[$day] = [
                    'day_name' => $dayName,
                    'schedules' => $schedules->where('day_of_week', $day)->values()
                ];
            }

            return [
                'class' => $class,
                'weekly_schedule' => $weeklySchedule,
                'total_schedules' => $schedules->count(),
                'total_hours' => $schedules->sum('duration_minutes') / 60
            ];
        });
    }

    /**
     * Get schedule statistics
     */
    private function getScheduleStatistics(): array
    {
        $schoolId = $this->getCurrentSchoolId();
        
        $total = ClassSchedule::where('school_id', $schoolId)->count();
        $active = ClassSchedule::where('school_id', $schoolId)->where('is_active', true)->count();
        $inactive = ClassSchedule::where('school_id', $schoolId)->where('is_active', false)->count();
        $withConflicts = ClassSchedule::where('school_id', $schoolId)->withConflicts()->count();
        
        // Statistics by day
        $dayStats = ClassSchedule::where('school_id', $schoolId)
                                ->where('is_active', true)
                                ->selectRaw('day_of_week, COUNT(*) as count')
                                ->groupBy('day_of_week')
                                ->pluck('count', 'day_of_week')
                                ->toArray();

        // Total teaching hours per week
        $totalMinutes = ClassSchedule::where('school_id', $schoolId)
                                   ->where('is_active', true)
                                   ->get()
                                   ->sum('duration_minutes');

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'with_conflicts' => $withConflicts,
            'total_hours_per_week' => round($totalMinutes / 60, 2),
            'day_statistics' => $dayStats,
            'average_per_day' => $active > 0 ? round($active / 7, 2) : 0
        ];
    }

    /**
     * Get week days
     */
    private function getWeekDays(): array
    {
        return [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];
    }

    /**
     * Get time slots
     */
    private function getTimeSlots(): array
    {
        $slots = [];
        
        for ($hour = 7; $hour <= 18; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $time = sprintf('%02d:%02d', $hour, $minute);
                $slots[] = [
                    'value' => $time,
                    'label' => Carbon::createFromFormat('H:i', $time)->format('g:i A')
                ];
            }
        }
        
        return $slots;
    }
}