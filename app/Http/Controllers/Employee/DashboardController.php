<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Holiday;
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

        // Fetch holidays for current month
        $holidays = Holiday::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->pluck('date')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();

        // Prepare data for chart
        $dates = [];
        $hours = [];
        $colors = [];
        $statuses = [];
        $tooltipData = [];

        // Fill in all days of the month
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $dates[] = $date->format('d-M'); // e.g., "01-Dec"
            
            $attendance = $attendances->firstWhere('date', $dateString);
            
            $dayTooltip = [
                'fullDate' => $date->format('d-M (l)'), // e.g., "01-Dec (Monday)"
                'timeIn' => '--',
                'timeOut' => '--',
                'breakHrs' => '0:00',
                'remoteHrs' => '0:00',
                'totalWorkedHrs' => 0,
                'status' => ''
            ];

            if ($attendance) {
                // Determine color based on status
                $status = strtolower(trim($attendance->status));
                
                // Check for Missing Checkout (Check-in exists, Check-out is null)
                if ($attendance->check_in && !$attendance->check_out) {
                    $status = 'missing';
                }

                $statuses[] = ucfirst($status);
                $dayTooltip['status'] = '(' . ucfirst($status) . ')';

                // Calculate hours and times
                if ($attendance->check_in) {
                    $dayTooltip['timeIn'] = Carbon::parse($attendance->check_in)->format('g:i a');
                }

                if ($attendance->check_in && $attendance->check_out) {
                    $checkIn = Carbon::parse($attendance->check_in);
                    $checkOut = Carbon::parse($attendance->check_out);
                    $dayTooltip['timeOut'] = $checkOut->format('g:i a');
                    
                    $workedMinutes = $checkIn->diffInMinutes($checkOut);
                    $workedHours = $workedMinutes / 60;
                    $hours[] = round($workedHours, 2);
                    $dayTooltip['totalWorkedHrs'] = round($workedHours, 2);
                } else {
                    // Visual height for non-working/missing statuses
                    $hours[] = 9; 
                }

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
                    case 'missing':
                        $colors[] = '#be185d'; // Pink
                        break;
                    case 'early':
                        $colors[] = '#22c55e'; // Green
                        break;
                    case 'short day':
                        $colors[] = '#f97316'; // Orange
                        break;
                    case 'absent for short time':
                        $colors[] = '#374151'; // Gray-700
                        break;
                    case 'h/d leave':
                        $colors[] = '#a16207'; // Yellow-700
                        break;
                    case 'sch days':
                        $colors[] = '#64748b'; // Slate-500
                        break;
                    case 'off':
                    case 'holiday':
                    case 'off/holiday':
                        $colors[] = '#000000'; // Black
                        break;
                   
                    default:
                        $colors[] = '#6b7280'; // Gray
                }
            } else {
                // No record found
                
                if ($date->isWeekend()) {
                    // Weekend (Sat/Sun) -> OFF
                    $statuses[] = 'OFF';
                    $colors[] = '#000000'; // Black
                    $hours[] = 9; // Visual height
                    $dayTooltip['status'] = '(OFF)';
                } elseif (in_array($dateString, $holidays)) {
                    // Holiday -> OFF/Holiday
                    $statuses[] = 'Holiday';
                    $colors[] = '#000000'; // Black
                    $hours[] = 9; // Visual height
                    $dayTooltip['status'] = '(Holiday)';
                } elseif ($date->isFuture()) {
                    // Future Date -> Scheduled
                    $statuses[] = 'Scheduled';
                    $colors[] = '#607d8b'; // Blue Gray
                    $hours[] = 9; // Visual height
                    $dayTooltip['status'] = '(Scheduled)';
                } else {
                    // Past Weekday without attendance -> Absent
                    $statuses[] = 'Absent';
                    $colors[] = '#ef4444'; // Red
                    $hours[] = 9; // Visual height
                    $dayTooltip['status'] = '(Absent)';
                }
            }
            $tooltipData[] = $dayTooltip;
        }

        return view('dashboard', compact('dates', 'hours', 'colors', 'statuses', 'tooltipData'));
    }
}
