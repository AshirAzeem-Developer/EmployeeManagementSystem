<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Holiday extends Model
{
    use Loggable;
    protected $table = 'tbl_holidays';

    protected $fillable = [
        'date',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
