<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'employee');
            })
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->paginate(20);

        return view('admin.attendance.index', compact('attendances'));
    }
}
