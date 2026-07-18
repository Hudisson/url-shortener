<?php

declare(strict_types=1);

namespace App\Validation;

use App\Validation\Contracts\UrlValidatorInterface;
use InvalidArgumentException;

final readonly class UrlValidator implements UrlValidatorInterface
{
    private const ERROR_INVALID_URL = 'The URL provided is invalid.';

    public function validate(string $url): void
    {
        if( ! filter_var($url, FILTER_VALIDATE_URL)){
            throw new InvalidArgumentException(
                self::ERROR_INVALID_URL
            );
        }
    }
}
