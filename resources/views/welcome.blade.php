<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ExpenseEye</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-image: url('{{ asset('img/welcome.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            margin: 0;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .navigation {
            display: flex;
            justify-content: flex-end;
            padding: 1rem;
        }
        .navigation a {
            margin-left: 1rem;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid white;
            border-radius: 0.35rem;
            transition: background-color 0.3s;
        }
        .navigation a:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }
        .app-name {
            text-align: center;
            font-size: 3rem;
            font-weight: 600;
            margin-top: 20%;
        }
    </style>
</head>
<body>
    <div class="navigation">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        @endif
    </div>

    <div class="app-name">
        ExpenseEye
    </div>
</body>
</html>
