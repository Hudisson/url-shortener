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

    /**
     * Exclui uma URL encurtada pertencente a um usuário.
     */
    public function deleteByShortCodeAndUserId(string $shortCode, int $userId): bool;


    /**
     * Retorna a quantidade de URLs encurtadas pertencentes a um usuário.
     */
    public function countByUserId(int $userId): int;

    /**
     * Retorna a quantidade de URLs ativas pertencentes a um usuário.
     */
    public function countActiveByUserId(int $userId): int;

    /**
     * Retorna a quantidade de URLs inativas pertencentes a um usuário.
     */
    public function countInactiveByUserId(int $userId): int;

    /**
     * Retorna as URLs mais acessadas pertencentes a um usuário.
     */
    public function findMostAccessedByUserId( int $userId, int $limit = 3): Collection;
}
