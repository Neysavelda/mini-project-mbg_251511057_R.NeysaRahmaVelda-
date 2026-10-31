@extends('layouts.app')
@section('title', 'Kelola Data Sekolah')

@section('content')
<div class="card">
    <h2>Data Sekolah</h2>
    
    <form method="POST" action="{{ route('schools.store') }}" style="margin-bottom: 24px;">
        @csrf
        <div style="display: flex; gap: 10px; margin-top: 10px;">
            <input type="text" name="name" placeholder="Nama Sekolah (misal: SMAN 2 Bandung)" required>
            <input type="text" name="district" placeholder="Kecamatan" required>
            <button type="submit" class="btn" style="margin-top: 0; white-space: nowrap;">+ Tambah Sekolah</button>
        </div>
    </form>

    <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
        <thead>
            <tr style="background: #eee; text-align: left;">
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Nama Sekolah</th>
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Kecamatan</th>
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Total Distribusi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schools as $school)
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px;">{{ $school->name }}</td>
                <td style="padding: 10px;">{{ $school->district }}</td>
                <td style="padding: 10px;"><strong>{{ $school->distributions_count }}</strong> Jadwal</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection