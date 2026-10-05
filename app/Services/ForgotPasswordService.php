<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

final class ForgotPasswordService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function sendResetLink(array $data): void
    {
        $validated = Validator::make($data, [
            'email' => ['required', 'email'],
        ])->validate();

        Password::sendResetLink($validated);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function resetPassword(array $data): bool
    {
        $validated = Validator::make($data, [
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        $status = Password::reset(
            $validated,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        return $status === Password::PASSWORD_RESET;
    }
}
