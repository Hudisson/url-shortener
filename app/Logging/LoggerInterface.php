<?php

declare(strict_types=1);

namespace App\Logging;

// Contrato que define os métodos obrigatórios para qualquer implementação de logger.
interface LoggerInterface
{
    // Mensagens detalhadas para desenvolvimento/depuração
    public function debug(string $message, array $context = []): void;

    // Informações gerais sobre o funcionamento do sistema
    public function info(string $message, array $context = []): void;

    // Avisos sobre algo que precisa de atenção, mas não trava o sistema
    public function warning(string $message, array $context = []): void;

    // Erros que devem ser corrigidos, mas que o sistema conseguiu contornar
    public function error(string $message, array $context = []): void;

    // Erros graves que comprometem o funcionamento da aplicação (ex: banco fora do ar)
    public function critical(string $message, array $context = []): void;
}
