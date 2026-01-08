<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTimetable extends Model
{
    protected $fillable = [
        'school_id',
        'subject_id',
        'classroom_id',
        'exam_name',
        'exam_date',
        'start_time',
        'end_time',
        'remarks',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
