<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CoordinatorSummaryController extends Controller
{
    /**
     * Menampilkan dashboard ringkasan & statistik laporan khusus Koordinator
     */
    public function index()
    {
        // Proteksi Otorisasi Gate (AC-01 & BR-01)
        if (Gate::denies('manage-master-data')) {
            abort(403, 'Hanya Koordinator yang berhak mengakses halaman ringkasan ini.');
        }

        // 1. Hitung Statistik Total per Status
        $stats = [
            'total'        => IncidentReport::count(),
            'submitted'    => IncidentReport::where('status', IncidentReport::STATUS_SUBMITTED)->count(),
            'under_review' => IncidentReport::where('status', 'under_review')->count(),
            'forwarded'    => IncidentReport::where('status', 'forwarded')->count(),
            'resolved'     => IncidentReport::where('status', 'resolved')->count(),
        ];

        // 2. Rekapitulasi Laporan per Sekolah
        $schoolSummaries = School::withCount('reports')
            ->withSum('reports', 'affected_count')
            ->get();

        // 3. Daftar Laporan Terbaru
        $latestReports = IncidentReport::with(['distribution.school', 'reporter'])
            ->latest()
            ->take(10)
            ->get();

        return view('coordinator.summary', compact('stats', 'schoolSummaries', 'latestReports'));
    }
}