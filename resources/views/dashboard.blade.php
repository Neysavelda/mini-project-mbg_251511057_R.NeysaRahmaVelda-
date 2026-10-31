@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="card">
    <h2>Selamat Datang, {{ auth()->user()->name }}</h2>
    <p>Peran Anda: <strong>{{ auth()->user()->roleLabel() }}</strong></p>

    @if (session('status'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-top: 15px;">
            {{ session('status') }}
        </div>
    @endif

    @if (auth()->user()->role === \App\Models\User::ROLE_PELAPOR)
        <div style="margin-top: 24px;">
            <a href="{{ route('reports.create') }}" class="btn" style="text-decoration: none; display: inline-block;">
                + Buat Laporan Keluhan MBG
            </a>
        </div>
    @endif
</div>
@endsection