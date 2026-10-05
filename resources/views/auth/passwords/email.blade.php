@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Esqueci minha senha</h1>

                <p>
                    Informe o e-mail da sua conta para receber um link de redefinição de senha.
                </p>

            </div>

            @if ($errors->any())
                <x-alert>
                    {{ $errors->first() }}
                </x-alert>
            @endif

            <form method="POST" action="{{ route('password.email') }}">

                @csrf

                <div class="form-group">
                    <label for="email">E-mail</label>

                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        autocomplete="email" autofocus>
                </div>

                <button type="submit">
                    Enviar link de redefinição
                </button>

                <a href="{{ route('login') }}" class="secondary-button">
                    Voltar para entrar
                </a>

            </form>

        </section>

    </div>
@endsection
