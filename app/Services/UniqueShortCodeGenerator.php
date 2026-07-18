<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use App\Support\Contracts\ShortCodeGeneratorInterface;
use App\Support\Contracts\UniqueShortCodeGeneratorInterface;
use RuntimeException;

final readonly class UniqueShortCodeGenerator implements UniqueShortCodeGeneratorInterface
{

    private const MAX_ATTEMPTS = 10;

    public function __construct(
        private ShortCodeGeneratorInterface $generator,
        private ShortUrlRepositoryInterface $repository,
    ) {
    }

    public function generate(): string
    {
        for( $attempt = 0; $attempt < self::MAX_ATTEMPTS; $attempt++){
            $shortCode = $this->generator->generate();
            if(! $this->repository->existsByShortCode($shortCode)){
                return $shortCode;
            }
        }

        throw new RuntimeException(
            'Unable to generate a unique short code.'
        );
    }
}
