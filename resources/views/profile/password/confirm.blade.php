@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Confirme a redefinição</h1>

                <p>
                    Informe o código enviado para o seu e-mail. O código é válido por 15 minutos.
                </p>

            </div>

            {{-- Mensagem de sucesso --}}
            @if (session('success'))
                <x-alert type="success">
                    {{ session('success') }}
                </x-alert>
            @endif

            {{-- Mensagem de erro --}}
            @if ($errors->any())
                <x-alert>
                    {{ $errors->first() }}
                </x-alert>
            @endif

            {{-- Formulário de confirmação --}}
            <form method="POST" action="{{ route('profile.password.verify') }}">

                @csrf

                <div class="form-group">
                    <label for="code">Código de confirmação</label>

                    <input type="text" id="code" name="code" value="{{ old('code') }}" required maxlength="6"
                        minlength="6" inputmode="numeric" pattern="[0-9]{6}" autocomplete="one-time-code">
                </div>

                <button type="submit">
                    Confirmar e redefinir senha
                </button>

            </form>

            <a href="{{ route('profile.password.edit') }}" class="secondary-button">
                Solicitar outro código
            </a>

        </section>

    </div>
@endsection
