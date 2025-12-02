<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class AttendanceAdjustment extends Model
{
    use Loggable;
    protected $table = 'tbl_attendance_adjustments';

    protected $fillable = [
        'user_id',
        'adjustment_date',
        'type',
        'requested_time',
        'check_out_time',
        'reason',
        'status',
        'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}