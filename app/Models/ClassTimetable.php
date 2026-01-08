<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassTimetable extends Model
{
    protected $fillable = [
        'school_id',
        'classroom_id',
        'subject_id',
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
