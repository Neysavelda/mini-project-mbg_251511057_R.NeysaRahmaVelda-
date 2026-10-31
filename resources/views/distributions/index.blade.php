@extends('layouts.app')
@section('title', 'Kelola Distribusi Makanan')

@section('content')
<div class="card">
    <h2>Jadwal & Menu Distribusi Makanan</h2>
    
    <form method="POST" action="{{ route('distributions.store') }}" style="margin-bottom: 24px;">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px;">
            <div>
                <label>Sekolah</label>
                <select name="school_id" required>
                    <option value="">-- Pilih Sekolah --</option>
                    @foreach($schools as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Tanggal Distribusi</label>
                <input type="date" name="distribution_date" value="{{ date('Y-m-d') }}" required>
            </div>
            <div style="grid-column: span 2;">
                <label>Deskripsi Menu Makanan</label>
                <input type="text" name="menu_description" placeholder="Menu (contoh: Nasi, Ayam Goreng, Sop, Buah)" required>
            </div>
            <div>
                <label>Jumlah Porsi</label>
                <input type="number" name="portion_count" value="150" min="1" required>
            </div>
            <div style="align-self: end;">
                <button type="submit" class="btn" style="width: 100%;">+ Tambah Distribusi</button>
            </div>
        </div>
    </form>

    <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
        <thead>
            <tr style="background: #eee; text-align: left;">
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Tanggal</th>
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Sekolah</th>
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Menu Makanan</th>
                <th style="padding: 10px; border-bottom: 2px solid #ccc;">Porsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($distributions as $d)
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px;">{{ $d->distribution_date->format('d-m-Y') }}</td>
                <td style="padding: 10px;">{{ $d->school->name }}</td>
                <td style="padding: 10px;">{{ $d->menu_description }}</td>
                <td style="padding: 10px;">{{ $d->portion_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection