<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    protected $fillable = [
        'school_id',
        'class_id',
        'subject_id',
        'title',
        'description',
        'academic_year',
        'file_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
