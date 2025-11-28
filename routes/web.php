<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- ADMIN PANEL ROUTES ---
// 'admin' middleware lagaya takay sirf admin user hi access kar sakein
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Naya admin dashboard view
    })->name('dashboard');

    // Shifts Management Routes
    Route::resource('shifts', ShiftController::class);

    // Holidays Management Routes
    // Route::resource('holidays', HolidayController::class);

    // // Adjustment Approvals ka route
    // Route::get('adjustments/pending', [AdjustmentController::class, 'pending'])->name('adjustments.pending');
    // Route::post('adjustments/approve/{adjustment}', [AdjustmentController::class, 'approve'])->name('adjustments.approve');
    // ... aur baqi admin routes

});

require __DIR__ . '/auth.php';
