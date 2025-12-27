<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marksheet extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'total_students'
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

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
        
        $totalMarks = $this->marks->sum(function($mark) {
            return $mark->subject->max_marks;
        });
        
        $obtainedMarks = $this->marks->sum('obtained_marks');
        $percentage = $totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0;
        
        // Check if passed in all subjects
        $allPassed = $this->marks->every(function($mark) {
            return $mark->isPassed();
        });
        
        $this->update([
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'percentage' => round($percentage, 2),
            'grade' => $this->calculateGrade($percentage),
            'result' => $allPassed ? 'PASS' : 'FAIL'
        ]);

        // Calculate class position
        $this->calculateClassPosition();
    }

    public function calculateClassPosition()
    {
        // Get all marksheets for the same class, section, exam type, and academic year
        $classMarksheets = self::where('class', $this->class)
                              ->where('section', $this->section)
                              ->where('exam_type_id', $this->exam_type_id)
                              ->where('academic_year', $this->academic_year)
                              ->where('result', 'PASS') // Only consider passed students for ranking
                              ->orderBy('percentage', 'desc')
                              ->get();

        $totalStudents = $classMarksheets->count();
        $position = $classMarksheets->search(function($marksheet) {
            return $marksheet->id === $this->id;
        }) + 1;

        // If student failed, position is null
        if ($this->result === 'FAIL') {
            $position = null;
        }

        $this->update([
            'class_position' => $position,
            'total_students' => $totalStudents
        ]);
    }

    private function calculateGrade($percentage)
    {
        // Use configurable grading system
        $gradingSystem = \App\Models\GradingSystem::getGradeForPercentage($percentage);
        
        if ($gradingSystem) {
            return $gradingSystem->grade;
        }
        
        // Fallback to default grading if no system configured
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 33) return 'D';
        return 'F';
    }
}