<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function marksheets()
    {
        return $this->hasMany(Marksheet::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public static function getActiveTypes()
    {
        return self::where('is_active', true)
                   ->orderBy('sort_order')
                   ->orderBy('name')
                   ->get();
    }

    public static function getTypeOptions()
    {
        return self::getActiveTypes()->pluck('name', 'id');
    }
}
