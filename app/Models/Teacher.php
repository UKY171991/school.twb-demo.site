<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'date_of_joining',
        'address'
    ];
}
