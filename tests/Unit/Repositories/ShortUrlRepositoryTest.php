<?php

namespace Tests\Unit\Repositories;


use App\Models\ShortUrl;
use App\Repositories\ShortUrlRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// use PHPUnit\Framework\TestCase;


class ShortUrlRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_saves_a_short_url(): void
    {
        $repository = new ShortUrlRepository();

        $shortUrl = new ShortUrl();

        $shortUrl->original_url = 'https://example.com';
        $shortUrl->short_code = 'ABC123';
        $shortUrl->clicks = 0;
        $shortUrl->is_active = true;

        $result = $repository->save($shortUrl);

        $this->assertNotNull($result->id);
        $this->assertSame('https://example.com', $result->original_url);
        $this->assertSame('ABC123', $result->short_code);
    }

    public function test_it_checks_if_a_short_code_exists(): void
    {
        $repository = new ShortUrlRepository();

        $shortUrl = new ShortUrl();

        $shortUrl->original_url = 'https://example.com';
        $shortUrl->short_code = 'ABC123';
        $shortUrl->clicks = 0;
        $shortUrl->is_active = true;

        $shortUrl->save();

        $this->assertTrue(
            $repository->existsByShortCode('ABC123')
        );

        $this->assertFalse(
            $repository->existsByShortCode('XYZ789')
        );
    }

    public function test_it_finds_a_short_url_by_short_code(): void
    {
        $repository = new ShortUrlRepository();

        $shortUrl = new ShortUrl();

        $shortUrl->original_url = 'https://example.com';
        $shortUrl->short_code = 'ABC123';
        $shortUrl->clicks = 0;
        $shortUrl->is_active = true;

        $shortUrl->save();

        $result = $repository->findByShortCode('ABC123');

        $this->assertNotNull($result);
        $this->assertSame('https://example.com', $result->original_url);
        $this->assertSame('ABC123', $result->short_code);
    }

    public function test_it_returns_null_when_short_code_does_not_exist(): void
    {
        $repository = new ShortUrlRepository();

        $result = $repository->findByShortCode('NOTFOUND');

        $this->assertNull($result);
    }
}
