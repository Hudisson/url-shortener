<?php

declare(strict_types=1);

namespace App\Validation\Contracts;

interface UrlValidatorInterface
{
    /**
     * Valida uma URL.
     *
     * Lança uma exceção caso seja inválida.
     */
    public function validate(string $url): void;
}
