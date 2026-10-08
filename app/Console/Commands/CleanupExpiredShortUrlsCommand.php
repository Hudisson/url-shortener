<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ExpiredShortUrlCleanupService;
use Illuminate\Console\Command;

final class CleanupExpiredShortUrlsCommand extends Command
{
    protected $signature = 'short-urls:cleanup-expired';

    protected $description = 'Delete short URLs that have expired.';

    public function handle(ExpiredShortUrlCleanupService $service): int
    {
        $deletedCount = $service->deleteExpiredShortUrls();

        $this->info("Expired short URLs deleted: {$deletedCount}.");

        return self::SUCCESS;
    }
}
