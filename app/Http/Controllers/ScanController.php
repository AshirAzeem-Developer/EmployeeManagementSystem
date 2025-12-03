<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScanController extends Controller
{
    public function markByQr(Request $request)
    {
        try {
            $request->validate([
                'employee_code' => 'required|string|exists:users,employee_code',
            ]);

            $user = User::where('employee_code', $request->employee_code)->first();

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Employee not found.'], 404);
            }

            $today = Carbon::today();
            $now = Carbon::now();

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

                // Cooldown Check: Prevent check-out if check-in was less than 10 minutes ago
                $checkInTime = Carbon::parse($attendance->check_in);
                $diffMinutes = $now->diffInMinutes($checkInTime, true);

                if ($diffMinutes < 5) {
                    $remainingMinutes = ceil(5 - $diffMinutes); // 🔥 No decimals

                    return response()->json([
                        'status' => 'error',
                        'message' => "Cooldown active. Please wait {$remainingMinutes} minutes before checking out.",
                        'user' => $user->name,
                        'time' => $remainingMinutes . " minutes"
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
            Log::error('QR Scan Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'System Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
