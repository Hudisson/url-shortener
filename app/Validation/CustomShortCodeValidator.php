<?php

declare(strict_types=1);

namespace App\Validation;

use App\Validation\Contracts\CustomShortCodeValidatorInterface;
use InvalidArgumentException;

final readonly class CustomShortCodeValidator implements CustomShortCodeValidatorInterface
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 20;

    private const ERROR_INVALID_SHORT_CODE =
        'The custom short code provided is invalid.';

    public function validate(string $shortCode): void
    {
        $length = strlen($shortCode);

        if (
            $length < self::MIN_LENGTH ||
            $length > self::MAX_LENGTH ||
            ! preg_match('/^[a-zA-Z0-9_-]+$/', $shortCode)
        ) {
            throw new InvalidArgumentException(
                self::ERROR_INVALID_SHORT_CODE
            );
        }
    }
}
