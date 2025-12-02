<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Department extends Model
{
    use Loggable;
    protected $table = 'tbl_departments';
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->hasMany(User::class); // Department has many Users
    }
}
