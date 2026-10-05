@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Redefinir senha</h1>

                <p>
                    Escolha uma nova senha para sua conta.
                </p>

            </div>

            @if ($errors->any())
                <x-alert>
                    {{ $errors->first() }}
                </x-alert>
            @endif

            <form method="POST" action="{{ route('password.update') }}">

                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">E-mail</label>

                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required
                        autocomplete="email" readonly>
                </div>

                <div class="form-group">
                    <label for="password">Nova senha</label>

                    <div class="password-container">
                        <input type="password" id="password" name="password" required minlength="8"
                            autocomplete="new-password">
                        <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar senha">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirme a nova senha</label>

                    <div class="password-container">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            minlength="8" autocomplete="new-password">
                        <button type="button" class="password-toggle" data-target="password_confirmation"
                            aria-label="Mostrar senha">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit">
                    Redefinir senha
                </button>

            </form>

        </section>

    </div>
@endsection
