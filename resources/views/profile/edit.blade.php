@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Editar perfil</h1>

                <p>
                    Atualize o nome e o e-mail da sua conta.
                </p>

            </div>

            @if ($errors->any())
                <x-alert>
                    {{ $errors->first() }}
                </x-alert>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nome</label>

                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        maxlength="255" autocomplete="name">
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>

                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        maxlength="255" autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Confirme sua senha atual</label>

                    <div class="password-container">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar senha">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit">
                    Salvar alterações
                </button>

                <a href="{{ route('profile') }}" class="secondary-button">
                    Cancelar
                </a>

            </form>

        </section>

    </div>
@endsection
