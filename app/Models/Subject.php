<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'max_marks',
        'pass_marks',
        'grade_id',
        'teacher_id'
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
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