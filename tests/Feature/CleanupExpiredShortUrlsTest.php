<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ShortUrl;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CleanupExpiredShortUrlsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_only_short_urls_expired_at_or_before_the_run_time(): void
    {
        $this->travelTo(Carbon::parse('2026-10-08 10:00:00'));

        $expiredUrl = $this->createShortUrl('EXPIRED');
        $equalExpiryUrl = $this->createShortUrl('EQUAL');
        $futureUrl = $this->createShortUrl('FUTURE');

        $expiredUrl->expires_at = now()->subSecond();
        $expiredUrl->save();

        $equalExpiryUrl->expires_at = now();
        $equalExpiryUrl->save();

        $futureUrl->expires_at = now()->addSecond();
        $futureUrl->save();

        $this->artisan('short-urls:cleanup-expired')
            ->expectsOutput('Expired short URLs deleted: 2.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('short_urls', ['short_code' => 'EXPIRED']);
        $this->assertDatabaseMissing('short_urls', ['short_code' => 'EQUAL']);
        $this->assertDatabaseHas('short_urls', ['short_code' => 'FUTURE']);
    }

    private function createShortUrl(string $shortCode): ShortUrl
    {
        return ShortUrl::query()->create([
            'original_url' => 'https://example.com/'.$shortCode,
            'short_code' => $shortCode,
            'clicks' => 0,
            'is_active' => true,
        ]);
    }
}
