<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'class_id',
        'parent_id',
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'blood_group',
        'emergency_contact',
        'emergency_phone',
        'photo',
        'status',
        'admission_date',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
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

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
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
