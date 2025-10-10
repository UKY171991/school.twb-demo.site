<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'school_id',
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'occupation',
        'company',
        'annual_income',
        'photo',
        'relationship',
        'is_primary_contact',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'annual_income' => 'decimal:2',
        'is_primary_contact' => 'boolean',
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

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Accessor
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
