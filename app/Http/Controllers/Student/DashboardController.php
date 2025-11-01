<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\BaseDashboardController;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\View\View;
use Illuminate\Http\Request;

class DashboardController extends BaseDashboardController
{
    public function index(): View
    {
        // Get base dashboard data
        $viewData = $this->getDashboardViewData();
        
        // Add student-specific data
        $student = $this->user->student;
        if ($student) {
            $student->load(['classModel', 'school']);
            
            $viewData['student'] = $student;
            $viewData['subjects'] = $this->getStudentSubjects($student);
            $viewData['recentGrades'] = $this->getRecentGrades($student);
            $viewData['pageTitle'] = 'Student Dashboard - ' . $student->full_name;
        }

        return view('student.dashboard', $viewData);
    }

    /**
     * Get student's subjects
     */
    private function getStudentSubjects($student)
    {
        if (!$student || !$student->class_id) {
            return collect();
        }

        return Subject::with('teacher.user')
            ->where('class_id', $student->class_id)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get student's recent grades
     */
    private function getRecentGrades($student)
    {
        if (!$student) {
            return collect();
        }

        return Grade::with(['subject'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get student academic data via AJAX
     */
    public function getAcademicData(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            $student = $this->user->student;
            if (!$student) {
                return $this->errorResponse('Student profile not found');
            }

            return [
                'subjects' => $this->getStudentSubjects($student),
                'recent_grades' => $this->getRecentGrades($student),
                'statistics' => $this->getDashboardStatistics()
            ];
        });
    }

    /**
     * Get student schedule via AJAX
     */
    public function getSchedule(Request $request)
    {
        return $this->handleAjaxRequest(function() {
            $student = $this->user->student;
            if (!$student) {
                return $this->errorResponse('Student profile not found');
            }

            // Placeholder implementation - will be enhanced in later tasks
            return [
                'today_classes' => [],
                'upcoming_exams' => [],
                'assignments' => []
            ];
        });
    }
}
