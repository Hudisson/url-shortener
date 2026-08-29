@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Entrar</h1>

                <p>
                    Entre na sua conta para acessar suas URLs.
                </p>

            </div>

            @if (session('success'))
                <div class="success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">

                @csrf

                <div class="form-group">

                    <label for="email">
                        E-mail
                    </label>

                    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email"
                        placeholder="Digite seu e-mail">

                    @error('email')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="password">
                        Senha
                    </label>

                    <div class="password-container">
                        <input type="password" id="password" name="password" autocomplete="current-password"
                            placeholder="Digite sua senha">
                        <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar senha">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    @error('password')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <button type="submit">
                    Entrar
                </button>

            </form>

        </section>

    </div>
@endsection
