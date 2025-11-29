<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'tbl_shifts';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'grace_period_minutes',
    ];
}
