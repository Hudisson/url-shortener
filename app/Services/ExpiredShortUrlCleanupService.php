<?php

declare(strict_types=1);

namespace App\Services;

use App\Logging\LoggerInterface;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use Throwable;

final readonly class ExpiredShortUrlCleanupService
{
    public function __construct(
        private LoggerInterface $logger,
        private ShortUrlRepositoryInterface $repository,
    ) {}

    public function deleteExpiredShortUrls(): int
    {
        $runAt = now();
        $context = [
            'executed_at' => $runAt->toIso8601String(),
        ];

        $this->logger->info('Expired short URL cleanup started.', $context);

        try {
            $deletedCount = $this->repository->deleteExpiredAtOrBefore($runAt);
        } catch (Throwable $exception) {
            $this->logger->error(
                'Expired short URL cleanup failed.',
                $context + ['error' => $exception->getMessage()],
            );

            throw $exception;
        }

        $this->logger->info(
            'Expired short URL cleanup completed.',
            $context + ['deleted_count' => $deletedCount],
        );

        return $deletedCount;
    }
}
