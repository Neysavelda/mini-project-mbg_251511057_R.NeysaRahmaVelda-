<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\MealDistributionController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // CRUD Master Data (Koordinator)
    Route::resource('schools', SchoolController::class)->only(['index', 'store']);
    Route::resource('distributions', MealDistributionController::class)->only(['index', 'store']);

    // Form Pelaporan Keluhan & Endpoint AJAX Fetch Distribusi (Pelapor)
    Route::get('/reports/create', [IncidentReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [IncidentReportController::class, 'store'])->name('reports.store');
    Route::get('/api/schools/{school}/distributions', [IncidentReportController::class, 'getDistributions']);
});