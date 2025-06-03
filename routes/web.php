<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\SecurityLogController;

// Public routes with rate limiting
Route::middleware(['rate.limit:30,1'])->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('home');
    Route::post('/check-graduation', [StudentController::class, 'checkGraduation'])->name('check.graduation');
    Route::get('/certificate/{student}', [StudentController::class, 'generatePDF'])->name('generate.pdf');
    Route::get('/verify/{student}/{hash}', [StudentController::class, 'verifyCertificate'])->name('verify.certificate');

    // Test protection page
    Route::get('/test-protection', function () {
        return view('test-protection');
    })->name('test.protection');
});

// Security logging API (with higher rate limit for legitimate logging)
Route::middleware(['rate.limit:60,1'])->group(function () {
    Route::post('/api/security-log', [SecurityLogController::class, 'logSecurityAttempt'])->name('api.security-log');
});

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

// Admin routes with security middleware
Route::middleware(['auth', 'admin.access', 'rate.limit:120,1'])->prefix('admin')->name('admin.')->group(function () {
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
    Route::get('/students/export-excel', [AdminController::class, 'exportStudentsExcel'])->name('students.export.excel');

    // Import Excel
    Route::get('/students/import', [AdminController::class, 'importForm'])->name('students.import');
    Route::post('/students/import', [AdminController::class, 'importStudents'])->name('students.import.process');
    Route::get('/students/template', [AdminController::class, 'downloadTemplate'])->name('students.template');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // Security monitoring
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/', [SecurityController::class, 'index'])->name('index');
        Route::get('/events', [SecurityController::class, 'events'])->name('events');
        Route::get('/audit-logs', [SecurityController::class, 'auditLogs'])->name('audit-logs');
        Route::get('/events/{id}', [SecurityController::class, 'eventDetails'])->name('event-details');
        Route::get('/audit-logs/{id}', [SecurityController::class, 'auditLogDetails'])->name('audit-log-details');
        Route::get('/export-report', [SecurityController::class, 'exportReport'])->name('export-report');
        Route::post('/clean-logs', [SecurityController::class, 'cleanLogs'])->name('clean-logs');
        Route::get('/protection-stats', [SecurityLogController::class, 'getProtectionStats'])->name('protection-stats');
    });
});
