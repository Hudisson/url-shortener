<?php

namespace Tests\Unit\Services;

use App\Models\ShortUrl;
use App\Logging\LoggerInterface;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use App\Services\ShortUrlService;
use App\Support\Contracts\UniqueShortCodeGeneratorInterface;
use App\Validation\Contracts\UrlValidatorInterface;
use PHPUnit\Framework\TestCase;

class ShortUrlServiceTest extends TestCase
{

    private LoggerInterface $logger;
    private UniqueShortCodeGeneratorInterface $generator;
    private UrlValidatorInterface $validator;
    private ShortUrlRepositoryInterface $repository;

    private ShortUrlService $service;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(
            LoggerInterface::class
        );

        $this->generator = $this->createMock(
            UniqueShortCodeGeneratorInterface::class
        );

        $this->validator = $this->createMock(
            UrlValidatorInterface::class
        );

        $this->repository = $this->createMock(
            ShortUrlRepositoryInterface::class
        );

        $this->service = new ShortUrlService(
            $this->logger,
            $this->generator,
            $this->validator,
            $this->repository,
        );
    }

    public function test_it_creates_a_short_url_successfully(): void
    {
        $originalUrl = 'https://example.com';
        $shortCode = 'ABC123';

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($originalUrl);

        $this->generator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($shortCode);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(
                function (ShortUrl $shortUrl) use ($originalUrl, $shortCode): bool {
                    return $shortUrl->original_url === $originalUrl
                        && $shortUrl->short_code === $shortCode
                        && $shortUrl->clicks === 0
                        && $shortUrl->is_active === true;
                }
            ))
            ->willReturnCallback(
                function (ShortUrl $shortUrl): ShortUrl {
                    return $shortUrl;
                }
            );

        $result = $this->service->create($originalUrl);

        $this->assertInstanceOf(ShortUrl::class, $result);
        $this->assertSame($originalUrl, $result->original_url);
        $this->assertSame($shortCode, $result->short_code);
    }

    public function test_it_does_not_create_a_short_url_when_url_is_invalid(): void
    {
        $originalUrl = 'invalid-url';

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($originalUrl)
            ->willThrowException(
                new \InvalidArgumentException('The URL provided is invalid.')
            );

        $this->generator
            ->expects($this->never())
            ->method('generate');

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\InvalidArgumentException::class);

        $this->service->create($originalUrl);
    }

    public function test_it_propagates_repository_exception(): void
    {
        $originalUrl = 'https://example.com';
        $shortCode = 'ABC123';

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($originalUrl);

        $this->generator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($shortCode);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->willThrowException(
                new \RuntimeException('Unable to save short URL.')
            );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to save short URL.');

        $this->service->create($originalUrl);
    }
}
