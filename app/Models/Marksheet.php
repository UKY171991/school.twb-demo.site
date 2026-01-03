<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marksheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'exam_type_id',
        'exam_name',
        'exam_date',
        'class',
        'section',
        'academic_year',
        'total_marks',
        'obtained_marks',
        'percentage',
        'grade',
        'result',
        'class_position',
        'total_students',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function marks()
    {
        return $this->hasMany(MarksheetMark::class);
    }

    public function calculateResult()
    {
        $this->load('marks.subject');

        $totalMarks = $this->marks->sum(function ($mark) {
            return $mark->subject->max_marks;
        });

        $obtainedMarks = $this->marks->sum('obtained_marks');
        $percentage = $totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0;

        // Check if passed in all subjects
        $allPassed = $this->marks->every(function ($mark) {
            return $mark->isPassed();
        });

        $this->update([
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'percentage' => round($percentage, 2),
            'grade' => $this->calculateGrade($percentage),
            'result' => $allPassed ? 'PASS' : 'FAIL',
        ]);

        // Calculate class position
        $this->calculateClassPosition();
    }

    public function calculateClassPosition()
    {
        // Get all marksheets for the same class, section, exam type, and academic year
        $query = self::where('class', $this->class)
            ->where('section', $this->section)
            ->where('academic_year', $this->academic_year);

        // Only filter by exam_type_id if it's set
        if ($this->exam_type_id) {
            $query->where('exam_type_id', $this->exam_type_id);
        }

        $classMarksheets = $query->orderBy('percentage', 'desc')
            ->orderBy('obtained_marks', 'desc')
            ->get();

        $totalStudents = $classMarksheets->count();

        // Find position by comparing percentages
        $position = 1;
        $currentPercentage = $this->percentage;

        foreach ($classMarksheets as $index => $marksheet) {
            if ($marksheet->id === $this->id) {
                $position = $index + 1;
                break;
            }
        }

        // If student failed, still show position but mark it differently
        $this->update([
            'class_position' => $position,
            'total_students' => $totalStudents,
        ]);

        // Recalculate positions for all students in the same class/section/exam
        $this->recalculateAllPositions();
    }

    public function recalculateAllPositions()
    {
        // Get all marksheets for the same class, section, exam type, and academic year
        $query = self::where('class', $this->class)
            ->where('section', $this->section)
            ->where('academic_year', $this->academic_year);

        // Only filter by exam_type_id if it's set
        if ($this->exam_type_id) {
            $query->where('exam_type_id', $this->exam_type_id);
        }

        $classMarksheets = $query->orderBy('percentage', 'desc')
            ->orderBy('obtained_marks', 'desc')
            ->get();

        $totalStudents = $classMarksheets->count();

        // Update positions for all students
        foreach ($classMarksheets as $index => $marksheet) {
            $marksheet->update([
                'class_position' => $index + 1,
                'total_students' => $totalStudents,
            ]);
        }
    }

    private function calculateGrade($percentage)
    {
        // Use configurable grading system
        $gradingSystem = \App\Models\GradingSystem::getGradeForPercentage($percentage);

        if ($gradingSystem) {
            return $gradingSystem->grade;
        }

        // Fallback to default grading if no system configured
        if ($percentage >= 90) {
            return 'A+';
        }
        if ($percentage >= 80) {
            return 'A';
        }
        if ($percentage >= 70) {
            return 'B+';
        }
        if ($percentage >= 60) {
            return 'B';
        }
        if ($percentage >= 50) {
            return 'C+';
        }
        if ($percentage >= 40) {
            return 'C';
        }
        if ($percentage >= 33) {
            return 'D';
        }

        return 'F';
    }
}
