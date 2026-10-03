@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Verifique sua conta</h1>

                <p>
                    Informe o código enviado para o e-mail abaixo para ativar sua conta.
                </p>

            </div>

            @if (session('success'))
                <x-alert type="success">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if (session('error'))
                <x-alert>
                    {{ session('error') }}
                </x-alert>
            @endif


            {{-- E-mail da conta --}}
            <div class="form-group">

                <label for="email">
                    E-mail
                </label>

                <input type="email" id="email" name="email" form="verification-form" value="{{ $email }}"
                    readonly autocomplete="email">

                @error('email')
                    <x-alert>
                        {{ $message }}
                    </x-alert>
                @enderror

            </div>


            {{-- Formulário de verificação --}}
            <form method="POST" action="{{ route('verification.store') }}" id="verification-form">

                @csrf

                <div class="form-group">

                    <label for="code">
                        Código de verificação
                    </label>

                    <input type="text" id="code" name="code" maxlength="6" inputmode="numeric"
                        autocomplete="one-time-code" placeholder="Digite o código de 6 dígitos" value="{{ old('code') }}">

                    @error('code')
                        <x-alert>
                            {{ $message }}
                        </x-alert>
                    @enderror

                </div>

                <button type="submit">
                    Verificar conta
                </button>

            </form>


            {{-- Formulário de reenvio --}}
            <form method="POST" action="{{ route('verification.resend') }}" id="resend-form">

                @csrf

                <button type="submit" class="btn_resend-code">
                    Reenviar código
                </button>

            </form>

        </section>

    </div>
@endsection
