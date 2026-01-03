<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTimetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'exam_type_id',
        'subject_id',
        'class',
        'section',
        'academic_year',
        'exam_date',
        'start_time',
        'end_time',
        'exam_center',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getTimeSlotAttribute()
    {
        return $this->start_time->format('H:i').' - '.$this->end_time->format('H:i');
    }

    public function getFormattedDateAttribute()
    {
        return $this->exam_date->format('d M Y');
    }

    public static function getForClassAndExam($schoolId, $class, $section, $examTypeId, $academicYear)
    {
        return self::where('school_id', $schoolId)
            ->where('class', $class)
            ->where('section', $section)
            ->where('exam_type_id', $examTypeId)
            ->where('academic_year', $academicYear)
            ->where('is_active', true)
            ->with(['subject', 'examType'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();
    }
}
