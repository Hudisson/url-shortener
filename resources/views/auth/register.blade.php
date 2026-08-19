@extends('layouts.app')

@section('content')
    <div class="public-page">

        <x-ad-placeholder />

        <section class="shortener-card auth-card">

            <div class="shortener-header">

                <h1>Crie sua conta</h1>

                <p>
                    Cadastre-se gratuitamente para gerenciar
                    suas URLs encurtadas.
                </p>

            </div>

            @if (session('success'))
                <div class="success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" novalidate>

                @csrf

                <div class="form-group">

                    <label for="name">
                        Nome
                    </label>

                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        autocomplete="name">

                </div>

                <div class="form-group">

                    <label for="email">
                        E-mail
                    </label>

                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        autocomplete="email">

                </div>

                <div class="form-group">

                    <label for="password">
                        Senha
                    </label>

                    <div class="password-container">
                        <input type="password" id="password" name="password" required autocomplete="new-password">
                        <button type="button" class="password-toggle" data-target="password" aria-label="Mostrar senha">
                            &#128065;
                        </button>
                    </div>


                </div>

                <div class="form-group">

                    <label for="password_confirmation">
                        Confirmar senha
                    </label>

                    <div class="password-container">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            autocomplete="new-password">

                        <button type="button" class="password-toggle" data-target="password_confirmation"
                            aria-label="Mostrar senha">
                            &#128065;
                        </button>
                    </div>


                </div>

                <button type="submit">
                    Criar conta
                </button>

            </form>

        </section>

        <x-ad-placeholder />

    </div>
@endsection
