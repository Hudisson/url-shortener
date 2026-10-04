<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ProfilePasswordResetCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

final class ProfilePasswordResetFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_authenticated_user_can_open_the_password_reset_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.password.edit'));

        $response->assertOk();
        $response->assertSee('Redefinir senha');
        $response->assertSee('name="new_password"', false);
        $response->assertSee('name="new_password_confirmation"', false);
        $response->assertSee('data-target="new_password"', false);
        $response->assertSee('data-target="new_password_confirmation"', false);
        $response->assertSee('class="password-toggle"', false);
    }

    public function test_sending_reset_request_emails_a_code_without_changing_the_password(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('profile.password.send-code'), [
            'new_password' => 'nova-senha-123',
            'new_password_confirmation' => 'nova-senha-123',
        ]);

        $response->assertRedirect(route('profile.password.confirm'));
        $this->assertTrue(Hash::check('password', $user->fresh()->password));

        $resetCode = $user->profilePasswordResetCode()->firstOrFail();
        Mail::assertSent(ProfilePasswordResetCodeMail::class, function (ProfilePasswordResetCodeMail $mail) use ($user, $resetCode): bool {
            return $mail->hasTo($user->email)
                && Hash::check($mail->code, $resetCode->code)
                && strlen($mail->code) === 6
                && ctype_digit($mail->code)
                && str_contains($mail->render(), $mail->code);
        });
        $this->assertTrue(Hash::check('nova-senha-123', $resetCode->new_password_hash));
        $this->get(route('profile.password.confirm'))
            ->assertOk()
            ->assertSee($user->email)
            ->assertSee('name="code"', false);
    }

    public function test_code_confirmation_changes_password_only_after_a_valid_code(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('profile.password.send-code'), [
            'new_password' => 'nova-senha-123',
            'new_password_confirmation' => 'nova-senha-123',
        ]);

        $mail = Mail::sent(ProfilePasswordResetCodeMail::class)->first();
        $response = $this->post(route('profile.password.verify'), [
            'code' => $mail->code,
        ]);

        $response->assertRedirect(route('profile'));
        $this->assertTrue(Hash::check('nova-senha-123', $user->fresh()->password));
        $this->assertDatabaseMissing('profile_password_reset_codes', [
            'user_id' => $user->id,
        ]);
    }

    public function test_invalid_code_does_not_change_password_and_counts_attempts(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('profile.password.send-code'), [
            'new_password' => 'nova-senha-123',
            'new_password_confirmation' => 'nova-senha-123',
        ]);

        $response = $this->from(route('profile.password.confirm'))
            ->post(route('profile.password.verify'), [
                'code' => '000000',
            ]);

        $response->assertRedirect(route('profile.password.confirm'));
        $response->assertSessionHasErrors('code');
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
        $this->assertDatabaseHas('profile_password_reset_codes', [
            'user_id' => $user->id,
            'attempts' => 1,
        ]);
    }

    public function test_expired_code_cannot_reset_password(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('profile.password.send-code'), [
            'new_password' => 'nova-senha-123',
            'new_password_confirmation' => 'nova-senha-123',
        ]);

        $user->profilePasswordResetCode()->update([
            'expires_at' => now()->subMinute(),
        ]);

        $response = $this->from(route('profile.password.confirm'))
            ->post(route('profile.password.verify'), [
                'code' => Mail::sent(ProfilePasswordResetCodeMail::class)->first()->code,
            ]);

        $response->assertRedirect(route('profile.password.confirm'));
        $response->assertSessionHasErrors('code');
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
        $this->assertDatabaseMissing('profile_password_reset_codes', [
            'user_id' => $user->id,
        ]);
    }

    public function test_five_invalid_codes_lock_the_reset_request(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('profile.password.send-code'), [
            'new_password' => 'nova-senha-123',
            'new_password_confirmation' => 'nova-senha-123',
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('profile.password.verify'), [
                'code' => '000000',
            ])->assertSessionHasErrors('code');
        }

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
        $this->assertDatabaseHas('profile_password_reset_codes', [
            'user_id' => $user->id,
            'attempts' => 5,
        ]);

        $this->post(route('profile.password.send-code'), [
            'new_password' => 'outra-senha-123',
            'new_password_confirmation' => 'outra-senha-123',
        ])->assertSessionHasErrors('new_password');

        Mail::assertSentCount(1);
    }

    public function test_reset_form_rejects_mismatched_password_confirmation(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('profile.password.edit'))
            ->post(route('profile.password.send-code'), [
                'new_password' => 'nova-senha-123',
                'new_password_confirmation' => 'senha-diferente',
            ]);

        $response->assertRedirect(route('profile.password.edit'));
        $response->assertSessionHasErrors('new_password');
        Mail::assertNothingSent();
        $this->assertDatabaseCount('profile_password_reset_codes', 0);
    }
}
