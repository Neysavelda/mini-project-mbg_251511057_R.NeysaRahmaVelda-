<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\MealDistribution;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentReportController extends Controller
{
    /**
     * Menampilkan form pembuatan laporan baru
     */
    public function create()
    {
        $schools = School::all();
        return view('reports.create', compact('schools'));
    }

    /**
     * Endpoint AJAX Fetch API untuk mengambil data distribusi berdasarkan sekolah (AC-09)
     */
    public function getDistributions(School $school): JsonResponse
    {
        $distributions = $school->distributions()
            ->latest('distribution_date')
            ->get(['id', 'distribution_date', 'menu_description']);

        return response()->json($distributions);
    }

    /**
     * Menyimpan laporan baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input form (AC-04: bukti opsional, max 2MB, format jpg/jpeg/png/pdf)
        $validated = $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'meal_distribution_id' => ['required', 'exists:meal_distributions,id'],
            'incident_at' => ['required', 'date'],
            'affected_count' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // Validasi Relasi Server-Side (AC-03): Memastikan distribusi milik sekolah yang dipilih
        $distribution = MealDistribution::findOrFail($validated['meal_distribution_id']);
        if ($distribution->school_id != $validated['school_id']) {
            return back()
                ->withErrors(['meal_distribution_id' => 'Jadwal distribusi tidak sesuai dengan sekolah yang dipilih.'])
                ->withInput();
        }

        // Penanganan Unggah Bukti ke Private Storage (Fase 5 / AC-05)
        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('evidences');
        }

        // Simpan Data Laporan dengan Status Awal "Dikirim"
        $report = new IncidentReport();
        $report->user_id = Auth::id();
        $report->meal_distribution_id = $validated['meal_distribution_id'];
        $report->incident_at = $validated['incident_at'];
        $report->affected_count = $validated['affected_count'];
        $report->description = $validated['description'];
        $report->evidence_path = $evidencePath;
        $report->status = IncidentReport::STATUS_SUBMITTED;
        $report->save();

        return redirect()->route('dashboard')->with('status', 'Laporan keluhan berhasil dikirim!');
    }
}