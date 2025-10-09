<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'employee_id',
        'qualification',
        'designation',
        'joining_date',
        'salary',
        'experience',
        'is_active',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id', 'user_id');
    }
}
