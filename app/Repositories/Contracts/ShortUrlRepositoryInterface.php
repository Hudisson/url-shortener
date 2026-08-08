<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\ShortUrl;

interface ShortUrlRepositoryInterface
{
    /**
     * Persiste uma URL encurtada.
     */
    public function save(ShortUrl $shortUrl): ShortUrl;

    /**
     * Verifica se já existe uma URL com o short_code gerado
     */
    public function existsByShortCode(string $shortCode): bool;

    /**
     * Busca uma URL encurtada pelo seu código.
     */
    public function findByShortCode(string $shortCode): ?ShortUrl;
}
