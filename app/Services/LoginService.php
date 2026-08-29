<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class LoginService
{

    public function authenticate(string $email, string $password): User
    {

        // Faz a busca do e-mail usuário no banco de dodos.
        $user = User::query()->where('email',$email)->first();

        // Se não encontrar o email, retorna uma exceção.
        if ($user === null) {
            throw new \RuntimeException('E-mail ou senha inválidos.');
        }

        if (!Hash::check($password, $user->password)) {
            throw new \RuntimeException('E-mail ou senha inválidos.');
        }

        if ($user->email_verified_at === null) {
            throw new \RuntimeException('E-mail ainda não verificado.');
        }

        return $user;
    }
}


