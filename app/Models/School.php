<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageUploadTrait;

class School extends Model
{
    use HasFactory, ImageUploadTrait;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'website',
        'principal_name',
        'logo',
        'description',
        'status',
        'settings'
    ];

    protected $casts = [
        'settings' => 'array'
    ];

    // Relationships
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function marksheets()
    {
        return $this->hasMany(Marksheet::class);
    }

    public function examTypes()
    {
        return $this->hasMany(ExamType::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helper methods
    public function getActiveStudentsCount()
    {
        return $this->students()->count();
    }

    public function getActiveTeachersCount()
    {
        return $this->teachers()->count();
    }

    public function getGradesCount()
    {
        return $this->grades()->count();
    }

    /**
     * Get the school's logo URL
     */
    public function getLogoUrlAttribute()
    {
        return $this->getImageUrl($this->logo);
    }

    /**
     * Get default logo if no logo
     */
    public function getLogoAttribute($value)
    {
        return $value ? $this->getImageUrl($value) : asset('images/default-school-logo.png');
    }
}
