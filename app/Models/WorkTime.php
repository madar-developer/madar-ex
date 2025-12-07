<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    protected $table = 'work_times';
    protected $fillable = [
        'time_from', 'time_to', 'active'
    ];
}
