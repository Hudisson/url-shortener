@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="card">

            <h1>URL Shortener</h1>

            <p class="description">
                Encurte sua URL de forma simples e rápida.
            </p>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ url('/shorten') }}" method="POST">
                @csrf

                <label for="url">URL</label>

                <input type="url" id="url" name="url" placeholder="https://example.com"
                    value="{{ old('url') }}" required>

                <button type="submit">
                    Encurtar URL
                </button>
            </form>

        </section>
    </div>
@endsection
