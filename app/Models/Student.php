<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'class_id',
        'section_id',
        'parent_id',
        'admission_number',
        'roll_number',
        'admission_date',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'religion',
        'nationality',
        'address',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'date_of_birth' => 'date',
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

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
