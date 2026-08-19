<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'URL Shortener' }}</title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

    <header class="header">
        <div class="header-content">
            <a href="{{ url('/') }}" class="logo">
                URL Shortener
            </a>

            <nav class="header-nav">
                <a href="{{ url('/') }}">
                    Encurtar URL
                </a>

                <a href="#">
                    Entrar
                </a>

                <a href="{{ route('register') }}" class="link-de-cadastro">
                    Cadastre-se grátis
                </a>

                <a href="{{ route('about') }}">
                    Sobre
                </a>

                <a href="#">
                    Contato
                </a>

            </nav>

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
