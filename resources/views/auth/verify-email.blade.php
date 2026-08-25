@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Verifique sua conta</h1>

                <p>
                    Enviamos um código de verificação para o seu endereço de e-mail.
                    Informe o código abaixo para ativar sua conta.
                </p>

            </div>

            @if (session('success'))
                <div class="success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.store') }}">

                @csrf

                <div class="form-group">

                    <label for="code">
                        Código de verificação
                    </label>

                    <input type="text" id="code" name="code" maxlength="6" inputmode="numeric"
                        autocomplete="one-time-code" placeholder="Digite o código de 6 dígitos" value="{{ old('code') }}">

                    @error('code')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <button type="submit">
                    Verificar conta
                </button>

            </form>

        </section>

    </div>
@endsection
