<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'start_date', 'end_date', 'description', 'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
