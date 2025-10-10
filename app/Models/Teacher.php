<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'qualification',
        'experience',
        'salary',
        'joining_date',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Accessor
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
