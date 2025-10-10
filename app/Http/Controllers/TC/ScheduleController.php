<?php

namespace App\Http\Controllers\TC;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Subject;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;
        
        // Get teacher's classes and subjects
        $classes = ClassModel::with(['school', 'subjects'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        $subjects = Subject::with(['classModel'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        // Create weekly schedule (Monday to Friday)
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $timeSlots = [
            '08:00' => '08:45',
            '08:45' => '09:30',
            '09:45' => '10:30',
            '10:30' => '11:15',
            '11:30' => '12:15',
            '12:15' => '13:00',
            '14:00' => '14:45',
            '14:45' => '15:30',
            '15:45' => '16:30',
            '16:30' => '17:15'
        ];

        // Get current week's dates
        $currentWeek = [];
        $startOfWeek = Carbon::now()->startOfWeek();
        
        for ($i = 0; $i < 5; $i++) {
            $currentWeek[] = $startOfWeek->copy()->addDays($i);
        }

        // Sample schedule data (in a real application, this would come from a database)
        $scheduleData = [];
        foreach ($daysOfWeek as $dayIndex => $day) {
            foreach ($timeSlots as $startTime => $endTime) {
                $scheduleData[$day][$startTime] = [
                    'class' => $classes->random()->name ?? null,
                    'subject' => $subjects->random()->name ?? null,
                    'room' => 'Room ' . rand(101, 120),
                ];
            }
        }

        return view('tc.schedule.index', compact('classes', 'subjects', 'daysOfWeek', 'timeSlots', 'currentWeek', 'scheduleData'));
    }

    public function getTodaySchedule()
    {
        $teacher = auth()->user()->teacher;
        $today = Carbon::today();
        $dayOfWeek = $today->format('l'); // Monday, Tuesday, etc.

        // In a real application, you would fetch this from a schedules table
        $todaySchedule = [
            '08:00-08:45' => ['class' => 'Grade 10A', 'subject' => 'Mathematics', 'room' => 'Room 201'],
            '08:45-09:30' => ['class' => 'Grade 10A', 'subject' => 'Mathematics', 'room' => 'Room 201'],
            '09:45-10:30' => ['class' => 'Grade 11B', 'subject' => 'Physics', 'room' => 'Room 305'],
            '10:30-11:15' => ['class' => 'Grade 11B', 'subject' => 'Physics', 'room' => 'Room 305'],
            '11:30-12:15' => ['class' => 'Grade 12A', 'subject' => 'Advanced Mathematics', 'room' => 'Room 401'],
            '12:15-13:00' => ['class' => 'Grade 12A', 'subject' => 'Advanced Mathematics', 'room' => 'Room 401'],
            '14:00-14:45' => ['class' => 'Grade 10B', 'subject' => 'Mathematics', 'room' => 'Room 202'],
            '14:45-15:30' => ['class' => 'Grade 10B', 'subject' => 'Mathematics', 'room' => 'Room 202'],
            '15:45-16:30' => ['class' => 'Grade 11A', 'subject' => 'Physics', 'room' => 'Room 304'],
            '16:30-17:15' => ['class' => 'Grade 11A', 'subject' => 'Physics', 'room' => 'Room 304'],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $today->format('Y-m-d'),
                'day' => $dayOfWeek,
                'schedule' => $todaySchedule
            ]
        ]);
    }

    public function getWeekSchedule(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $weekStart = Carbon::parse($request->date ?? now())->startOfWeek();
        
        $weekSchedule = [];
        for ($i = 0; $i < 5; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $dayOfWeek = $date->format('l');
            
            // In a real application, you would fetch this from a schedules table
            $daySchedule = [
                '08:00-08:45' => ['class' => 'Grade 10A', 'subject' => 'Mathematics', 'room' => 'Room 201'],
                '08:45-09:30' => ['class' => 'Grade 10A', 'subject' => 'Mathematics', 'room' => 'Room 201'],
                '09:45-10:30' => ['class' => 'Grade 11B', 'subject' => 'Physics', 'room' => 'Room 305'],
                '10:30-11:15' => ['class' => 'Grade 11B', 'subject' => 'Physics', 'room' => 'Room 305'],
                '11:30-12:15' => ['class' => 'Grade 12A', 'subject' => 'Advanced Mathematics', 'room' => 'Room 401'],
                '12:15-13:00' => ['class' => 'Grade 12A', 'subject' => 'Advanced Mathematics', 'room' => 'Room 401'],
                '14:00-14:45' => ['class' => 'Grade 10B', 'subject' => 'Mathematics', 'room' => 'Room 202'],
                '14:45-15:30' => ['class' => 'Grade 10B', 'subject' => 'Mathematics', 'room' => 'Room 202'],
                '15:45-16:30' => ['class' => 'Grade 11A', 'subject' => 'Physics', 'room' => 'Room 304'],
                '16:30-17:15' => ['class' => 'Grade 11A', 'subject' => 'Physics', 'room' => 'Room 304'],
            ];
            
            $weekSchedule[$dayOfWeek] = [
                'date' => $date->format('Y-m-d'),
                'schedule' => $daySchedule
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $weekSchedule
        ]);
    }
}
