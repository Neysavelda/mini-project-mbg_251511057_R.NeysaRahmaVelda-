<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    /**
     * Menampilkan/mengunduh file bukti dengan proteksi hak akses (AC-05)
     */
    public function show(IncidentReport $report)
    {
        $user = Auth::user();

        // 1. Cek Otorisasi Hak Akses TERLEBIH DAHULU (AC-05)
        $isOwner = $report->user_id === $user->id;
        $isCoordinator = $user->role === 'koordinator';
        
        // Memastikan relasi distribution terdistribusi dengan benar untuk Pemantau
        $isMonitorOfSchool = false;
        if ($user->role === 'pemantau' && $user->school_id) {
            $isMonitorOfSchool = $report->distribution && $report->distribution->school_id === $user->school_id;
        }

        if (!$isOwner && !$isCoordinator && !$isMonitorOfSchool) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat file bukti ini.');
        }

        // 2. Cek Keberadaan File via Storage Facade
        if (!$report->evidence_path || !Storage::exists($report->evidence_path)) {
            abort(404, 'File bukti tidak ditemukan di private storage.');
        }

        // 3. Ambil File Private Menggunakan Response Download/File milik Storage Driver
        return response()->file(Storage::path($report->evidence_path));
    }
}