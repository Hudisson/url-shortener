@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Dashboard</h1>

                <p>
                    Olá, {{ auth()->user()->name }}!
                </p>

                <p>
                    Você está autenticado.
                </p>

            </div>

            <div class="short-url-list">

                @forelse ($shortUrls as $shortUrl)
                    <div class="short-url-item">

                        <div class="short-url-info">

                            <a href="{{ url($shortUrl->short_code) }}" class="short-url-code" target="_blank">
                                {{ url($shortUrl->short_code) }}
                            </a>

                            <p class="short-url-original">
                                {{ $shortUrl->original_url }}
                            </p>

                        </div>

                        <div class="short-url-meta">

                            <span>
                                {{ $shortUrl->clicks }} cliques
                            </span>

                            <span>
                                {{ $shortUrl->created_at->format('d/m/Y') }} às {{ $shortUrl->created_at->format('H:i') }}
                            </span>

                        </div>

                    </div>

                @empty

                    <div class="short-url-empty">
                        <p>
                            Você ainda não possui nenhuma URL encurtada.
                        </p>
                    </div>
                @endforelse

            </div>

        </section>

    </div>
@endsection
