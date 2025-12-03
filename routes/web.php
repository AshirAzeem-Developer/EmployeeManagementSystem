<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/dashboard', [App\Http\Controllers\Employee\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employee Attendance Routes
    Route::get('/attendance', [App\Http\Controllers\Employee\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [App\Http\Controllers\Employee\AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('/attendance/{attendance}', [App\Http\Controllers\Employee\AttendanceController::class, 'update'])->name('attendance.update');
    
    // Employee Adjustment Routes
    Route::resource('attendance/adjustments', \App\Http\Controllers\AttendanceAdjustmentController::class)->names('attendance.adjustments');
});

// --- ADMIN PANEL ROUTES ---
// 'admin' middleware lagaya takay sirf admin user hi access kar sakein
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    // Attendance Report
    Route::get('attendance/scan', [\App\Http\Controllers\Admin\AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('attendance/scan', [\App\Http\Controllers\Admin\AttendanceController::class, 'markByQr'])->name('attendance.markByQr');
    Route::resource('attendance', \App\Http\Controllers\Admin\AttendanceController::class)->only(['index']);
    
    // Manual Attendance
    Route::get('attendance/manual', [\App\Http\Controllers\Admin\ManualAttendanceController::class, 'create'])->name('attendance.manual.create');
    Route::post('attendance/manual', [\App\Http\Controllers\Admin\ManualAttendanceController::class, 'store'])->name('attendance.manual.store');
    Route::post('attendance/manual/search', [\App\Http\Controllers\Admin\ManualAttendanceController::class, 'search'])->name('attendance.manual.search');

    // Adjustment Requests Management
    Route::resource('adjustments', \App\Http\Controllers\Admin\AttendanceAdjustmentController::class);
    // Shifts Management Routes
    Route::resource('shifts', ShiftController::class);

    // Holidays Management Routes
    Route::resource('holidays', HolidayController::class);

    Route::resource('departments', DepartmentController::class); // Department routes
    // // Adjustment Approvals ka route
    // Route::get('adjustments/pending', [AdjustmentController::class, 'pending'])->name('adjustments.pending');
    // Route::post('adjustments/approve/{adjustment}', [AdjustmentController::class, 'approve'])->name('adjustments.approve');
    // ... aur baqi admin routes

});



require __DIR__ . '/auth.php';
