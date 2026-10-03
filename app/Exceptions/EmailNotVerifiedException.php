<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class EmailNotVerifiedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('E-mail ainda não verificado.');
    }
}
