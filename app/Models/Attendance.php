<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'attendance_date',
        'status',
        'note',
    ];

    /**
     * Cast attendance_date to a Carbon date instance so formatting is safe.
     */
    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
