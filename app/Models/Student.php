<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageUploadTrait;

class Student extends Model
{
    use HasFactory, ImageUploadTrait;

    protected $fillable = [
        'school_id',
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
        'grade_id',
        'image'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

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

    /**
     * Get the student's image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->getImageUrl($this->attributes['image'] ?? null);
    }
}