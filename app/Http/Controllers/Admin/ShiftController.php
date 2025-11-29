<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    // Shift ki list dikhayega
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shifts.index', compact('shifts'));
    }

    // Nayi shift banane ka form
    public function create()
    {
        return view('admin.shifts.create');
    }

    // Nayi shift ko database mein save karega
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|string|unique:tbl_shifts,name',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'grace_period_minutes' => 'required|integer|min:0',
        ]);

        // 2. Create Shift
        Shift::create($request->all());

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name' => 'required|string|unique:tbl_shifts,name,' . $shift->id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'grace_period_minutes' => 'required|integer|min:0',
        ]);

        $shift->update($request->all());

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }
}
