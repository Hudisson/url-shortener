<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\ShortUrlRepositoryInterface;

final readonly class ShortUrlRedirectService
{
    public function __construct(
        private ShortUrlRepositoryInterface $repository,
    ) {}

    public function redirect(string $shortCode): string
    {
        $shortUrl = $this->repository->findByShortCode($shortCode);

        if($shortUrl === null){
            throw new \RuntimeException('URL curta não encontrada.');
        }

        if(! $shortUrl->is_active){
            throw new \RuntimeException('A URL curta está inativa.');
        }

        $shortUrl->clicks++;
        $this->repository->save($shortUrl);

        return $shortUrl->original_url;
    }
}
