<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\IncidentReport;
use App\Observers\IncidentReportObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate untuk Koordinator (Access Master Data & Summary)
        Gate::define('manage-master-data', function (User $user) {
            return $user->role === 'koordinator';
            IncidentReport::observe(IncidentReportObserver::class);
        });
    }
}