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
    public function create(Request $request): View
    {
        return view('auth.verify-email', [
            'email' => $request->session()->get('email_verification_email'),
        ]);
    }

    /**
     * Verifica o código informado pelo usuário.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'size:6',
            ],
        ]);

        $email = $request->session()->get('email_verification_email');

        if (! is_string($email) || $email === '') {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Informe seu e-mail para continuar a verificação.',
                ]);
        }

        $isValid = $this->emailVerificationService->verifyByEmail(
            $email,
            $validated['code'],
        );

        if (! $isValid) {
            return back()
                ->withErrors([
                    'code' => 'Código inválido ou expirado.',
                ])
                ->withInput();
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
        $email = $request->session()->get('email_verification_email');

        if (! is_string($email) || $email === '') {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Informe seu e-mail para continuar a verificação.',
                ]);
        }

        try {
            $this->emailVerificationService->resendByEmail(
                $email
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
                'Se existir uma conta associada a este e-mail, um código de verificação será enviado.'
            );
    }
}
