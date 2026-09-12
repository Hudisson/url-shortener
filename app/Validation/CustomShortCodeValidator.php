<?php

declare(strict_types=1);

namespace App\Validation;

use App\Validation\Contracts\CustomShortCodeValidatorInterface;
use InvalidArgumentException;

final readonly class CustomShortCodeValidator implements CustomShortCodeValidatorInterface
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 50;

    private const ERROR_INVALID_SHORT_CODE =
        'O código personalizado fornecido é inválido.';

    private const RESERVED_CODES = [
        'login',
        'register',
        'logout',
        'dashboard',
        'urls',
        'metrics',
        'about',
        'api',
    ];

    public function validate(string $shortCode): void
    {

        $shortCode = trim($shortCode);

        $shortCode = strtolower($shortCode);

        $length = strlen($shortCode);

        if (
            $length < self::MIN_LENGTH ||
            $length > self::MAX_LENGTH ||
            ! preg_match('/^[a-zA-Z0-9_-]+$/', $shortCode) ||
            in_array(strtolower($shortCode), self::RESERVED_CODES, true)
        ) {
            throw new InvalidArgumentException(
                self::ERROR_INVALID_SHORT_CODE
            );
        }
    }
}
