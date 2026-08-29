<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

final class EmailVerificationController extends Controller
{
    public function __construct(
        private readonly EmailVerificationService $emailVerificationService,
    ) {}

    /**
     * Exibe a página de verificação de e-mail.
     */
    public function create(): View
    {
        return view('auth.verify-email');
    }

    /**
     * Verifica o código informado pelo usuário.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'code' => [
                'required',
                'string',
                'size:6',
            ],
        ]);

        $isValid = $this->emailVerificationService->verifyByEmail(
            $validated['email'],
            $validated['code'],
        );

        if (! $isValid) {
            return back()
                ->withErrors([
                    'code' => 'Código inválido ou expirado.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        return redirect()
            ->route('verification.create')
            ->with(
                'success',
                'Conta verificada com sucesso.'
            );
    }

    /**
     * Solicita o reenvio do código de verificação.
     */
    public function resend(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        try {
            $this->emailVerificationService->resendByEmail(
                $validated['email']
            );
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('verification.create')
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }

        return redirect()
            ->route('verification.create')
            ->with(
                'success',
                'Se existir uma conta associada a este e-mail, um novo código de verificação será enviado.'
            );
    }
}
