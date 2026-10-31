@extends('layouts.app')
@section('title', 'Ringkasan Laporan MBG')

@section('content')
<div class="card">
    <h2>Ringkasan & Statistik Laporan Keluhan MBG</h2>
    <p style="color: #666;">Panel Evaluasi dan Pemantauan Keseluruhan untuk Koordinator Komunitas.</p>

    <!-- Stat Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin: 20px 0;">
        <div style="background: #e2e8f0; padding: 16px; border-radius: 8px; text-align: center;">
            <h3 style="margin: 0; font-size: 1.8rem; color: #1a365d;">{{ $stats['total'] }}</h3>
            <span style="font-size: 0.9rem; color: #4a5568;">Total Laporan</span>
        </div>
        <div style="background: #feebc8; padding: 16px; border-radius: 8px; text-align: center;">
            <h3 style="margin: 0; font-size: 1.8rem; color: #744210;">{{ $stats['submitted'] }}</h3>
            <span style="font-size: 0.9rem; color: #744210;">Baru (Submitted)</span>
        </div>
        <div style="background: #bee3f8; padding: 16px; border-radius: 8px; text-align: center;">
            <h3 style="margin: 0; font-size: 1.8rem; color: #2b6cb0;">{{ $stats['under_review'] }}</h3>
            <span style="font-size: 0.9rem; color: #2b6cb0;">Sedang Ditinjau</span>
        </div>
        <div style="background: #e9d8fd; padding: 16px; border-radius: 8px; text-align: center;">
            <h3 style="margin: 0; font-size: 1.8rem; color: #553c9a;">{{ $stats['forwarded'] }}</h3>
            <span style="font-size: 0.9rem; color: #553c9a;">Diteruskan</span>
        </div>
        <div style="background: #c6f6d5; padding: 16px; border-radius: 8px; text-align: center;">
            <h3 style="margin: 0; font-size: 1.8rem; color: #22543d;">{{ $stats['resolved'] }}</h3>
            <span style="font-size: 0.9rem; color: #22543d;">Selesai</span>
        </div>
    </div>

    <hr style="margin: 30px 0;">

    <!-- Rekapitulasi per Sekolah -->
    <h3>Rekapitulasi Kejadian per Sekolah</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 12px;">
        <thead>
            <tr style="background: #edf2f7; text-align: left;">
                <th style="padding: 10px;">Nama Sekolah</th>
                <th style="padding: 10px;">Kecamatan</th>
                <th style="padding: 10px;">Jumlah Kasus Laporan</th>
                <th style="padding: 10px;">Total Korban Terdampak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schoolSummaries as $school)
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 10px;"><strong>{{ $school->name }}</strong></td>
                <td style="padding: 10px;">{{ $school->district }}</td>
                <td style="padding: 10px;">{{ $school->reports_count }} laporan</td>
                <td style="padding: 10px;">{{ $school->reports_sum_affected_count ?? 0 }} orang</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 12px; text-align: center;">Belum ada data sekolah.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection