<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ShortUrlRedirectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_redirects_to_the_original_url(): void
    {
        ShortUrl::create([
            'original_url' => 'https://example.com',
            'short_code' => 'ABC123',
            'clicks' => 0,
            'is_active' => true,
        ]);

        $response = $this->get('/ABC123');

        $response->assertRedirect('https://example.com');

        $this->assertDatabaseHas('short_urls', [
            'short_code' => 'ABC123',
            'clicks' => 1,
        ]);
    }
}
