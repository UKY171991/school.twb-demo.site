<?php

namespace App\Models;

use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;

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
        'designation',
        'image',
        'signature',
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

    public function getSignatureUrlAttribute()
    {
        return $this->getImageUrl($this->attributes['signature'] ?? null);
    }
}
