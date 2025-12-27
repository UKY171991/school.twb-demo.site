<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'duration_days',
        'weightage',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'weightage' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function marksheets()
    {
        return $this->hasMany(Marksheet::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public static function getActiveTypes($schoolId = null)
    {
        $query = self::where('is_active', true);
        
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        
        return $query->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
    }

    public static function getTypeOptions($schoolId = null)
    {
        return self::getActiveTypes($schoolId)->pluck('name', 'id');
    }
}
