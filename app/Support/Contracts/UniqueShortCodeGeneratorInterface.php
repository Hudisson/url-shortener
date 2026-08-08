<?php

declare(strict_types=1);

namespace App\Support\Contracts;

interface UniqueShortCodeGeneratorInterface
{
    public function generate(): string;
}
