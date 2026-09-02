@extends('layouts.app')

@section('content')
    <div class="public-page">

        <section class="shortener-card metrics-card">

            <div class="shortener-header">

                <h1>Métricas da URL</h1>

            </div>

            <div class="metrics-url-info">

                <div class="metrics-url-field">

                    <span class="metrics-label">
                        URL encurtada
                    </span>

                    <a href="{{ url($shortUrl->short_code) }}" target="_blank" class="short-url-metrics">
                        {{ url($shortUrl->short_code) }}
                    </a>

                </div>

                <div class="metrics-url-field">

                    <span class="metrics-label">
                        URL original
                    </span>

                    <p class="short-url-original">
                        {{ $shortUrl->original_url }}
                    </p>

                </div>

            </div>

            <hr>

            <div class="metrics-summary">

                <div class="metric-item">

                    <i class="fa-solid fa-arrow-pointer"></i>

                    <span class="metric-label">
                        Cliques
                    </span>

                    <strong class="metric-value">
                        {{ $shortUrl->clicks }}
                    </strong>

                </div>

                <div class="metric-item">

                    <i class="fa-solid fa-calendar-plus"></i>

                    <span class="metric-label">
                        Criada em
                    </span>

                    <strong class="metric-value metric-date">
                        {{ $shortUrl->created_at->format('d/m/Y') }}
                        às
                        {{ $shortUrl->created_at->format('H:i') }}
                    </strong>

                </div>

            </div>

            <div class="metrics-actions">

                <a href="{{ route('dashboard') }}" class="button secondary-button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Voltar ao Dashboard
                </a>

            </div>

        </section>

    </div>
@endsection
