<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\MealDistributionController;
use App\Http\Controllers\ReportReviewController;
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

    // Form Pelaporan Keluhan & AJAX Fetch (Pelapor)
    Route::get('/reports/create', [IncidentReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [IncidentReportController::class, 'store'])->name('reports.store');
    Route::get('/api/schools/{school}/distributions', [IncidentReportController::class, 'getDistributions']);

    // Secure Private Evidence (Fase 5 / AC-05)
    Route::get('/reports/{report}/evidence', [EvidenceController::class, 'show'])->name('reports.evidence');

    // Peninjauan Laporan (Fase 6 - Pemantau & Koordinator)
    Route::get('/reviews', [ReportReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{report}', [ReportReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{report}/note', [ReportReviewController::class, 'storeNote'])->name('reviews.note');
    Route::get('/summary', [CoordinatorSummaryController::class, 'index'])->name('coordinator.summary');
});