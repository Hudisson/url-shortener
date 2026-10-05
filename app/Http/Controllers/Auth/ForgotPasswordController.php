<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ForgotPasswordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Classe para redefinir a senha do usuário caso o mesmo esqueça a senha no momento de fazer login
 */
final class ForgotPasswordController extends Controller
{
    public function __construct(
        private readonly ForgotPasswordService $forgotPasswordService,
    ) {}

    public function create(): View
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $this->forgotPasswordService->sendResetLink(
            $request->only('email'),
        );

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Verifique seu e-mail. Se houver uma conta com esse e-mail, enviaremos um link para redefinir sua senha.',
            );
    }

    public function edit(Request $request, string $token): View
    {
        return view('auth.passwords.reset', [
            'email' => $request->query('email'),
            'token' => $token,
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $isReset = $this->forgotPasswordService->resetPassword(
            $request->only(['token', 'email', 'password', 'password_confirmation']),
        );

        if (! $isReset) {
            return back()->withErrors([
                'email' => 'O link de redefinição é inválido ou expirou. Solicite um novo link.',
            ]);
        }

        return redirect()
            ->route('login')
            ->with('success', 'Sua senha foi redefinida com sucesso. Você já pode entrar na sua conta.');
    }
}
