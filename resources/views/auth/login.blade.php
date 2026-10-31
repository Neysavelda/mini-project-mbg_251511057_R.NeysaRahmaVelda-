@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="card narrow">
    <h2 style="margin-top: 0;">Login</h2>
    <p style="color: #666; margin-bottom: 20px;">Masuk untuk membuat atau memantau laporan.</p>

    @if ($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 16px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-block" style="margin-top: 10px;">Masuk</button>
    </form>
</div>
@endsection