<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Generators;

use App\Support\Generators\ShortCodeGenerator;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;


final class ShortCodeGeneratorTest extends TestCase
{

    private ShortCodeGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new ShortCodeGenerator();
    }

    public function test_it_generates_a_short_code_with_default_length(): void
    {

        $code = $this->generator->generate();

        $this->assertSame(6, strlen($code));
    }

    public function test_it_generates_a_short_code_with_the_specified_length(): void
    {

        $code = $this->generator->generate(10);

        $this->assertSame(10, strlen($code));
    }

    public function test_it_throws_an_exception_when_length_is_less_than_one(): void
    {
        $generator = new ShortCodeGenerator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'The short code length must be greater than zero.'
        );

        $generator->generate(0);
    }

    public function test_it_generates_only_base62_characters(): void
    {
        $code = $this->generator->generate(100);

        $this->assertMatchesRegularExpression(
            '/^[A-Za-z0-9]+$/',
            $code
        );
    }
}
