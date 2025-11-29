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

        // Fetch last 7 days attendance
        $startDate = Carbon::now()->subDays(6);
        $endDate = Carbon::now();

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date', 'asc')
            ->get();

        // Prepare data for chart
        $labels = [];
        $data = [];

        // Fill in missing days with 0 hours
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('D, d M');
            
            $attendance = $attendances->firstWhere('date', $dateString);
            
            if ($attendance && $attendance->check_in && $attendance->check_out) {
                $checkIn = Carbon::parse($attendance->check_in);
                $checkOut = Carbon::parse($attendance->check_out);
                $hours = $checkIn->diffInMinutes($checkOut) / 60;
                $data[] = round($hours, 2);
            } else {
                $data[] = 0;
            }
        }

        return view('dashboard', compact('labels', 'data'));
    }
}
