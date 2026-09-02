<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShortUrl;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final readonly class DashboardService
{
    public function __construct(
        private ShortUrlRepositoryInterface $repository,
    ) {}

    /**
     * Retorna todas as URLs encurtadas pertencentes ao usuário.
     */
    public function getUserShortUrls(int $userId): Collection
    {
        return $this->repository->findByUserId($userId);
    }

    /**
     * Retorna uma URL encurtada pertencente ao usuário.
     */
    public function getUserShortUrl(string $shortCode, int $userId): ?ShortUrl {
        return $this->repository->findByShortCodeAndUserId(
            $shortCode,
            $userId
        );
    }
}
