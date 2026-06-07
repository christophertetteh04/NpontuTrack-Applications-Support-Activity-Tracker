<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    Route::resource('activities', ActivityController::class)->except(['show']);

    Route::post('activities/{activity}/log', [ActivityLogController::class, 'store'])->name('activity.log.store');
    Route::get('activities/{activity}/history', [ActivityLogController::class, 'history'])->name('activity.log.history');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::resource('users', UserController::class)->except(['show', 'destroy']);
});
