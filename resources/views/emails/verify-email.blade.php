<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <title>Verifique sua conta</title>
</head>

<body>

    <h1>Olá, {{ $user->name }}!</h1>

    <p>
        Obrigado por se cadastrar no URL Shortener.
    </p>

    <p>
        Para verificar sua conta, utilize o código abaixo:
    </p>

    <h2>
        {{ $code }}
    </h2>

    <p>
        Este código é válido por 15 minutos.
    </p>

    <p>
        Se você não realizou este cadastro, ignore este e-mail.
    </p>

</body>

</html>
