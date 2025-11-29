<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualAttendanceController extends Controller
{
    public function create()
    {
        return view('admin.attendance.manual');
    }

    public function search(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|string',
        ]);

        $user = User::where('employee_code', $request->employee_code)
            ->where('role', 'employee')
            ->select('id', 'name', 'employee_code')
            ->first();

        if ($user) {
            return response()->json(['status' => 'success', 'user' => $user]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date|before_or_equal:today',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status' => 'required|in:present,late,absent,half_day',
            'notes' => 'nullable|string|max:255',
        ]);

        // Check if attendance already exists
        $attendance = Attendance::where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->first();

        if ($attendance) {
            // Update existing
            $attendance->update([
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'status' => $request->status,
                // 'notes' => $request->notes, // Assuming we add notes column later or use existing structure
            ]);
            $message = 'Attendance updated successfully.';
        } else {
            // Create new
            Attendance::create([
                'user_id' => $request->user_id,
                'date' => $request->date,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'status' => $request->status,
            ]);
            $message = 'Attendance marked successfully.';
        }

        return redirect()->route('admin.attendance.manual.create')->with('success', $message);
    }
}
