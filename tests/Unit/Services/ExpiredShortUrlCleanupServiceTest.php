<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Logging\LoggerInterface;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use App\Services\ExpiredShortUrlCleanupService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ExpiredShortUrlCleanupServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_logs_the_run_time_and_number_of_deleted_urls(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-08 10:00:00'));

        $logger = $this->createMock(LoggerInterface::class);
        $repository = $this->createMock(ShortUrlRepositoryInterface::class);
        $loggedMessages = [];

        $logger->expects($this->exactly(2))
            ->method('info')
            ->willReturnCallback(
                function (string $message, array $context) use (&$loggedMessages): void {
                    $loggedMessages[] = [$message, $context];
                }
            );

        $repository->expects($this->once())
            ->method('deleteExpiredAtOrBefore')
            ->with($this->callback(
                fn ($dateTime): bool => $dateTime->toDateTimeString() === '2026-10-08 10:00:00'
            ))
            ->willReturn(3);

        $service = new ExpiredShortUrlCleanupService($logger, $repository);

        $this->assertSame(3, $service->deleteExpiredShortUrls());
        $this->assertSame('Expired short URL cleanup started.', $loggedMessages[0][0]);
        $this->assertSame('Expired short URL cleanup completed.', $loggedMessages[1][0]);
        $this->assertSame('2026-10-08T10:00:00+00:00', $loggedMessages[0][1]['executed_at']);
        $this->assertSame('2026-10-08T10:00:00+00:00', $loggedMessages[1][1]['executed_at']);
        $this->assertSame(3, $loggedMessages[1][1]['deleted_count']);
    }

    public function test_it_logs_and_rethrows_repository_errors(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $repository = $this->createMock(ShortUrlRepositoryInterface::class);
        $exception = new RuntimeException('Database unavailable.');

        $logger->expects($this->once())
            ->method('info');

        $logger->expects($this->once())
            ->method('error')
            ->with(
                'Expired short URL cleanup failed.',
                $this->callback(
                    fn (array $context): bool => isset($context['executed_at'])
                        && $context['error'] === 'Database unavailable.'
                )
            );

        $repository->expects($this->once())
            ->method('deleteExpiredAtOrBefore')
            ->willThrowException($exception);

        $service = new ExpiredShortUrlCleanupService($logger, $repository);

        $this->expectExceptionObject($exception);

        $service->deleteExpiredShortUrls();
    }
}
