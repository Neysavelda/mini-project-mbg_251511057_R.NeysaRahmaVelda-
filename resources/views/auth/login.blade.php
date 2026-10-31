@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="card narrow">
    <h1>Login</h1>
    <p class="muted">Masuk untuk membuat atau memantau laporan.</p>

    @error('username')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="username">Username</label>
        <input id="username" name="username" type="text" value="{{ old('username') }}" autofocus required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <button type="submit" class="btn">Masuk</button>
    </form>
</div>
@endsection