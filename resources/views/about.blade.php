@extends('layouts.app')

@section('content')
    <div class="public-page">

        <x-ad-placeholder />

        <section class="shortener-card about-card">

            <div class="shortener-header">

                <h1>Sobre o URL Shortener</h1>

                <p>
                    Um serviço simples para criar e compartilhar
                    URLs curtas de forma rápida.
                </p>

            </div>

            <div class="about-content">

                <h2>Sobre o projeto</h2>

                <p>
                    O URL Shortener é uma aplicação desenvolvida para
                    transformar URLs longas em links curtos, facilitando
                    seu compartilhamento e utilização.
                </p>

                <p>
                    Visitantes podem criar URLs curtas gratuitamente,
                    sem a necessidade de possuir uma conta.
                </p>


                <h2>Política de uso</h2>

                <p>
                    O serviço deve ser utilizado de forma responsável
                    e exclusivamente para fins legais.
                </p>

                <p>
                    Não é permitido utilizar a aplicação para criar,
                    divulgar ou distribuir links destinados a atividades
                    ilegais, fraudulentas, maliciosas ou que possam causar
                    danos a terceiros.
                </p>

                <p>
                    URLs que violem essas regras poderão ser desativadas
                    ou removidas da aplicação.
                </p>


                <h2>Disponibilidade das URLs</h2>

                <p>
                    As URLs criadas por visitantes permanecem disponíveis
                    enquanto estiverem ativas e armazenadas pelo serviço.
                </p>

                <p>
                    Usuários autenticados poderão futuramente definir
                    um período de expiração para suas URLs, permitindo
                    determinar por quanto tempo cada link permanecerá
                    acessível.
                </p>

                <p>
                    Uma URL poderá deixar de funcionar caso seja
                    desativada, removida ou tenha atingido uma condição
                    de expiração ou limite configurado pelo usuário.
                </p>


                <h2>Código aberto</h2>

                <p>
                    O URL Shortener é um projeto de código aberto
                    desenvolvido como parte do meu portfólio e está
                    disponível publicamente no GitHub.
                </p>

                <p>
                    Você pode consultar o código-fonte, acompanhar o
                    desenvolvimento e contribuir com sugestões ou
                    melhorias.
                </p>

                <a href="https://github.com/Hudisson/url-shortener" target="_blank" rel="noopener noreferrer" class="button">
                    Ver projeto no GitHub
                </a>


                <h2>Desenvolvedor</h2>

                <p>
                    O projeto é desenvolvido por
                    <strong>Hudisson Rodrigues Xavier</strong>,
                    desenvolvedor e estudante de Ciência da Computação.
                </p>

                <p>
                    Para informações profissionais, sugestões ou outras
                    formas de contato, consulte os canais disponíveis
                    no perfil do desenvolvedor.
                </p>

            </div>

        </section>

        <x-ad-placeholder />

    </div>
@endsection
