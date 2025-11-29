<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = AttendanceAdjustment::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('admin.adjustments.index', compact('adjustments'));
    }

    public function update(Request $request, AttendanceAdjustment $adjustment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $adjustment->status = $request->status;
        $adjustment->approved_by = Auth::id();
        $adjustment->save();

        if ($request->status === 'approved') {
            $attendance = Attendance::firstOrCreate(
                [
                    'user_id' => $adjustment->user_id,
                    'date' => $adjustment->adjustment_date,
                ],
                [
                    'status' => 'absent', // Default status, will be updated
                ]
            );

            if ($adjustment->type === 'check_in') {
                $attendance->check_in = $adjustment->requested_time;
            } elseif ($adjustment->type === 'check_out') {
                $attendance->check_out = $adjustment->requested_time;
            } elseif ($adjustment->type === 'full_day_missing') {
                $attendance->check_in = $adjustment->requested_time;
                $attendance->check_out = $adjustment->check_out_time;
            }

            // Update status based on check-in/out
            if ($attendance->check_in && $attendance->check_out) {
                $attendance->status = 'present';
                // Calculate late status if needed (future enhancement)
            }

            $attendance->save();
        }

        return redirect()->route('admin.adjustments.index')->with('success', 'Adjustment request updated successfully.');
    }
}
