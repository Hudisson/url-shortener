<?php

namespace Tests\Unit\Services;

use App\Models\ShortUrl;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use App\Services\ShortUrlRedirectService;


use PHPUnit\Framework\TestCase;

class ShortUrlRedirectServiceTest extends TestCase
{
    private ShortUrlRepositoryInterface $repository;
    private ShortUrlRedirectService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(
            ShortUrlRepositoryInterface::class
        );

        $this->service = new ShortUrlRedirectService(
            $this->repository,
        );
    }

    public function test_it_returns_the_original_url_for_an_active_short_url(): void
    {
        $shortUrl = new ShortUrl();

        $shortUrl->original_url = 'https://example.com';
        $shortUrl->short_code = 'ABC123';
        $shortUrl->clicks = 0;
        $shortUrl->is_active = true;

        $this->repository
            ->expects($this->once())
            ->method('findByShortCode')
            ->with('ABC123')
            ->willReturn($shortUrl);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($shortUrl)
            ->willReturn($shortUrl);

        $result = $this->service->redirect('ABC123');

        $this->assertSame(
            'https://example.com',
            $result
        );

        $this->assertSame(1, $shortUrl->clicks);
    }

    public function test_it_throws_an_exception_when_short_url_does_not_exist(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('findByShortCode')
            ->with('NOTFOUND')
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Short URL not found.');

        $this->service->redirect('NOTFOUND');
    }

    public function test_it_throws_an_exception_when_short_url_is_inactive(): void
    {
        $shortUrl = new ShortUrl();

        $shortUrl->original_url = 'https://example.com';
        $shortUrl->short_code = 'ABC123';
        $shortUrl->clicks = 0;
        $shortUrl->is_active = false;

        $this->repository
            ->expects($this->once())
            ->method('findByShortCode')
            ->with('ABC123')
            ->willReturn($shortUrl);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Short URL is inactive.');

        $this->service->redirect('ABC123');
    }
}
