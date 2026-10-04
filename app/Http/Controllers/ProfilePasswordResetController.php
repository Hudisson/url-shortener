<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ProfilePasswordResetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ProfilePasswordResetController extends Controller
{
    // Injeção de dependência
    public function __construct(
        private readonly ProfilePasswordResetService $passwordResetService,
    ) {}

    // Método para exibir o formulário de redefinição de senha
    public function create(): View
    {
        return view('profile.password.edit');
    }

    // Método para enviar o código de confirmação
    public function sendCode(Request $request): RedirectResponse
    {
        $this->passwordResetService->sendCode(
            $request->user(),
            $request->only(['new_password', 'new_password_confirmation']),
        );

        return redirect()
            ->route('profile.password.confirm')
            ->with('success', 'Enviamos um código de confirmação para o e-mail da sua conta.');
    }

    // Método para exibir o formulário de confirmação
    public function confirm(Request $request): View
    {
        return view('profile.password.confirm', [
            'email' => $request->user()->email,
        ]);
    }

    // Método para verificar o código de confirmação
    public function verify(Request $request): RedirectResponse
    {
        $this->passwordResetService->verifyCode(
            $request->user(),
            $request->only('code'),
        );

        return redirect()
            ->route('profile')
            ->with('success', 'Senha redefinida com sucesso.');
    }
}
