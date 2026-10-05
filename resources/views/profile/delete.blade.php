@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Excluir conta</h1>

                <p>
                    Para confirmar a exclusão da sua conta, informe sua senha atual. Todas as suas URLs encurtadas serão
                    desativadas.
                </p>

            </div>

            @if ($errors->any())
                <x-alert>
                    {{ $errors->first() }}
                </x-alert>
            @endif

            <form method="POST" action="{{ route('profile.destroy') }}">

                @csrf
                @method('DELETE')

                <div class="form-group">
                    <label for="password">Senha atual</label>

                    <div class="password-container">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar senha">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="profile-delete-button">
                    Confirmar exclusão da conta
                </button>

                <a href="{{ route('profile') }}" class="secondary-button">
                    Cancelar
                </a>

            </form>

        </section>

    </div>
@endsection
