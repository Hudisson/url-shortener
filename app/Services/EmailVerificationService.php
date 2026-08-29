<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\EmailVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

final class EmailVerificationService
{
    private const CODE_LENGTH = 6;

    private const EXPIRATION_MINUTES = 15;

    private const RESEND_INTERVAL_SECONDS = 60;

    /**
     * Cria um código de verificação para o usuário
     * e envia o código por e-mail.
     */
    public function createFor(User $user): string
    {
        $code = $this->generateCode();

        $user->emailVerificationCodes()->create([
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::EXPIRATION_MINUTES),
        ]);

        Mail::to($user->email)->send(
            new EmailVerificationMail($user, $code)
        );

        return $code;
    }

    /**
     * Verifica o código informado pelo usuário.
     */
    public function verify(User $user, string $code): bool
    {
        $verificationCode = $user->emailVerificationCodes()
            ->latest()
            ->first();

        if ($verificationCode === null) {
            return false;
        }

        if ($verificationCode->expires_at->isPast()) {
            return false;
        }

        return Hash::check(
            $code,
            $verificationCode->code
        );
    }

    /**
     * Verifica o usuário através do e-mail e código informado.
     *
     * Retorna false quando o usuário não existe,
     * o código é inválido ou está expirado.
     */
    public function verifyByEmail(string $email, string $code): bool
    {
        $user = $this->findUserByEmail($email);

        if ($user === null) {
            return false;
        }

        if ($user->email_verified_at !== null) {
            return true;
        }

        if (! $this->verify($user, $code)) {
            return false;
        }

        $user->email_verified_at = now();
        $user->save();

        return true;
    }

    /**
     * Reenvia um novo código de verificação.
     */
    public function resendByEmail(string $email): void
    {
        $user = $this->findUserByEmail($email);

        /*
         * Não informar se o e-mail existe ou não.
         * Isso evita revelar quais e-mails possuem contas cadastradas.
         */
        if ($user === null) {
            return;
        }

        if ($user->email_verified_at !== null) {
            return;
        }

        $this->ensureResendAllowed($user);

        $this->deletePreviousCodes($user);

        $this->createFor($user);
    }

    /**
     * Localiza um usuário pelo e-mail.
     */
    private function findUserByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }

    /**
     * Verifica se o reenvio do código pode ser realizado.
     */
    private function ensureResendAllowed(User $user): void
    {
        $lastCode = $user->emailVerificationCodes()
            ->latest()
            ->first();

        if (
            $lastCode !== null &&
            $lastCode->created_at
                ->addSeconds(self::RESEND_INTERVAL_SECONDS)
                ->isFuture()
        ) {
            throw new RuntimeException(
                'Aguarde 60 segundos antes de solicitar um novo código.'
            );
        }
    }

    /**
     * Remove os códigos anteriores do usuário.
     */
    private function deletePreviousCodes(User $user): void
    {
        $user->emailVerificationCodes()->delete();
    }

    /**
     * Gera um código numérico aleatório.
     */
    private function generateCode(): string
    {
        $min = 10 ** (self::CODE_LENGTH - 1);
        $max = (10 ** self::CODE_LENGTH) - 1;

        return (string) random_int($min, $max);
    }
}
