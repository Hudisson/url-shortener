<!DOCTYPE html>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'URL Shortener' }}</title>

    @vite('resources/css/app.css')

</head>

<body>

    <header class="header">
        <div class="header-content">
            <a href="{{ url('/') }}" class="logo">
                URL Shortener
            </a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <p>
            &copy; {{ date('Y') }} URL Shortener.
        </p>
    </footer>

</body>

</html>
