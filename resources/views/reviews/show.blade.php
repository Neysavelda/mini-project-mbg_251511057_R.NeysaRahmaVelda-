@extends('layouts.app')
@section('title', 'Detail Peninjauan Laporan')

@section('content')
<div class="card">
    <h2>Detail Laporan Keluhan #{{ $report->id }}</h2>
    <p><strong>Sekolah:</strong> {{ $report->distribution->school->name }} ({{ $report->distribution->school->district }})</p>
    <p><strong>Menu Makanan:</strong> {{ $report->distribution->menu_description }}</p>
    <p><strong>Pelapor:</strong> {{ $report->reporter->name }}</p>
    <p><strong>Waktu Kejadian:</strong> {{ $report->incident_at->format('d-m-Y H:i') }}</p>
    <p><strong>Jumlah Korban:</strong> {{ $report->affected_count }} orang</p>
    <p><strong>Status Saat Ini:</strong> <span style="background: #e2e3e5; padding: 4px 8px; border-radius: 4px;">{{ $report->status }}</span></p>
    
    <hr style="margin: 20px 0;">
    <h3>Deskripsi Kejadian:</h3>
    <p style="background: #f8f9fa; padding: 12px; border-radius: 4px;">{{ $report->description }}</p>

    @if($report->evidence_path)
        <p><strong>Bukti Upload:</strong> 
            <a href="{{ route('reports.evidence', $report->id) }}" target="_blank" class="btn" style="padding: 4px 10px; text-decoration: none;">
                Lihat File Bukti (Secure)
            </a>
        </p>
    @endif

    <hr style="margin: 20px 0;">
    <h3>Riwayat Catatan Peninjauan:</h3>
    @forelse($report->notes as $note)
        <div style="background: #fff3cd; padding: 10px; border-radius: 4px; margin-bottom: 10px;">
            <small><strong>{{ $note->user->name }}</strong> ({{ $note->created_at->format('d-m-Y H:i') }}):</small>
            <p style="margin: 4px 0 0 0;">{{ $note->note }}</p>
        </div>
    @empty
        <p style="color: #777;">Belum ada catatan.</p>
    @endforelse

    @if(auth()->user()->role === 'pemantau' || auth()->user()->role === 'koordinator')
    <hr style="margin: 20px 0;">
    <h3>Beri Catatan & Update Status</h3>
    <form method="POST" action="{{ route('reviews.note', $report->id) }}">
        @csrf
        <div style="margin-bottom: 12px;">
            <label style="display: block; font-weight: bold;">Catatan Peninjau</label>
            <textarea name="note" rows="3" required style="width: 100%; padding: 8px;"></textarea>
        </div>
        <div style="margin-bottom: 12px;">
            <label style="display: block; font-weight: bold;">Ubah Status Laporan Ke:</label>
            <select name="status" required style="width: 100%; padding: 8px;">
                <option value="under_review">Sedang Ditinjau</option>
                <option value="needs_info">Perlu Dilengkapi</option>
                <option value="forwarded">Ditandai untuk Diteruskan</option>
                <option value="resolved">Selesai</option>
            </select>
        </div>
        <button type="submit" class="btn">Simpan Peninjauan</button>
    </form>
    @endif
</div>
@endsection