<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Código para redefinição de senha</title>
</head>

<body
    style="
    margin: 0;
    padding: 0;
    background-color: #f4f4f5;
    font-family: Arial, Helvetica, sans-serif;
    color: #18181b;
">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color: #f4f4f5; padding: 40px 20px;">
        <tr>
            <td align="center">

                <!-- Container -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="
                        max-width: 560px;
                        background-color: #ffffff;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
                    ">

                    <!-- Header -->
                    <tr>
                        <td align="center"
                            style="
                                background-color: #18181b;
                                padding: 32px 24px;
                            ">
                            <h1
                                style="
                                margin: 0;
                                color: #ffffff;
                                font-size: 24px;
                                line-height: 1.3;
                                font-weight: 700;
                            ">
                                Redefinição de senha
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 36px 32px;">

                            <h2
                                style="
                                margin: 0 0 18px;
                                font-size: 22px;
                                line-height: 1.4;
                                color: #18181b;
                            ">
                                Olá, {{ $user->name }}!
                            </h2>

                            <p
                                style="
                                margin: 0 0 16px;
                                font-size: 16px;
                                line-height: 1.7;
                                color: #52525b;
                            ">
                                Recebemos uma solicitação para redefinir a senha
                                da sua conta.
                            </p>

                            <p
                                style="
                                margin: 0 0 24px;
                                font-size: 16px;
                                line-height: 1.7;
                                color: #52525b;
                            ">
                                Para confirmar a alteração, informe o código
                                abaixo na página de redefinição de senha:
                            </p>

                            <!-- Code -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin: 0 0 28px;">
                                <tr>
                                    <td align="center"
                                        style="
                                            background-color: #f4f4f5;
                                            border: 1px solid #e4e4e7;
                                            border-radius: 10px;
                                            padding: 24px 16px;
                                        ">
                                        <span
                                            style="
                                            display: block;
                                            font-size: 36px;
                                            line-height: 1;
                                            letter-spacing: 8px;
                                            font-weight: 700;
                                            color: #18181b;
                                        ">
                                            {{ $code }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Expiration -->
                            <p
                                style="
                                margin: 0 0 24px;
                                padding: 14px 16px;
                                background-color: #fafafa;
                                border-radius: 8px;
                                font-size: 14px;
                                line-height: 1.6;
                                color: #71717a;
                            ">
                                <strong style="color: #18181b;">
                                    Atenção:
                                </strong>
                                este código é válido por
                                <strong style="color: #18181b;">
                                    15 minutos
                                </strong>
                                e pode ser utilizado apenas uma vez.
                            </p>

                            <p
                                style="
                                margin: 0;
                                font-size: 14px;
                                line-height: 1.7;
                                color: #71717a;
                            ">
                                Se você não solicitou a redefinição de senha,
                                pode ignorar este e-mail. Sua senha não será
                                alterada sem a confirmação do código.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="
                                padding: 22px 32px;
                                background-color: #fafafa;
                                border-top: 1px solid #e4e4e7;
                            ">
                            <p
                                style="
                                margin: 0;
                                font-size: 12px;
                                line-height: 1.5;
                                color: #71717a;
                            ">
                                Este é um e-mail automático. Por favor,
                                não responda a esta mensagem.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
