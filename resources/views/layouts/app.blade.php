<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'URL Shortener' }}</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/dropdown-header.js',
        'resources/js/delete-modal.js'
        ])

</head>

<body>

    <header class="header">

        <div class="header-content">

            <a href="{{ url('/') }}" class="logo">
                URL Shortener
            </a>

            {{-- menu de navegação - vistantes --}}
            <nav class="header-nav">

                <a href="{{ url('/') }}">
                    Encurtar URL
                </a>

                @guest

                    <a href="{{ route('login') }}">
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
                @else
                    {{-- menu de navegação - usuário autenticado --}}
                    <div class="account-menu">

                        <button type="button" class="account-button" aria-expanded="false" aria-haspopup="true">
                            Conta
                            <i class="fa-solid fa-caret-down"></i>
                        </button>

                        <div class="account-dropdown">

                            <a href="{{ route('dashboard') }}">
                                Dashboard
                            </a>

                            <a href="{{ route('profile') }}">
                                Perfil
                            </a>

                            <hr class="linha-horizontal-meu-auth">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit">
                                    Sair
                                </button>
                            </form>

                        </div>

                    </div>

                    <a href="{{ route('about') }}">
                        Info
                    </a>

                @endguest

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
