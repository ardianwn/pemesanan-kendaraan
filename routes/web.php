<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// Debug routes to help troubleshoot session and CSRF issues
Route::get('/debug/session', [DebugController::class, 'showSessionInfo'])->name('debug.session');
Route::post('/debug/test-csrf', [DebugController::class, 'testCsrf'])->name('test-csrf');
Route::get('/debug/clear-sessions', [DebugController::class, 'clearSessions'])->name('debug.clear-sessions');

Route::get('/dashboard', [DashboardController::class, 'userDashboard'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Select2 Routes for dropdowns
Route::middleware('auth')->group(function() {
    Route::get('/kendaraan/select', [KendaraanController::class, 'select'])->name('kendaraan.select');
    Route::get('/driver/select', [DriverController::class, 'select'])->name('driver.select');
    Route::get('/approver/select', [ApproverController::class, 'selectApprover'])->name('approver.select');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Pemesanan Routes
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('/pemesanan/create', [PemesananController::class, 'create'])->name('pemesanan.create');
    Route::post('/pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/pemesanan/{pemesanan}', [PemesananController::class, 'show'])->name('pemesanan.show');
    Route::post('/pemesanan/{pemesanan}/finish', [PemesananController::class, 'finish'])->name('pemesanan.finish');
});    // Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    
    // Admin Kendaraan Routes
    Route::get('/kendaraan', [AdminController::class, 'kendaraanIndex'])->name('kendaraan.index');
    Route::get('/kendaraan/create', [AdminController::class, 'kendaraanCreate'])->name('kendaraan.create');
    Route::post('/kendaraan', [AdminController::class, 'kendaraanStore'])->name('kendaraan.store');
    Route::get('/kendaraan/{kendaraan}/edit', [AdminController::class, 'kendaraanEdit'])->name('kendaraan.edit');
    Route::put('/kendaraan/{kendaraan}', [AdminController::class, 'kendaraanUpdate'])->name('kendaraan.update');
    Route::delete('/kendaraan/{kendaraan}', [AdminController::class, 'kendaraanDestroy'])->name('kendaraan.destroy');
    
    // Driver Routes
    Route::get('/driver', [AdminController::class, 'driverIndex'])->name('driver.index');
    Route::get('/driver/create', [AdminController::class, 'driverCreate'])->name('driver.create');
    Route::post('/driver', [AdminController::class, 'driverStore'])->name('driver.store');
    Route::get('/driver/{driver}/edit', [AdminController::class, 'driverEdit'])->name('driver.edit');
    Route::put('/driver/{driver}', [AdminController::class, 'driverUpdate'])->name('driver.update');
    Route::delete('/driver/{driver}', [AdminController::class, 'driverDestroy'])->name('driver.destroy');
    
    // Pemesanan Export & Import
    Route::get('/pemesanan/export', [PemesananController::class, 'export'])->name('pemesanan.export');
    Route::get('/pemesanan/export-page', function() {
        return view('admin.pemesanan.export');
    })->name('pemesanan.export-page');
    Route::post('/pemesanan/import', [PemesananController::class, 'import'])->name('pemesanan.import');
    Route::get('/pemesanan/import-page', function() {
        return view('admin.pemesanan.import');
    })->name('pemesanan.import-page');
    
    // Log Routes
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    
    // Role Management Routes
    Route::resource('/roles', RoleController::class);
    Route::get('/roles-select', [RoleController::class, 'getForSelect'])->name('roles.select');
    
    // User Management Routes
    Route::resource('/users', UserController::class);
    Route::get('/logs/{log}', [LogController::class, 'show'])->name('logs.show');
    
    // User Management Routes
    Route::resource('users', UserController::class);

    // Select2 API routes
    Route::get('/kendaraan-select', [AdminController::class, 'kendaraanSelect'])->name('kendaraan.select');
    Route::get('/driver-select', [AdminController::class, 'driverSelect'])->name('driver.select');
    Route::get('/approver-select', [AdminController::class, 'approverSelect'])->name('approver.select');
});

// Approver Routes
Route::middleware(['auth', 'approver'])->prefix('approver')->name('approver.')->group(function () {
    Route::get('/', [DashboardController::class, 'approverDashboard'])->name('dashboard');
    Route::get('/persetujuan', [ApproverController::class, 'persetujuanIndex'])->name('persetujuan.index');
    Route::get('/persetujuan/{persetujuan}', [ApproverController::class, 'persetujuanShow'])->name('persetujuan.show');
    Route::post('/persetujuan/{persetujuan}/approve', [ApproverController::class, 'persetujuanApprove'])->name('persetujuan.approve');
    Route::post('/persetujuan/{persetujuan}/reject', [ApproverController::class, 'persetujuanReject'])->name('persetujuan.reject');
});

require __DIR__.'/auth.php';
