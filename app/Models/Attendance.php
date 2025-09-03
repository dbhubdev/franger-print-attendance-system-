<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
     protected $fillable = [
        'uid',
        'emp_id',
        'punch_time'
    ];

    protected $dates = ['punch_time'];
}
