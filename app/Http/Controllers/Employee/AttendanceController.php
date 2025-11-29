<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Check if already checked in today
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // Get attendance history
        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('employee.attendance.index', compact('todayAttendance', 'attendances'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Check if already checked in
        $exists = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'You have already checked in today.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => Carbon::now()->format('H:i:s'),
            'status' => 'present',
        ]);

        return redirect()->back()->with('success', 'Checked in successfully.');
    }

    public function update(Request $request, Attendance $attendance)
    {
        // Ensure the attendance belongs to the user and is for today
        if ($attendance->user_id !== Auth::id() || $attendance->date != Carbon::today()->format('Y-m-d')) {
            return redirect()->back()->with('error', 'Invalid attendance record.');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'You have already checked out.');
        }

        $attendance->update([
            'check_out' => Carbon::now()->format('H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Checked out successfully.');
    }
}
