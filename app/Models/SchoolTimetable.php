<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTimetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'teacher_id',
        'subject_id',
        'class',
        'section',
        'day_of_week',
        'start_time',
        'end_time',
        'room_number',
        'notes',
        'academic_year',
        'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeForClass($query, $class, $section = null)
    {
        $query->where('class', $class);
        if ($section) {
            $query->where('section', $section);
        }
        return $query;
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeForDay($query, $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    public function scopeForAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    // Helper methods
    public function getDurationAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        $hours = $start->diffInHours($end);
        $minutes = $start->diffInMinutes($end) % 60;
        
        return $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '');
    }

    public function getClassSectionAttribute()
    {
        return $this->class . ($this->section ? '-' . $this->section : '');
    }

    // Static methods
    public static function getDaysOfWeek()
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    }

    public static function getTimeSlots()
    {
        return [
            '08:00' => '8:00 AM',
            '08:45' => '8:45 AM',
            '09:30' => '9:30 AM',
            '10:15' => '10:15 AM',
            '11:00' => '11:00 AM',
            '11:45' => '11:45 AM',
            '12:30' => '12:30 PM',
            '13:15' => '1:15 PM',
            '14:00' => '2:00 PM',
            '14:45' => '2:45 PM',
            '15:30' => '3:30 PM',
            '16:15' => '4:15 PM'
        ];
    }
}
