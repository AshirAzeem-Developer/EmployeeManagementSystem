<?php

namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogActivity
{
    public static function addToLog($action, $description = null)
    {
        $log = [];
        $log['action'] = $action;
        $log['description'] = $description;
        $log['ip_address'] = Request::ip();
        
        if (Auth::check()) {
            $user = Auth::user();
            $log['employee_code'] = $user->employee_code;
            $log['created_by'] = $user->id;
            $log['updated_by'] = $user->id;
        }

        Log::create($log);
    }

    public static function log($action, $description = null)
    {
        self::addToLog($action, $description);
    }
}
