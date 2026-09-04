@extends('layouts.app')

@section('content')
    <div class="dashboard">

        <div class="dashboard-header">

            <h1>
                Minhas URLs
            </h1>

            <p>
                Gerencie todas as suas URLs encurtadas.
            </p>

        </div>

        <div class="short-url-list">

            @forelse ($shortUrls as $shortUrl)
                <div class="short-url-item">

                    <div class="short-url-info">

                        <a href="{{ url($shortUrl->short_code) }}" class="short-url-code" target="_blank"
                            rel="noopener noreferrer">
                            {{ url($shortUrl->short_code) }}
                        </a>

                        <p class="short-url-original">
                            {{ $shortUrl->original_url }}
                        </p>

                    </div>

                    <div class="short-url-meta">

                        <span>
                            {{ $shortUrl->clicks }}
                            {{ $shortUrl->clicks === 1 ? 'acesso' : 'acessos' }}
                        </span>

                        <span>
                            {{ $shortUrl->created_at->format('d/m/Y') }}
                            às
                            {{ $shortUrl->created_at->format('H:i') }}
                        </span>

                    </div>

                    <div class="short-url-actions">

                        <a href="{{ route('dashboard.metrics', $shortUrl->short_code) }}"
                            class="button short-url-action btn-view-metrics-url">
                            Métricas
                            <i class="fa-solid fa-square-poll-vertical"></i>
                        </a>

                        <button type="button" class="button short-url-action btn-copy-url">
                            Copiar
                            <i class="fa-solid fa-copy"></i>
                        </button>

                        <button type="button" class="button short-url-action btn-edit-url">
                            Editar
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <button type="button" class="button short-url-action btn-qr-url">
                            QR code
                            <i class="fa-solid fa-qrcode"></i>
                        </button>

                        <form action="{{ route('dashboard.destroy', $shortUrl->short_code) }}" method="POST"
                            class="delete-url-form">
                            @csrf
                            @method('DELETE')

                            <button type="button" class="button short-url-action btn-delete-url">
                                Excluir
                                <i class="fa-solid fa-trash"></i>
                            </button>

                        </form>

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

        {{-- Modal de confirmação de exclusão --}}

        <div id="delete-modal" class="delete-modal" aria-hidden="true">

            <div class="delete-modal-content" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">

                <h2 id="delete-modal-title">
                    Excluir URL
                </h2>

                <p>
                    Tem certeza de que deseja excluir esta URL?
                    Essa ação não poderá ser desfeita.
                </p>

                <div class="delete-modal-actions">

                    <button type="button" id="delete-modal-cancel" class="delete-modal-button delete-modal-cancel">
                        Cancelar
                    </button>

                    <button type="button" id="delete-modal-confirm" class="delete-modal-button delete-modal-confirm">
                        Excluir
                    </button>

                </div>

            </div>

        </div>

        {{-- Fim do modal --}}

    </div>
@endsection
