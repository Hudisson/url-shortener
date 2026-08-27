<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Mail;


final class EmailVerificationService
{
    private const CODE_LENGTH = 6;

    private const EXPIRATION_MINUTES = 15;

    public function createFor(User $user): string
    {
        $code = $this->generateCode();

        // Guarda o Hash do código gerado no banco de dados.
        $user->emailVerificationCodes()->create([
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::EXPIRATION_MINUTES),
        ]);

        // Envia o código para o e-mail do usuário.
        Mail::to($user->email)->send(
            new EmailVerificationMail($user, $code)
        );

        return $code;
    }

    // Método para gerar o código (aleatório)
    private function generateCode(): string
    {
        $min = 10 ** (self::CODE_LENGTH - 1);
        $max = (10 ** self::CODE_LENGTH) - 1;

        return (string) random_int($min, $max);
    }


    // Método para veficar o código de validação
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

    // Método para reenvio de código de verificação
    public function resend(User $user): void
    {

        $lastCode = $user->emailVerificationCodes()->latest()->first();
        
        if ($lastCode !== null && $lastCode->created_at->addSeconds(60)->isFuture()) {
            throw new \RuntimeException('Aguarde 60 segundos antes de solicitar um novo código.');
        }

        $user->emailVerificationCodes()->delete();

        $code = $this->generateCode();

        $user->emailVerificationCodes()->create([
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::EXPIRATION_MINUTES),
        ]);

        Mail::to($user->email)->send(
            new EmailVerificationMail($user, $code)
        );
    }
}
