@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Redefinir senha</h1>

                <p>
                    Informe sua nova senha. Enviaremos um código para confirmar a alteração.
                </p>

            </div>

            {{-- Mensagem de erro --}}
            @if ($errors->any())
                <x-alert>
                    {{ $errors->first() }}
                </x-alert>
            @endif

            {{-- Formulário de envio de código --}}
            <form method="POST" action="{{ route('profile.password.send-code') }}">

                @csrf

                <div class="form-group">
                    <label for="new_password">Nova senha</label>

                    <div class="password-container">
                        <input type="password" id="new_password" name="new_password" required minlength="8"
                            autocomplete="new-password">
                        <button type="button" class="password-toggle" data-target="new_password"
                            aria-label="Mostrar senha">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirme a nova senha</label>

                    <div class="password-container">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                            minlength="8" autocomplete="new-password">
                        <button type="button" class="password-toggle" data-target="new_password_confirmation"
                            aria-label="Mostrar senha">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit">
                    Enviar código de confirmação
                </button>

                <a href="{{ route('profile') }}" class="secondary-button">
                    Cancelar
                </a>

            </form>

        </section>

    </div>
@endsection
