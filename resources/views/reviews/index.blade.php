@extends('layouts.app')
@section('title', 'Daftar Laporan Keluhan')

@section('content')
<div class="card">
    <h2>Daftar Laporan Keluhan Masuk</h2>
    <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
        <thead>
            <tr style="background: #eee; text-align: left;">
                <th style="padding: 10px;">Tanggal Keluhan</th>
                <th style="padding: 10px;">Sekolah</th>
                <th style="padding: 10px;">Pelapor</th>
                <th style="padding: 10px;">Jumlah Korban</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $r)
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px;">{{ $r->incident_at->format('d-m-Y H:i') }}</td>
                <td style="padding: 10px;">{{ $r->distribution->school->name }}</td>
                <td style="padding: 10px;">{{ $r->reporter->name }}</td>
                <td style="padding: 10px;">{{ $r->affected_count }} orang</td>
                <td style="padding: 10px;"><strong>{{ $r->status }}</strong></td>
                <td style="padding: 10px;">
                    <a href="{{ route('reviews.show', $r->id) }}" class="btn" style="padding: 4px 10px; text-decoration: none;">Tinjau Laporan</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 16px; text-align: center;">Belum ada laporan masuk.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection