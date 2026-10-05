<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RuntimeException;

final class ProfileService
{
    /**
     * Atualiza os dados do perfil após validar os campos e a senha atual.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(User $user, array $data): User
    {
        $validated = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user),
            ],
            'password' => ['required', 'current_password'],
        ])->validate();

        if ($validated['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ])->save();

        return $user;
    }

    /**
     * Desativa as URLs do usuário e exclui sua conta após validar a senha atual.
     *
     * @param  array<string, mixed>  $data
     */
    public function deleteAccount(User $user, array $data): void
    {
        Validator::make($data, [
            'password' => ['required', 'current_password'],
        ])->validate();

        DB::transaction(function () use ($user): void {
            ShortUrl::query()
                ->where('user_id', $user->id)
                ->update(['is_active' => false]);

            $deletedUsers = DB::table('users')
                ->where('id', $user->getKey())
                ->delete();

            if ($deletedUsers !== 1) {
                throw new RuntimeException('Não foi possível excluir a conta do usuário.');
            }
        });
    }
}
