<?php

declare(strict_types=1);

namespace App\Validation\Contracts;

interface CustomShortCodeValidatorInterface
{
    /**
     * Valida um código personalizado.
     *
     * Lança uma exceção caso seja inválido.
     */
    public function validate(string $shortCode): void;
}
