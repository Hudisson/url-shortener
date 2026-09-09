<?php

declare(strict_types=1);

namespace App\Services;

use App\Logging\LoggerInterface;
use App\Models\ShortUrl;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use App\Validation\CustomShortCodeValidator;
use App\Validation\Contracts\UrlValidatorInterface;

final readonly class ShortCustomUrlService
{
    public function __construct(
        private LoggerInterface $logger,
        private UrlValidatorInterface $urlValidator,
        private CustomShortCodeValidator $validator,
        private ShortUrlRepositoryInterface $repository,
    ) {}

    public function create(
        string $originalUrl,
        string $codigoPersonalizado,
        int $userId,
        ?string $label = null,
    ): ShortUrl {

        $this->urlValidator->validate($originalUrl); // Valida a URL original
        $this->validator->validate($codigoPersonalizado); // valida o código personalizado

        // Verifica se o código personalizado já exite no Banco de Dados
        if ($this->repository->existsByShortCode($codigoPersonalizado)) {
            throw new \InvalidArgumentException(
                'The custom short code is already in use.'
            );
        }

        $this->logger->info(
            'Short URL creation started.',
            [
                'original_url' => $originalUrl,
                'short_code' => $codigoPersonalizado,
                'label' => $label,
                'type' =>  'custom',
                'user_id' => $userId,
            ]
        );

        $this->logger->info(
            'Short code generated successfully.',
            [
                'short_code' => $codigoPersonalizado,
            ]
        );

        $shortUrl = $this->buildShortUrl(
            $originalUrl,
            $codigoPersonalizado,
            $userId,
            $label
        );

        $shortUrl = $this->repository->save($shortUrl);

        $this->logger->info(
            'Short URL created successfully.',
            [
                'short_code' => $shortUrl->short_code,
                'label' => $label,
                'type' => $shortUrl->type,
                'user_id' => $shortUrl->user_id,
            ]
        );

        return $shortUrl;
    }

    /**
     * Método responsável por montar a entidade ShortUrl com os valores iniciais.
     */
    private function buildShortUrl(
        string $originalUrl,
        string $codigoPersonalizado,
        int $userId,
        string $label,
    ): ShortUrl {
        $shortUrl = new ShortUrl();

        $shortUrl->user_id = $userId;
        $shortUrl->label = $label;
        $shortUrl->type = 'custom';
        $shortUrl->original_url = $originalUrl;
        $shortUrl->short_code = $codigoPersonalizado;
        $shortUrl->clicks = 0;
        $shortUrl->is_active = true;

        return $shortUrl;
    }
}
