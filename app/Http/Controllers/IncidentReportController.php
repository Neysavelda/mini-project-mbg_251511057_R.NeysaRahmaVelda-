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
    public function store(Request $request)
    {
        $request->validate([
            'meal_distribution_id' => 'required|exists:meal_distributions,id',
            'incident_at'          => 'required|date',
            'affected_count'       => 'required|integer|min:1',
            'description'          => 'required|string',
            'evidence'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'evidence.mimes' => 'Berkas bukti pendukung harus berformat JPG, PNG, atau PDF.',
            'evidence.max'   => 'Ukuran berkas maksimal adalah 2MB.',
        ]);

        // BR-05: Pelapor tidak boleh membuat laporan baru di distribusi yang sama jika laporan sebelumnya belum Selesai
        $existingReport = IncidentReport::where('user_id', auth()->id())
            ->where('meal_distribution_id', $request->meal_distribution_id)
            ->where('status', '!=', IncidentReport::STATUS_RESOLVED)
            ->exists();

        if ($existingReport) {
            return back()->withInput()->withErrors([
                'meal_distribution_id' => 'Anda sudah memiliki laporan aktif untuk jadwal distribusi ini yang sedang diproses.'
            ]);
        }

        if ($existingReport) {
            return back()->withInput()->withErrors([
                'meal_distribution_id' => 'Anda sudah membuat laporan untuk jadwal distribusi ini yang masih dalam proses.'
            ]);
        }

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('evidences', 'local');
        }

        IncidentReport::create([
            'user_id'              => auth()->id(),
            'meal_distribution_id' => $request->meal_distribution_id,
            'incident_at'          => $request->incident_at,
            'affected_count'       => $request->affected_count,
            'description'          => $request->description,
            'evidence_path'        => $evidencePath,
            'status'               => IncidentReport::STATUS_SUBMITTED,
        ]);

        return redirect()->route('dashboard')->with('success', 'Laporan keluhan berhasil dikirim.');
    }