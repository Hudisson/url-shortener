<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;


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

    if($verificationCode === null){
        return false;
    }

    if($verificationCode->expires_at->isPast()){
        return false;
    }

    return Hash::check(
        $code,
        $verificationCode->code
    );
  }

}
