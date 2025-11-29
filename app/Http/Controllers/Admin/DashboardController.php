<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::where('role', 'employee')->count();
        $totalDepartments = Department::count();
        
        $today = Carbon::today();
        $presentToday = Attendance::where('date', $today)
            ->where('status', 'present')
            ->count();

        // Recent Attendance (Last 5 records)
        $recentAttendance = Attendance::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalEmployees', 'totalDepartments', 'presentToday', 'recentAttendance'));
    }
}
