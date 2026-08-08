<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use App\Services\UniqueShortCodeGenerator;
use App\Support\Contracts\ShortCodeGeneratorInterface;
use PHPUnit\Framework\TestCase;

final class UniqueShortCodeGeneratorTest extends TestCase
{
    private ShortCodeGeneratorInterface $generator;
    private ShortUrlRepositoryInterface $repository;
    private UniqueShortCodeGenerator $uniqueGenerator;

    protected function setUp(): void
    {
        $this->generator = $this->createMock(
            ShortCodeGeneratorInterface::class
        );

        $this->repository = $this->createMock(
            ShortUrlRepositoryInterface::class
        );

        $this->uniqueGenerator = new UniqueShortCodeGenerator(
            $this->generator,
            $this->repository,
        );
    }

    public function test_it_generates_a_unique_short_code_when_code_is_available(): void
    {
        $this->generator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('ABC123');

        $this->repository
            ->expects($this->once())
            ->method('existsByShortCode')
            ->with('ABC123')
            ->willReturn(false);

        $result = $this->uniqueGenerator->generate();

        $this->assertSame('ABC123', $result);
    }

    public function test_it_generates_a_new_code_when_first_code_exists(): void
    {
        $this->generator
            ->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls(
                'ABC123',
                'XYZ789'
            );

        $this->repository
            ->expects($this->exactly(2))
            ->method('existsByShortCode')
            ->willReturnOnConsecutiveCalls(
                true,
                false
            );

        $result = $this->uniqueGenerator->generate();

        $this->assertSame('XYZ789', $result);
    }

    public function test_it_throws_exception_when_cannot_generate_unique_code(): void
    {
        $this->generator
            ->expects($this->exactly(10))
            ->method('generate')
            ->willReturn('ABC123');

        $this->repository
            ->expects($this->exactly(10))
            ->method('existsByShortCode')
            ->willReturn(true);

        $this->expectException(\RuntimeException::class);

        $this->uniqueGenerator->generate();
    }
}
