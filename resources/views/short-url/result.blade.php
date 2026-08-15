@extends('layouts.app')

@section('content')
    <div class="public-page">

        <x-ad-placeholder />

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>URL criada com sucesso!</h1>

                <p>
                    Sua URL curta está pronta para ser utilizada.
                </p>

            </div>

            <div class="result">

                <label for="short-url">Sua URL curta</label>

                <div class="copy-container">

                    <input type="text" id="short-url" value="{{ url($shortUrl->short_code) }}" readonly>

                    <button type="button" id="copy-button" class="copy-button">
                        Copiar URL
                    </button>

                </div>

            </div>

            <a href="{{ url($shortUrl->short_code) }}" class="button">
                Acessar URL
            </a>

            <a href="{{ url('/') }}" class="secondary-button">
                Encurtar outra URL
            </a>

        </section>

        <div class="public-description">

            <h2>Compartilhe seu link</h2>

            <p>
                Sua URL curta já está disponível.
                Você pode copiá-la e compartilhá-la onde quiser.
            </p>

        </div>

        <x-ad-placeholder />

    </div>
@endsection
