@extends('layouts.app')
@section('title', 'Tambah Distribusi')
@section('content')
<div class="card narrow">
    <h1>Tambah Distribusi Makanan</h1>
    <form method="POST" action="{{ route('distributions.store') }}">
        @csrf
        <label>Sekolah</label>
        <select name="school_id" required>
            <option value="">-- Pilih Sekolah --</option>
            @foreach($schools as$school)
                <option value="{{ $school->id }}">{{ $school->name }}</option>
            @endforeach
        </select>
        <label>Tanggal Distribusi</label>
        <input type="date" name="distribution_date" value="{{ date('Y-m-d') }}" required>
        <label>Menu Makanan</label>
        <textarea name="menu_description" required></textarea>
        <label>Jumlah Porsi</label>
        <input type="number" name="portion_count" value="150" required>
        <button type="submit" class="btn">Simpan</button>
    </form>
</div>
@endsection