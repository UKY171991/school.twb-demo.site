<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_id',
        'grade',
        'comments',
        'graded_at',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
