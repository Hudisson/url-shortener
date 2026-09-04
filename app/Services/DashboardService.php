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
    public function getUserShortUrl(string $shortCode, int $userId): ?ShortUrl
    {
        return $this->repository->findByShortCodeAndUserId(
            $shortCode,
            $userId
        );
    }

    /**
     * Exclui uma URL encurtada pertencente ao usuário.
     */
    public function deleteUserShortUrl(string $shortCode, int $userId): bool
    {
        return $this->repository->deleteByShortCodeAndUserId(
            $shortCode,
            $userId
        );
    }


    /**
     * Retorna a quantidade total de URLs encurtadas do usuário.
     */
    public function countUserShortUrls(int $userId): int
    {
        return $this->repository->countByUserId($userId);
    }

    /**
     * Retorna a quantidade de URLs ativas do usuário.
     */
    public function countUserActiveShortUrls(int $userId): int
    {
        return $this->repository->countActiveByUserId($userId);
    }

    /**
     * Retorna a quantidade de URLs inativas do usuário.
     */
    public function countUserInactiveShortUrls(int $userId): int
    {
        return $this->repository->countInactiveByUserId($userId);
    }

    /**
     * Retorna as URLs mais acessadas do usuário.
     */
    public function getMostAccessedUserShortUrls(int $userId, int $limit = 3): Collection
    {
        return $this->repository->findMostAccessedByUserId(
            $userId,
            $limit
        );
    }
}
