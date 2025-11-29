<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Validation rule use karne ke liye

class UserController extends Controller
{
    // Saare users ki list (admin ko chor kar)
    public function index()
    {
        // Login kiye hue admin ko list se hata dein aur sirf active users layein
        $users = User::with(['department', 'shift'])
            ->where('id', '!=', Auth::id())
            ->where('is_active', 1)
            ->get();

        return view('admin.users.index', compact('users'));
    }

    // Naya User banane ka form
    public function create()
    {
        $departments = Department::all();
        $shifts = Shift::all();
        return view('admin.users.create', compact('departments', 'shifts'));
    }

    // Naye User ko database mein save karega
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'employee'])],
            'department_id' => 'nullable|exists:tbl_departments,id',
            'shift_id' => 'nullable|exists:tbl_shifts,id',
            // 'employee_code' => 'nullable|string|unique:users,employee_code', // Auto-generated now
        ]);

        // Generate Employee Code
        $latestUser = User::whereNotNull('employee_code')->orderBy('id', 'desc')->first();
        if ($latestUser && preg_match('/^emp-(\d+)$/', $latestUser->employee_code, $matches)) {
            $nextId = intval($matches[1]) + 1;
        } else {
            $nextId = 1;
        }
        $employeeCode = 'emp-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'department_id' => $request->department_id,
            'shift_id' => $request->shift_id,
            'employee_code' => $employeeCode,
            'is_active' => 1,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Employee created successfully. Employee Code: ' . $employeeCode);
    }

    // User details edit karne ka form
    public function edit(User $user)
    {
        $departments = Department::all();
        $shifts = Shift::all();
        return view('admin.users.edit', compact('user', 'departments', 'shifts'));
    }

    // User details update karna
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'employee'])],
            'department_id' => 'nullable|exists:tbl_departments,id',
            'shift_id' => 'nullable|exists:tbl_shifts,id',            
            'employee_code' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'department_id' => $request->department_id,
            'shift_id' => $request->shift_id,
            'employee_code' => $request->employee_code,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $user)
    {
        // Soft delete using is_active column
        $user->update(['is_active' => 0]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
