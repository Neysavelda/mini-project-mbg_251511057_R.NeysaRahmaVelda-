<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Lapor MBG')</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; margin: 0; background: #f4f6f9; color: #333; }
        .navbar { background: #1a365d; color: white; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; }
        .container { max-width: 900px; margin: 30px auto; padding: 0 16px; }
        .card { background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card.narrow { max-width: 420px; margin: 40px auto; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .btn { background: #1a365d; color: white; border: none; padding: 10px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; font-weight: bold; }
        .btn:hover { background: #2b6cb0; }
        .btn-block { width: 100%; text-align: center; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div>
            <a href="{{ route('dashboard') }}" style="font-size: 1.2rem;">Lapor MBG</a>
        </div>
        <div>
            @auth
                @if(auth()->user()->role === 'koordinator')
                    <a href="{{ route('coordinator.summary') }}" style="margin-right: 16px;">Ringkasan Laporan</a>
                @endif
                @if(auth()->user()->role === 'pemantau' || auth()->user()->role === 'koordinator')
                    <a href="{{ route('reviews.index') }}" style="margin-right: 16px;">Daftar Laporan</a>
                @endif
                <span style="margin-right: 12px;">{{ auth()->user()->name }} ({{ auth()->user()->roleLabel() }})</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: white; color: #1a365d; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>