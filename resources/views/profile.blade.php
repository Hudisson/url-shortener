@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Meu perfil</h1>

                <p>
                    Informações da sua conta.
                </p>

            </div>

            <div class="form-group">
                <label>Nome</label>

                <input type="text" value="{{ $user->name }}" disabled>
            </div>

            <div class="form-group">
                <label>E-mail</label>

                <input type="email" value="{{ $user->email }}" disabled>
            </div>

            <div class="form-group">
                <label>Cadastro realizado em</label>

                <input type="text"
                    value="{{ $user->created_at->format('d/m/Y') }} às {{ $user->created_at->format('H:i') }}" disabled>
            </div>

            <div class="form-group">
                <label>Status do e-mail</label>

                <input type="text" value="{{ $user->email_verified_at !== null ? 'Verificado' : 'Não verificado' }}"
                    disabled>
            </div>

        </section>

    </div>
@endsection
