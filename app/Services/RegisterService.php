<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

final readonly class RegisterService
{
    public function create(string $name, string $email, string $password): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);
    }
}
