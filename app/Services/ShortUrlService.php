<?php

declare(strict_types=1);

namespace App\Services;

use App\Logging\LoggerInterface;
use App\Support\Contracts\UniqueShortCodeGeneratorInterface;
use App\Validation\Contracts\UrlValidatorInterface;
use App\Models\ShortUrl;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;

final readonly class ShortUrlService
{
    public function __construct(
        private LoggerInterface $logger,
        private UniqueShortCodeGeneratorInterface $shortCodeGenerator,
        private UrlValidatorInterface $validator,
        private ShortUrlRepositoryInterface $repository,
    ) {}

    public function create(string $originalUrl): ShortUrl
    {

        $this->validator->validate($originalUrl);

        $this->logger->info(
            'Short URL creation started.',
            [
                'original_url' => $originalUrl,
            ]
        );

        $shortCode = $this->shortCodeGenerator->generate();

        $this->logger->info(
            'Short code generated successfully.',
            [
                'short_code' => $shortCode,
            ]
        );

        $shortUrl = $this->buildShortUrl($originalUrl, $shortCode);

        $shortUrl = $this->repository->save($shortUrl);

        $this->logger->info(
            'Short URL created successfully.',
            [
                'short_code' => $shortUrl->short_code,
            ]
        );

        return $shortUrl;
    }

    /**
     * Método responsável por montar a entidade ShortUrl com os valores iniciais.
     */
    private function buildShortUrl(string $originalUrl, string $shortCode): ShortUrl
    {
        $shortUrl = new ShortUrl();
        $shortUrl->original_url = $originalUrl;
        $shortUrl->short_code = $shortCode;
        $shortUrl->clicks = 0;
        $shortUrl->is_active = true;

        return $shortUrl;
    }
}
