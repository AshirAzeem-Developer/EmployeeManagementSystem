<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin dashboard is separate, so if admin hits this, redirect or show admin view
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Fetch current month attendance
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        // Prepare data for chart
        $dates = [];
        $hours = [];
        $colors = [];
        $statuses = [];

        // Fill in all days of the month
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $dates[] = $date->format('d M'); // e.g., "01 Nov"
            
            $attendance = $attendances->firstWhere('date', $dateString);
            
            if ($attendance) {
                // Calculate hours
                if ($attendance->check_in && $attendance->check_out) {
                    $checkIn = Carbon::parse($attendance->check_in);
                    $checkOut = Carbon::parse($attendance->check_out);
                    $workedHours = $checkIn->diffInMinutes($checkOut) / 60;
                    $hours[] = round($workedHours, 2);
                } else {
                    $hours[] = 0;
                }

                // Determine color based on status
                $status = strtolower($attendance->status);
                $statuses[] = ucfirst($status);

                switch ($status) {
                    case 'present':
                        $colors[] = '#3b82f6'; // Blue
                        break;
                    case 'late':
                        $colors[] = '#eab308'; // Yellow
                        break;
                    case 'absent':
                        $colors[] = '#ef4444'; // Red
                        break;
                    case 'half_day':
                        $colors[] = '#14b8a6'; // Teal
                        break;
                    case 'leave':
                        $colors[] = '#8b5cf6'; // Purple
                        break;
                    default:
                        $colors[] = '#6b7280'; // Gray
                }
            } else {
                // No record found
                $hours[] = 0;
                
                if ($date->isWeekend()) {
                    // Weekend (Sat/Sun) -> OFF
                    $statuses[] = 'OFF';
                    $colors[] = '#6b7280'; // Gray
                } elseif ($date->isFuture()) {
                    // Future Date -> Scheduled
                    $statuses[] = 'Scheduled';
                    $colors[] = '#e5e7eb'; // Light Gray
                } else {
                    // Past Weekday without attendance -> Absent
                    $statuses[] = 'Absent';
                    $colors[] = '#ef4444'; // Red
                }
            }
        }

        return view('dashboard', compact('dates', 'hours', 'colors', 'statuses'));
    }
}
