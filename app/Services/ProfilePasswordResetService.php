<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\ProfilePasswordResetCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class ProfilePasswordResetService
{
    private const CODE_LENGTH = 6;

    private const EXPIRATION_MINUTES = 15;

    private const RESEND_INTERVAL_SECONDS = 60;

    private const MAX_ATTEMPTS = 5;

    /**
     * Envia um código de confirmação para o usuário.
     * @param  array<string, mixed>  $data
     */
    public function sendCode(User $user, array $data): void
    {
        $validated = Validator::make($data, [
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        // Verifica se há um código anterior e se o intervalo de reenvio ainda não expirou
        $previousCode = $user->profilePasswordResetCode()->first();

        if (
            $previousCode !== null &&
            $previousCode->created_at->addSeconds(self::RESEND_INTERVAL_SECONDS)->isFuture()
        ) {
            // Lança uma exceção de validação caso o intervalo de reenvio ainda não tenha expirado
            throw ValidationException::withMessages([
                'new_password' => 'Aguarde 60 segundos antes de solicitar um novo código.',
            ]);
        }

        $code = $this->generateCode(); // Gera um novo código

        $user->profilePasswordResetCode()->updateOrCreate([], [
            'code' => Hash::make($code),
            'new_password_hash' => Hash::make($validated['new_password']),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(self::EXPIRATION_MINUTES),
        ]);

        Mail::to($user->email)->send(
            new ProfilePasswordResetCodeMail($user, $code),
        );
    }

    /**
     * Verifica o código de confirmação do usuário.
     * @param  array<string, mixed>  $data
     */
    public function verifyCode(User $user, array $data): void
    {
        $validated = Validator::make($data, [
            'code' => ['required', 'string', 'digits:'.self::CODE_LENGTH],
        ])->validate();

        $verified = DB::transaction(function () use ($user, $validated): bool {
            $resetCode = $user->profilePasswordResetCode()
                ->lockForUpdate()
                ->first();

            if ($resetCode === null) {
                return false;
            }

            if ($resetCode->expires_at->isPast()) {
                $resetCode->delete();

                return false;
            }

            if ($resetCode->attempts >= self::MAX_ATTEMPTS) {
                return false;
            }

            if (! Hash::check($validated['code'], $resetCode->code)) {
                $resetCode->attempts++;
                $resetCode->save();

                return false;
            }

            $user->password = $resetCode->new_password_hash;
            $user->save();
            $resetCode->delete();

            return true;
        });

        if (! $verified) {
            throw ValidationException::withMessages([
                'code' => 'O código informado é inválido ou expirou. Solicite um novo código para continuar.',
            ]);
        }
    }

    private function generateCode(): string
    {
        $min = 10 ** (self::CODE_LENGTH - 1);
        $max = (10 ** self::CODE_LENGTH) - 1;

        return (string) random_int($min, $max);
    }
}
