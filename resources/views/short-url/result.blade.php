@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="card">

            <h1>URL criada com sucesso!</h1>

            <p class="description">
                Sua URL curta está pronta.
            </p>

            <div class="result">
                <label for="short-url">
                    URL curta
                </label>

                <input type="text" id="short-url" value="{{ url($shortUrl->short_code) }}" readonly>
            </div>

            <a href="{{ url($shortUrl->short_code) }}" class="button">
                Acessar URL
            </a>

            <a href="{{ url('/') }}" class="secondary-button">
                Encurtar outra URL
            </a>

        </section>
    </div>
@endsection
