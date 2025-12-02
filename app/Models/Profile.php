<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Profile extends Model
{
    use Loggable;
    protected $table = 'tbl_profiles';
    //
    protected $fillable = [
        'user_id',
        'phone_number',
        'date_of_birth',
        'current_address',
        'cnic',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Profile belongs to One User
    }
}
