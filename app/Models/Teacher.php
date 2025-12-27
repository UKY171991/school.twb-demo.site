<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageUploadTrait;

class Teacher extends Model
{
    use ImageUploadTrait;
    protected $fillable = [
        'school_id',
        'name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'date_of_joining',
        'address',
        'image'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the teacher's image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->getImageUrl($this->attributes['image'] ?? null);
    }
}
