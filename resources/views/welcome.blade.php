@extends('layouts.app')

@section('content')
    <div class="public-page">

        <x-ad-placeholder />

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Encurte suas URLs</h1>

                <p>
                    Transforme links longos em URLs curtas de forma
                    simples, rápida e gratuita.
                </p>

            </div>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ url('/shorten') }}" method="POST">

                @csrf

                <div class="form-group">

                    <label for="url">
                        URL original
                    </label>

                    <input type="url" id="url" name="url" placeholder="https://exemplo.com/minha-url-longa"
                        value="{{ old('url') }}" required>

                </div>

                <button type="submit">
                    Encurtar URL
                </button>

            </form>

        </section>

        <div class="public-description">

            <h2>Simples e rápido</h2>

            <p>
                Cole sua URL, clique em encurtar e receba
                seu novo link imediatamente.
            </p>

        </div>

        <x-ad-placeholder />

    </div>
@endsection
