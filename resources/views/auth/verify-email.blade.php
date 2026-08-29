@extends('layouts.app')

@section('content')

<div class="public-page">

    <section class="shortener-card">

        <div class="shortener-header">

            <h1>Verifique sua conta</h1>

            <p>
                Informe o e-mail utilizado no cadastro e o código
                recebido para ativar sua conta.
            </p>

        </div>

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif


        {{-- E-mail da conta --}}
        <div class="form-group">

            <label for="email">
                E-mail
            </label>

            <input
                type="email"
                id="email"
                name="email"
                form="verification-form"
                value="{{ old('email') }}"
                autocomplete="email"
                placeholder="Digite o e-mail utilizado no cadastro"
            >

            @error('email')
                <div class="error">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- Formulário de verificação --}}
        <form
            method="POST"
            action="{{ route('verification.store') }}"
            id="verification-form"
        >

            @csrf

            <div class="form-group">

                <label for="code">
                    Código de verificação
                </label>

                <input
                    type="text"
                    id="code"
                    name="code"
                    maxlength="6"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    placeholder="Digite o código de 6 dígitos"
                    value="{{ old('code') }}"
                >

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


        {{-- Formulário de reenvio --}}
        <form
            method="POST"
            action="{{ route('verification.resend') }}"
            id="resend-form"
        >

            @csrf

            <input
                type="hidden"
                name="email"
                id="resend-email"
            >

            <button type="submit" class="btn_resend-code">
                Reenviar código
            </button>

        </form>

    </section>

</div>

<script>
    const emailInput = document.getElementById('email');
    const resendEmail = document.getElementById('resend-email');

    document.getElementById('resend-form').addEventListener('submit', function () {
        resendEmail.value = emailInput.value;
    });
</script>

@endsection
