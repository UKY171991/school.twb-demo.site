<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'roll_number',
        'class',
        'section',
        'father_name',
        'mother_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'grade_id'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function marksheets()
    {
        return $this->hasMany(Marksheet::class);
    }

    public function marksheetMarks()
    {
        return $this->hasMany(MarksheetMark::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}