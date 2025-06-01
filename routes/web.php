<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', [StudentController::class, 'index'])->name('home');
Route::post('/check-graduation', [StudentController::class, 'checkGraduation'])->name('check.graduation');
Route::get('/certificate/{student}', [StudentController::class, 'generatePDF'])->name('generate.pdf');
Route::get('/verify/{student}/{hash}', [StudentController::class, 'verifyCertificate'])->name('verify.certificate');

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('login.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Student management
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::get('/students/create', [AdminController::class, 'createStudent'])->name('students.create');
    Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/{student}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
    Route::put('/students/{student}', [AdminController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{student}', [AdminController::class, 'destroyStudent'])->name('students.destroy');

    // Bulk actions
    Route::post('/students/bulk-action', [AdminController::class, 'bulkAction'])->name('students.bulk-action');
    Route::get('/students/export', [AdminController::class, 'exportStudents'])->name('students.export');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});
