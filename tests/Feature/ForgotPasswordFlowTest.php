<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\PasswordResetLinkNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

final class ForgotPasswordFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_login_page_links_to_the_forgot_password_form(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('href="'.route('password.request').'"', false);
        $response->assertSee('Esqueci minha senha');
    }

    public function test_forgot_password_page_has_only_an_email_input(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
        $response->assertSee('name="email"', false);
        $response->assertDontSee('name="password"', false);
    }

    public function test_existing_account_receives_a_reset_link(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas(
            'success',
            'Se houver uma conta com esse e-mail, enviaremos um link para redefinir sua senha.',
        );

        Notification::assertSentTo(
            $user,
            PasswordResetLinkNotification::class,
            function (PasswordResetLinkNotification $notification) use ($user): bool {
                $mail = $notification->toMail($user);
                $renderedMail = $mail->render();

                return str_contains(
                    $renderedMail,
                    route('password.reset', [
                        'token' => $notification->token,
                        'email' => $user->email,
                    ]),
                );
            },
        );

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_unknown_email_receives_the_same_confirmation_without_sending_mail(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'unknown@example.com',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas(
            'success',
            'Se houver uma conta com esse e-mail, enviaremos um link para redefinir sua senha.',
        );
        Notification::assertNothingSent();
    }

    public function test_reset_link_opens_form_with_email_and_token(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));

        $response->assertOk();
        $response->assertSee('name="password"', false);
        $response->assertSee('name="password_confirmation"', false);
        $response->assertSee('name="token" value="'.$token.'"', false);
        $response->assertSee('value="'.$user->email.'"', false);
    }

    public function test_valid_reset_link_updates_password_and_consumes_token(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'nova-senha-segura',
            'password_confirmation' => 'nova-senha-segura',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas(
            'success',
            'Sua senha foi redefinida com sucesso. Você já pode entrar na sua conta.',
        );
        $this->assertTrue(Hash::check('nova-senha-segura', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_reset_rejects_password_confirmation_that_does_not_match(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->from(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]))->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'nova-senha-segura',
            'password_confirmation' => 'outra-senha',
        ]);

        $response->assertRedirect(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));
        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_invalid_reset_token_does_not_change_password(): void
    {
        $user = User::factory()->create();

        $response = $this->from(route('password.reset', [
            'token' => 'invalid-token',
            'email' => $user->email,
        ]))->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'nova-senha-segura',
            'password_confirmation' => 'nova-senha-segura',
        ]);

        $response->assertRedirect(route('password.reset', [
            'token' => 'invalid-token',
            'email' => $user->email,
        ]));
        $response->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
