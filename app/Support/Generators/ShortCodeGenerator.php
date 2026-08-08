<?php

declare(strict_types=1);

namespace App\Support\Generators;

use App\Support\Contracts\ShortCodeGeneratorInterface;
use InvalidArgumentException;

final readonly class ShortCodeGenerator implements ShortCodeGeneratorInterface
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    private const DEFAULT_LENGTH = 6;

    private const ERROR_INVALID_LENGTH = 'The short code length must be greater than zero.';

    /**
     * Método responsável por: validar o tamanho, percorrer o laço, montar a string
     */
    public function generate(int $length = self::DEFAULT_LENGTH): string
    {
        $this->validateLength($length);
        $code = '';
        for($i = 0; $i < $length; $i++){
            $code .= $this->generateCharacter();
        }

        return $code;
    }

    /**
     * Método responsável por: escolher um índice aleatório, retornar um único caractere
     */
    private function generateCharacter(): string
    {
        $index = random_int(0, strlen(self::ALPHABET) - 1);
        return self::ALPHABET[$index];
    }

    /**
     * Método responsável por garantir que o tamanho solicitado seja válido.
     */
    private function validateLength(int $length): void
    {
        if($length < 1){
            throw new InvalidArgumentException(
                self::ERROR_INVALID_LENGTH
            );
        }
    }
}

