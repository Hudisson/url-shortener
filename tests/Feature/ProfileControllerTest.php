<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_authenticated_user_can_open_the_profile_edit_form(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee('Editar perfil');
        $response->assertSee('value="'.$user->name.'"', false);
        $response->assertSee('value="'.$user->email.'"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('autocomplete="current-password"', false);
        $response->assertSee('data-target="password"', false);
        $response->assertSee('class="password-toggle"', false);
    }

    public function test_profile_success_message_has_a_close_button(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['success' => 'Perfil atualizado com sucesso.'])
            ->get(route('profile'));

        $response->assertOk();
        $response->assertSee('Perfil atualizado com sucesso.');
        $response->assertSee('data-dismiss-alert', false);
        $response->assertSee('aria-label="Fechar aviso"', false);
    }

    public function test_user_can_update_name_and_email(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nome atualizado',
            'email' => 'atualizado@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('profile'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome atualizado',
            'email' => 'atualizado@example.com',
            'email_verified_at' => null,
        ]);
    }

    public function test_user_can_keep_their_current_email_and_verification_status(): void
    {
        $verifiedAt = now();
        /** @var User $user */
        $user = User::factory()->create([
            'email_verified_at' => $verifiedAt,
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nome atualizado',
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('profile'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome atualizado',
            'email' => $user->email,
            'email_verified_at' => $verifiedAt->toDateTimeString(),
        ]);
    }

    public function test_user_cannot_update_to_an_email_already_in_use(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $anotherUser */
        $anotherUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => 'Nome atualizado',
                'email' => $anotherUser->email,
                'password' => 'password',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_user_cannot_update_profile_with_an_incorrect_password(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => 'Nome atualizado',
                'email' => 'atualizado@example.com',
                'password' => 'senha-incorreta',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHasErrors('password');
        $response->assertSessionHasErrors([
            'password' => 'A senha atual está incorreta.',
        ]);
        $this->get(route('profile.edit'))
            ->assertSee('A senha atual está incorreta.')
            ->assertSee('data-dismiss-alert', false);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_user_must_provide_their_password_to_update_profile(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => 'Nome atualizado',
                'email' => 'atualizado@example.com',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_authenticated_user_can_open_the_account_deletion_form(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.delete'));

        $response->assertOk();
        $response->assertSee('Excluir conta');
        $response->assertSee('name="password"', false);
        $response->assertDontSee('name="password_confirmation"', false);
        $response->assertSee('action="'.route('profile.destroy').'"', false);
    }

    public function test_account_deletion_deactivates_only_the_users_urls_and_logs_them_out(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $anotherUser */
        $anotherUser = User::factory()->create();

        $userUrl = ShortUrl::query()->create([
            'user_id' => $user->id,
            'original_url' => 'https://example.com/user',
            'short_code' => 'user-url',
            'clicks' => 2,
            'is_active' => true,
        ]);
        $anotherUserUrl = ShortUrl::query()->create([
            'user_id' => $anotherUser->id,
            'original_url' => 'https://example.com/another',
            'short_code' => 'another-url',
            'clicks' => 3,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertGuest();
        $this->assertDatabaseHas('short_urls', [
            'id' => $userUrl->id,
            'is_active' => false,
        ]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseHas('short_urls', [
            'id' => $userUrl->id,
            'user_id' => null,
            'is_active' => false,
        ]);
        $this->assertDatabaseHas('short_urls', [
            'id' => $anotherUserUrl->id,
            'user_id' => $anotherUser->id,
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('users', ['id' => $anotherUser->id]);
    }

    public function test_account_is_not_deleted_when_password_is_incorrect(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $shortUrl = ShortUrl::query()->create([
            'user_id' => $user->id,
            'original_url' => 'https://example.com/user',
            'short_code' => 'user-url',
            'clicks' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->from(route('profile.delete'))
            ->delete(route('profile.destroy'), [
                'password' => 'senha-incorreta',
            ]);

        $response->assertRedirect(route('profile.delete'));
        $response->assertSessionHasErrors('password');
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('short_urls', [
            'id' => $shortUrl->id,
            'user_id' => $user->id,
            'is_active' => true,
        ]);
    }
}
