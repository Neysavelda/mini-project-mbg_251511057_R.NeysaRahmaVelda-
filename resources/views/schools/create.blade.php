@extends('layouts.app')
@section('title', 'Tambah Sekolah')
@section('content')
<div class="card narrow">
    <h1>Tambah Sekolah</h1>
    <form method="POST" action="{{ route('schools.store') }}">
        @csrf
        <label>Nama Sekolah</label>
        <input type="text" name="name" required>
        <label>Kecamatan</label>
        <input type="text" name="district" required>
        <button type="submit" class="btn">Simpan</button>
    </form>
</div>
@endsection