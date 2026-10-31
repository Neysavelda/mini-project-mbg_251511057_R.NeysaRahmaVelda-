<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use App\Models\ReportNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportReviewController extends Controller
{
    /**
     * Menampilkan daftar laporan masuk (Pemantau hanya melihat sekolahnya)
     */
    public function index(Request $request)
    {
        $query = IncidentReport::with(['distribution.school', 'reporter']);

        // Pembatasan sekolah untuk Pemantau (BR-03 / AC-02)
        if (auth()->user()->role === 'pemantau') {
            $schoolId = auth()->user()->school_id;
            $query->whereHas('distribution', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });
        }

        // AC-10: Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // AC-10: Search berdasarkan deskripsi / pelapor
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                ->orWhereHas('reporter', function ($qReporter) use ($search) {
                    $qReporter->where('name', 'like', "%{$search}%");
                });
            });
        }

        // AC-11: Pagination 10 data per halaman
        $reports = $query->latest()->paginate(10)->withQueryString();

        return view('reviews.index', compact('reports'));
    }

    /**
     * Menampilkan detail peninjauan laporan
     */
    public function show(IncidentReport $report)
    {
        $user = Auth::user();

        if ($user->role === 'pemantau' && $report->distribution->school_id !== $user->school_id) {
            abort(403, 'Anda tidak berhak meninjau laporan dari sekolah ini.');
        }

        $report->load(['distribution.school', 'reporter', 'notes.user']);

        return view('reviews.show', compact('report'));
    }

    /**
     * Menyimpan catatan peninjauan & update status laporan (BR-03)
     */
    public function storeNote(Request $request, IncidentReport $report): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role === 'pemantau' && $report->distribution->school_id !== $user->school_id) {
            abort(403, 'Anda tidak berhak mengubah laporan sekolah ini.');
        }

        $validated = $request->validate([
            'note' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        ReportNote::create([
            'incident_report_id' => $report->id,
            'user_id' => $user->id,
            'note' => $validated['note'],
        ]);

        $report->update(['status' => $validated['status']]);

        return back()->with('status', 'Catatan dan status peninjauan berhasil disimpan.');
    }
}