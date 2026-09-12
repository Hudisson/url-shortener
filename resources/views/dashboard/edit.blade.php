@extends('layouts.app')

@section('content')
    <div class="dashboard">

        <div class="dashboard-header">

            <h1>
                Editar URL
            </h1>

            <p>
                Atualize as informações da sua URL encurtada.
            </p>

        </div>

        <section class="shortener-card">

            <form method="POST">

                @csrf
                @method('PUT')

                {{-- URL original --}}
                <div class="form-group">

                    <label for="url">
                        URL original
                    </label>

                    <input type="url" id="url" name="url" placeholder="https://exemplo.com/projeto"
                        value="{{ old('url', $shortUrl->original_url) }}" required>

                </div>

                {{-- Código personalizado --}}
                @if ($shortUrl->type === 'custom')
                    <div class="form-group">

                        <label for="short_code">
                            Código personalizado
                        </label>

                        <input type="text" id="short_code" name="short_code" placeholder="meu-projeto"
                            value="{{ old('short_code', $shortUrl->short_code) }}" minlength="3" maxlength="50">

                    </div>
                @else
                    <div class="form-group">

                        <label for="short_code">
                            Código da URL
                        </label>

                        <input type="text" id="short_code" value="{{ $shortUrl->short_code }}" readonly>

                        <small>
                            O código de uma URL gerada automaticamente não pode ser alterado.
                        </small>

                    </div>
                @endif

                {{-- Etiqueta --}}
                <div class="form-group">

                    <label for="label">
                        Etiqueta
                    </label>

                    <input type="text" id="label" name="label" placeholder="Projeto pessoal"
                        value="{{ old('label', $shortUrl->label) }}" maxlength="255">

                </div>

                {{-- Ações --}}
                <div class="form-actions">

                    <button type="submit">
                        Salvar alterações
                    </button>

                    <a href="{{ route('dashboard.urls') }}" class="button">
                        Cancelar
                    </a>

                </div>

            </form>

        </section>

    </div>
@endsection
