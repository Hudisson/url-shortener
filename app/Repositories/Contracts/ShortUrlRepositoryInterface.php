<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\ShortUrl;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Busca todas as URLs encurtadas de um usuário.
     */
    public function findByUserId(int $userId): Collection;

    /**
     * Busca uma URL encurtada pelo código pertencente a um usuário.
     */
    public function findByShortCodeAndUserId(string $shortCode, int $userId): ?ShortUrl;
}
