<?php

declare(strict_types=1);

namespace App\Logging;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

final readonly class Logger implements LoggerInterface
{
    public function debug(string $message, array $context = []): void
    {
        $this->write(LogLevel::DEBUG, $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->write(LogLevel::INFO, $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->write(LogLevel::WARNING, $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->write(LogLevel::ERROR, $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->write(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Escreve uma mensagem no sistema de logs do Laravel.
     */
    private function write(
        LogLevel $level,
        string $message,
        array $context = [],
    ): void {
        // Avalia o nível do log e chama o método correspondente do Laravel
        match ($level) {
            LogLevel::DEBUG => Log::debug($message, $context),
            LogLevel::INFO => Log::info($message, $context),
            LogLevel::WARNING => Log::warning($message, $context),
            LogLevel::ERROR => Log::error($message, $context),
            LogLevel::CRITICAL => Log::critical($message, $context),

            // Dispara um erro caso um nível não mapeado seja enviado
            default => throw new InvalidArgumentException(
                sprintf('Nível de log inválido: %s', $level->value)
            ),
        };
    }
}
