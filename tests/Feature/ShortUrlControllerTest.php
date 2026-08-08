<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ShortUrlControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_short_url(): void
    {
        $response = $this->postJson('/shorten', [
            'url' => 'https://example.com',
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'short_code',
            'original_url',
        ]);

        $response->assertJson([
            'original_url' => 'https://example.com',
        ]);

        $this->assertDatabaseHas('short_urls', [
            'original_url' => 'https://example.com',
        ]);
    }

    public function test_it_does_not_create_a_short_url_when_url_is_invalid(): void
    {
        $response = $this->postJson('/shorten', [
            'url' => 'invalid-url',
        ]);

        $response->assertStatus(500);

        $this->assertDatabaseCount('short_urls', 0);
    }
}
