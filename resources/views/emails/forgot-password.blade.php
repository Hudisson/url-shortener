<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Redefinição de senha</title>
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

                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="
                        max-width: 560px;
                        background-color: #ffffff;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
                    ">

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
                                margin: 0 0 24px;
                                font-size: 16px;
                                line-height: 1.7;
                                color: #52525b;
                            ">
                                Recebemos uma solicitação para redefinir a senha da sua conta. Clique no botão abaixo para
                                escolher uma nova senha.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin: 0 0 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}"
                                            style="
                                                display: inline-block;
                                                padding: 14px 24px;
                                                background-color: #18181b;
                                                border-radius: 7px;
                                                color: #ffffff;
                                                font-size: 16px;
                                                font-weight: 600;
                                                text-decoration: none;
                                            ">
                                            Redefinir senha
                                        </a>
                                    </td>
                                </tr>
                            </table>

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
                                O link é válido por 60 minutos e pode ser usado uma única vez.
                            </p>

                            <p
                                style="
                                margin: 0;
                                font-size: 14px;
                                line-height: 1.7;
                                color: #71717a;
                            ">
                                Se você não solicitou a redefinição, ignore este e-mail. Sua senha não será alterada.
                            </p>

                        </td>
                    </tr>

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
                                Este é um e-mail automático. Por favor, não responda a esta mensagem.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
