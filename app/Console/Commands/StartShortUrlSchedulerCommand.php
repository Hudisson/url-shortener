<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ExpiredShortUrlCleanupService;
use Illuminate\Console\Command;

final class StartShortUrlSchedulerCommand extends Command
{
    protected $signature = 'short-urls:scheduler';

    protected $description = 'Run the expired URL cleanup immediately, then start the Laravel scheduler.';

    public function handle(ExpiredShortUrlCleanupService $service): int
    {
        $deletedCount = $service->deleteExpiredShortUrls();

        $this->info("Startup cleanup deleted {$deletedCount} expired short URLs.");

        return $this->call('schedule:work');
    }
}
