<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\EmailVerificationMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

final class EmailVerificationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_registration_opens_verification_with_read_only_email(): void
    {
        Mail::fake();

        $response = $this->post(route('register.store'), [
            'name' => 'Pending User',
            'email' => 'pending@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verification.create'));
        $response->assertSessionHas('email_verification_email', 'pending@example.com');

        $page = $this->get(route('verification.create'));
        $page->assertOk();
        $page->assertSee('value="pending@example.com"', false);
        $page->assertSee('readonly', false);
        $page->assertDontSee('placeholder="Digite o e-mail utilizado no cadastro"', false);
    }

    public function test_login_resends_the_active_code_for_an_unverified_account(): void
    {
        Mail::fake();

        $user = $this->createUnverifiedUser();
        $code = '123456';
        $user->emailVerificationCodes()->create([
            'code' => Hash::make($code),
            'code_encrypted' => Crypt::encryptString($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('verification.create'));
        $response->assertSessionHas('email_verification_email', $user->email);
        Mail::assertSent(EmailVerificationMail::class, fn (EmailVerificationMail $mail): bool => $mail->code === $code);
        $this->assertDatabaseCount('email_verification_codes', 1);
    }

    public function test_login_generates_a_new_code_when_the_previous_code_expired(): void
    {
        Mail::fake();

        $user = $this->createUnverifiedUser();
        $verificationCode = $user->emailVerificationCodes()->create([
            'code' => Hash::make('123456'),
            'code_encrypted' => Crypt::encryptString('123456'),
            'expires_at' => now()->subMinute(),
        ]);
        $verificationCode->created_at = now()->subMinutes(16);
        $verificationCode->save();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('verification.create'));
        Mail::assertSent(EmailVerificationMail::class, function (EmailVerificationMail $mail): bool {
            return $mail->code !== '123456'
                && strlen($mail->code) === 6
                && ctype_digit($mail->code);
        });

        $this->assertDatabaseCount('email_verification_codes', 1);
        $savedCode = $user->emailVerificationCodes()->firstOrFail();
        $sentCode = Mail::sent(EmailVerificationMail::class)->first()->code;
        $this->assertSame($sentCode, Crypt::decryptString($savedCode->code_encrypted));
        $this->assertTrue(Hash::check($sentCode, $savedCode->code));
    }

    public function test_verification_uses_the_email_from_the_session(): void
    {
        $user = $this->createUnverifiedUser();
        $code = '123456';
        $user->emailVerificationCodes()->create([
            'code' => Hash::make($code),
            'code_encrypted' => Crypt::encryptString($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->withSession([
            'email_verification_email' => $user->email,
        ])->post(route('verification.store'), [
            'email' => 'another@example.com',
            'code' => $code,
        ]);

        $response->assertRedirect(route('verification.create'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    private function createUnverifiedUser(): User
    {
        return User::factory()->create([
            'email' => 'pending@example.com',
            'password' => 'password123',
            'email_verified_at' => null,
        ]);
    }
}
