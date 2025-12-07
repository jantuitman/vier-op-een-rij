<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vier op een Rij - Connect Four')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        <header>
            <h1>Vier op een Rij</h1>
            <nav>
                <a href="{{ route('game.index') }}" class="nav-link {{ request()->routeIs('game.index') ? 'active' : '' }}">
                    Game
                </a>
                <a href="{{ route('scores.index') }}" class="nav-link {{ request()->routeIs('scores.index') ? 'active' : '' }}">
                    High Scores
                </a>
            </nav>
        </header>

        <main>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer>
            <p>&copy; 2025 Vier op een Rij - Connect Four Game</p>
        </footer>
    </div>
</body>
</html>
