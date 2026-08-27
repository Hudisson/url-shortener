<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EmailVerificationController extends Controller
{
    public function __construct(
        private readonly EmailVerificationService $emailVerificationService,
    ) {}

    public function create(): View
    {
        return view('auth.verify-email');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'size:6',
            ],
        ]);

        $userId = session('email_verification_user_id');

        if ($userId === null) {
            return redirect()
                ->route('register')
                ->with(
                    'error',
                    'Nenhuma solicitação de verificação foi encontrada.'
                );
        }

        $user = User::find((int) $userId);

        if ($user === null) {
            session()->forget('email_verification_user_id');

            return redirect()
                ->route('register')
                ->with(
                    'error',
                    'Usuário não encontrado.'
                );
        }

        $isValid = $this->emailVerificationService->verify(
            $user,
            $validated['code'],
        );

        if (! $isValid) {
            return back()
                ->withErrors([
                    'code' => 'Código inválido ou expirado.',
                ]);
        }

        $user->update([
            'email_verified_at' => now(),
        ]);

        session()->forget('email_verification_user_id');

        return redirect()
            ->route('verification.create')
            ->with(
                'success',
                'Conta verificada com sucesso.'
            );
    }

    // Método de reenvio de código de verificação
    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('email_verification_user_id');

        if ($userId === null) {
            return redirect()->route('register')->with('error', 'Nenhuma conta aguardando verificação.');
        }

        $user = User::find($userId);

        if ($user === null) {
            return redirect()->route('register')->with('error', 'Usuário não encontrado.');
        }

        if ($user->email_verified_at !== null) {
            return redirect()->route('register')->with('success', 'Sua conta já foi verificada.');
        }

        try {
            $this->emailVerificationService->resend($user);
        } catch (\RuntimeException $exception) {
            return redirect()
                ->route('verification.create')
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('verification.create')
            ->with('success', 'Um novo código de verificação foi enviado para seu e-mail.');
    }
}
