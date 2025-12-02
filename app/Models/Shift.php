<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Shift extends Model
{
    use Loggable;
    protected $table = 'tbl_shifts';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'grace_period_minutes',
    ];
}
