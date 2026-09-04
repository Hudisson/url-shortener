@extends('layouts.app')

@section('content')

    <div class="dashboard">

        <div class="dashboard-header">
            <h1>Dashboard</h1>

            <p>
                Resumo das suas URLs encurtadas.
            </p>
        </div>

        <div class="dashboard-stats">

            <div class="dashboard-stat-card">
                <span class="dashboard-stat-label">
                    Total de URLs
                </span>

                <strong class="dashboard-stat-value">
                    {{ $totalUrls }}
                </strong>
            </div>

            <div class="dashboard-stat-card">
                <span class="dashboard-stat-label">
                    URLs ativas
                </span>

                <strong class="dashboard-stat-value">
                    {{ $activeUrls }}
                </strong>
            </div>

            <div class="dashboard-stat-card">
                <span class="dashboard-stat-label">
                    URLs inativas
                </span>

                <strong class="dashboard-stat-value">
                    {{ $inactiveUrls }}
                </strong>
            </div>

        </div>

        <div class="dashboard-section">

            <div class="dashboard-section-header">
                <h2>URLs mais acessadas</h2>

                <p>
                    Suas três URLs com maior número de acessos.
                </p>
            </div>

            @if ($mostAccessedUrls->isEmpty())
                <div class="dashboard-empty">
                    <p>
                        Você ainda não possui URLs encurtadas.
                    </p>
                </div>
            @else
                <div class="most-accessed-list">

                    @foreach ($mostAccessedUrls as $shortUrl)
                        <div class="most-accessed-item">

                            <div class="most-accessed-info">


                                @if (!$shortUrl->label)
                                    <span class="etiqueta-sem-titulo">
                                        Sem título
                                    </span>
                                @else
                                    <span class="etiqueta">
                                        {{ $shortUrl->label }}
                                    </span>
                                @endif

                                <hr>

                                <a href="{{ url($shortUrl->short_code) }}" target="_blank" rel="noopener noreferrer">
                                    {{ url($shortUrl->short_code) }}
                                </a>

                                <span>
                                    {{ $shortUrl->clicks }}
                                    {{ $shortUrl->clicks === 1 ? 'acesso' : 'acessos' }}
                                </span>

                            </div>

                            <a href="{{ route('dashboard.metrics', $shortUrl->short_code) }}"
                                class="button short-url-action btn-view-metrics-url">
                                Métricas
                                <i class="fa-solid fa-chart-simple"></i>
                            </a>

                        </div>
                    @endforeach

                </div>
            @endif

        </div>

        <div class="dashboard-section dashboard-all-urls">

            <a href="{{ route('dashboard.urls') }}" class="button">
                Visualizar todas as URLs
            </a>

        </div>

    </div>

@endsection
