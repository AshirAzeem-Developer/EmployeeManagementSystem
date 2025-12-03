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
    public function scan()
    {
        return view('admin.attendance.scan');
    }

    public function markByQr(Request $request)
    {
        try {
            $request->validate([
                'employee_code' => 'required|string|exists:users,employee_code',
            ]);

            $user = \App\Models\User::where('employee_code', $request->employee_code)->first();
            
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found.'], 404);
            }

            $today = \Carbon\Carbon::today();
            $now = \Carbon\Carbon::now();

            // Check if attendance already exists for today
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();

            if ($attendance) {
                // Already checked in, so check out
                if ($attendance->check_out) {
                     return response()->json([
                        'status' => 'error',
                        'message' => 'Already checked out for today.',
                        'user' => $user->name,
                        'time' => $now->format('H:i:s')
                    ]);
                }

                $attendance->update([
                    'check_out' => $now->format('H:i:s'),
                    'status' => 'Present', // Or calculate based on hours
                ]);

                return response()->json([
                    'status' => 'success',
                    'type' => 'checkout',
                    'message' => 'Checked Out Successfully',
                    'user' => $user->name,
                    'time' => $now->format('H:i:s')
                ]);

            } else {
                // New Check In
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today->format('Y-m-d'),
                    'check_in' => $now->format('H:i:s'),
                    'status' => 'Present', // Default status
                ]);

                return response()->json([
                    'status' => 'success',
                    'type' => 'checkin',
                    'message' => 'Checked In Successfully',
                    'user' => $user->name,
                    'time' => $now->format('H:i:s')
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
             return response()->json([
                'status' => 'error',
                'message' => 'Invalid Employee Code',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('QR Scan Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'System Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
