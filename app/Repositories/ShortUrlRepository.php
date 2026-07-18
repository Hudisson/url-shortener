<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ShortUrl;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;

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
}
