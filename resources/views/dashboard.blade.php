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

    </section>

</div>

@endsection
