<?php

namespace App\Models;

use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'principal_signature',
        'exam_controller_signature',
        'logo',
        'description',
        'status',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
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
        return $this->getImageUrl($this->attributes['logo'] ?? null);
    }

    /**
     * Get the principal's signature URL
     */
    public function getPrincipalSignatureUrlAttribute()
    {
        return $this->getImageUrl($this->attributes['principal_signature'] ?? null);
    }

    /**
     * Get the exam controller's signature URL
     */
    public function getExamControllerSignatureUrlAttribute()
    {
        return $this->getImageUrl($this->attributes['exam_controller_signature'] ?? null);
    }
}
