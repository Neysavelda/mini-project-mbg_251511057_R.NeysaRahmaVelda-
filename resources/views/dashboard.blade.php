@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <h1>Halo, {{ auth()->user()->name }}</h1>
    <p>Peran Anda: <strong>{{ auth()->user()->roleLabel() }}</strong></p>
    <p class="muted">Menu untuk peran ini ditambahkan pada fase berikutnya.</p>
</div>
@endsection