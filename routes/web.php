<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealDistributionController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    // Route dashboard ini yang tadinya belum terdaftar / belum ada ->name('dashboard')
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route Master Data Koordinator (Fase 3)
    Route::resource('schools', SchoolController::class)->only(['index', 'store']);
    Route::resource('distributions', MealDistributionController::class)->only(['index', 'store']);
});