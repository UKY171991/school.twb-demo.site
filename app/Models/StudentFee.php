<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $fillable = [
        'student_id',
        'school_id',
        'amount',
        'fee_type',
        'month',
        'year',
        'status',
        'paid_date',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
