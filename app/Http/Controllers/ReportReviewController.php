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
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'pemantau') {
            $reports = IncidentReport::whereHas('distribution', function ($query) use ($user) {
                $query->where('school_id', $user->school_id);
            })->with(['distribution.school', 'reporter'])->latest()->get();
        } else {
            $reports = IncidentReport::with(['distribution.school', 'reporter'])->latest()->get();
        }

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