<?php

declare(strict_types=1);

namespace App\Support\Contracts;

interface ShortCodeGeneratorInterface
{
    /**
     * Gera um código curto.
     */

    public function generate(int $length = 6): string;

}
