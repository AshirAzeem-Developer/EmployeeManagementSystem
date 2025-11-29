<?php

namespace App\Http\Controllers;

use App\Models\AttendanceAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = AttendanceAdjustment::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('attendance.adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        return view('attendance.adjustments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'adjustment_date' => 'required|date|before_or_equal:today',
            'type' => 'required|in:check_in,check_out,full_day_missing',
            'requested_time' => 'nullable|required_if:type,check_in,check_out,full_day_missing',
            'check_out_time' => 'nullable|required_if:type,full_day_missing',
            'reason' => 'required|string|max:255',
        ]);

        AttendanceAdjustment::create([
            'user_id' => Auth::id(),
            'adjustment_date' => $request->adjustment_date,
            'type' => $request->type,
            'requested_time' => $request->requested_time,
            'check_out_time' => $request->check_out_time,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('attendance.adjustments.index')->with('success', 'Adjustment request submitted successfully.');
    }
}
