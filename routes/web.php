<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Authentication ────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Protected Routes ──────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard (Req 4: daily handover view)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // Activity definitions (Req 1: admin/lead management)
    Route::resource('activities', ActivityController::class)->except(['show']);

    // Activity status updates (Req 2 & 3)
    Route::post('activities/{activity}/log', [ActivityLogController::class, 'store'])->name('activity.log.store');
    Route::get('activities/{activity}/history', [ActivityLogController::class, 'history'])->name('activity.log.history');

    // Reports (Req 5)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // User management (Req 6 - admin only)
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
});
