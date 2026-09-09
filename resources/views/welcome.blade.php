@extends('layouts.app')

@section('content')
    <div class="public-page">

        <x-ad-placeholder />

        <section class="shortener-card">

            <div class="shortener-header">

                <h1>Criar URL</h1>

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

                <!-- URL Original -->
                <div class="form-group">

                    <label for="url">
                        URL original
                    </label>

                    <input type="url" id="url" name="url" placeholder="https://exemplo.com/projeto"
                        value="{{ old('url') }}" required>

                </div>

                {{-- Tipo de URL e Código Personalizado - apenas usuário autenticado --}}
                @auth

                    <style>
                        .form-radio{
                            border: 1px solid #ddd;
                            padding: 10px;
                            border-radius: 4px;
                        }

                        .form-radio span{
                            font-size: 14px;
                            font-weight: 700;
                        }

                        .div-input-radio{
                            display: flex;
                        }

                        .div-input-radio input{
                            width: 25px;
                            outline: none !important;
                            box-shadow: none !important;

                        }
                        .input-url-automatica{
                            margin-top: 20px;
                        }
                        .input-url-automatica input{
                            margin-left: 10px;
                            margin-bottom: 10px;

                        }

                        .input-url-personalizada input{
                            margin-left: 94px;
                            margin-bottom: 10px;
                        }
                    </style>
                    <div class="form-group form-radio">
                        <span>Tipo de URL</span>
                        <div class="div-input-radio input-url-automatica">
                            <label>Gerada automaticamente</label>
                            <input type="radio" name="tipo_url" value="auto"
                                    {{ old('tipo_url', 'auto') == 'auto' ? 'checked' : '' }}
                                    onchange="toggleCustomCode(false)">
                        </div>

                        <div class="div-input-radio input-url-personalizada">
                            <label> Personalizada </label>
                            <input type="radio" name="tipo_url" value="custom"
                                    {{ old('tipo_url') == 'custom' ? 'checked' : '' }}
                                    onchange="toggleCustomCode(true)">
                        </div>
                    </div>

                    <!-- Código personalizado -->
                    <div class="form-group" id="custom-code-group" style="display: {{ old('tipo_url') == 'custom' ? 'block' : 'none' }};">

                        <label for="codigo_personalizado">
                            Código personalizado
                        </label>

                        <input type="text" id="codigo_personalizado" name="codigo_personalizado" placeholder="meu-projeto"
                            value="{{ old('codigo_personalizado') }}" minlength="3" maxlength="50">

                    </div>
                @endauth

                {{-- Campo de Etiqueta - apenas usuário autenticado --}}
                @auth
                    <div class="form-group">

                        <label for="label">
                            Etiqueta
                        </label>

                        <input type="text" id="label" name="label" placeholder="Projeto pessoal"
                            value="{{ old('label') }}" maxlength="255">

                    </div>
                @endauth

                <button type="submit">
                    Criar URL
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

    <!-- Script para alternar a exibição do campo de código personalizado de forma limpa -->
    <script>
        function toggleCustomCode(show) {
            const group = document.getElementById('custom-code-group');
            if (group) {
                group.style.display = show ? 'block' : 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const personalizadaRadio = document.querySelector('input[name="tipo_url"][value="custom"]');
            if (personalizadaRadio && personalizadaRadio.checked) {
                toggleCustomCode(true);
            }
        });
    </script>
@endsection
