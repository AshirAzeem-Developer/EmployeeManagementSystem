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
        
        // Present Today
        $presentToday = Attendance::whereDate('date', $today)
            ->where('status', 'present')
            ->whereHas('user', function ($query) {
                $query->where('role', 'employee');
            })
            ->count();

        // Absent Today (Total Employees - Present Today)
        // Note: This is a simple calculation. For more accuracy, we should check for leave applications etc.
        $absentToday = $totalEmployees - $presentToday;

        // Late Today
        $lateToday = Attendance::whereDate('date', $today)
            ->where('status', 'late')
            ->whereHas('user', function ($query) {
                $query->where('role', 'employee');
            })
            ->count();

        // Pending Adjustment Requests
        $pendingAdjustments = \App\Models\AttendanceAdjustment::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Upcoming Holidays
        $upcomingHolidays = \App\Models\Holiday::where('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->take(3)
            ->get();

        // Recent Attendance (Last 5 records)
        $recentAttendance = Attendance::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'employee');
            })
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees', 
            'totalDepartments', 
            'presentToday', 
            'absentToday', 
            'lateToday', 
            'pendingAdjustments', 
            'upcomingHolidays', 
            'recentAttendance'
        ));
    }
}
