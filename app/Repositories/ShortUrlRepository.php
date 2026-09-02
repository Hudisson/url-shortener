<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ShortUrl;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final readonly class ShortUrlRepository implements ShortUrlRepositoryInterface
{

    /**
     * Persiste uma URL encurtada.
     */
    public function save(ShortUrl $shortUrl): ShortUrl
    {
        $shortUrl->save();

        return $shortUrl;
    }

    /**
     * Verifica se já existe uma URL com o short_code gerado
     */
    public function existsByShortCode(string $shortCode): bool
    {
        return ShortUrl::query()
            ->where('short_code', $shortCode)
            ->exists();
    }

    /**
     * Busca uma URL encurtada pelo seu código.
     */
    public function findByShortCode(string $shortCode): ?ShortUrl
    {
        return ShortUrl::query()
            ->where('short_code', $shortCode)
            ->first();
    }

    /**
     * Busca todas as URLs encurtadas de um usuário.
     */
    public function findByUserId(int $userId): Collection
    {
        return ShortUrl::query()
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    /**
     * Busca uma URL encurtada pelo código pertencente a um usuário.
     */
    public function findByShortCodeAndUserId(string $shortCode, int $userId): ?ShortUrl
    {
        return ShortUrl::query()
            ->where('short_code', $shortCode)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Exclui uma URL encurtada pertencente a um usuário.
     */
    public function deleteByShortCodeAndUserId(string $shortCode, int $userId): bool
    {
        return ShortUrl::query()
            ->where('short_code', $shortCode)
            ->where('user_id', $userId)
            ->delete() > 0;
    }
}
