<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade',
        'min_percentage',
        'max_percentage',
        'grade_points',
        'description',
        'is_passing',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'min_percentage' => 'decimal:2',
        'max_percentage' => 'decimal:2',
        'grade_points' => 'decimal:2',
        'is_passing' => 'boolean',
        'is_active' => 'boolean'
    ];

    public static function getGradeForPercentage($percentage)
    {
        return self::where('is_active', true)
                   ->where('min_percentage', '<=', $percentage)
                   ->where('max_percentage', '>=', $percentage)
                   ->first();
    }

    public static function getActiveGrades()
    {
        return self::where('is_active', true)
                   ->orderBy('sort_order')
                   ->orderBy('min_percentage', 'desc')
                   ->get();
    }
}
