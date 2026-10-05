<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ShortUrlControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_short_url(): void
    {
        $this->travelTo(Carbon::parse('2026-08-29 20:53:16'));

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
            'created_at' => '2026-08-29 20:53:16',
            'expires_at' => '2027-08-29 20:53:16',
        ]);
    }

    public function test_it_sets_expiration_for_a_custom_short_url(): void
    {
        $this->travelTo(Carbon::parse('2026-08-29 20:53:16'));

        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/shorten', [
            'url' => 'https://example.com',
            'tipo_url' => 'custom',
            'codigo_personalizado' => 'meu-link',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('short_urls', [
            'short_code' => 'meu-link',
            'created_at' => '2026-08-29 20:53:16',
            'expires_at' => '2027-08-29 20:53:16',
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
