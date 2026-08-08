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
            throw new \RuntimeException('Short URL not found.');
        }

        if(! $shortUrl->is_active){
            throw new \RuntimeException('Short URL is inactive.');
        }

        $shortUrl->clicks++;
        $this->repository->save($shortUrl);

        return $shortUrl->original_url;
    }
}
