<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

final readonly class RegisterService
{

    public function __construct(
        private EmailVerificationService $emailVerificationService
    ){}

    public function create(string $name, string $email, string $password): User
    {
        $user =  User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $this->emailVerificationService->createFor($user);

        return $user;
    }
}
