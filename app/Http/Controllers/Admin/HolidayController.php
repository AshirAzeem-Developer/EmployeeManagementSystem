<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    // Holiday ki list dikhayega
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'desc')->get();
        return view('admin.holidays.index', compact('holidays'));
    }

    // Nayi chutti banane ka form
    public function create()
    {
        return view('admin.holidays.create');
    }

    // Nayi chutti ko database mein save karega
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'date' => 'required|date|unique:tbl_holidays,date',
            'description' => 'required|string|max:191',
        ]);

        // 2. Create Holiday
        Holiday::create($request->all());

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday created successfully.');
    }

    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'date' => 'required|date|unique:tbl_holidays,date,' . $holiday->id,
            'description' => 'required|string|max:191',
        ]);

        $holiday->update($request->all());

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday deleted successfully.');
    }
}
