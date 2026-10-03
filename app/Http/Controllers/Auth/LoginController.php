<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\EmailNotVerifiedException;
use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use App\Services\LoginService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

final class LoginController extends Controller
{
    public function __construct(
        private readonly LoginService $service,
        private readonly EmailVerificationService $emailVerificationService,
    ) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {
            $user = $this->service->authenticate(
                $validated['email'],
                $validated['password'],
            );

            $request->session()->regenerate();

            auth()->guard()->login($user);

            return redirect()->route('dashboard');

        } catch (EmailNotVerifiedException) {
            $request->session()->put('email_verification_email', $validated['email']);

            try {
                $this->emailVerificationService->resendByEmail($validated['email']);
            } catch (RuntimeException $exception) {
                return redirect()
                    ->route('verification.create')
                    ->with('error', $exception->getMessage());
            }

            return redirect()
                ->route('verification.create')
                ->with('success', 'Enviamos um código de verificação para o seu e-mail.');
        } catch (RuntimeException $exception) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => $exception->getMessage(),
                ]);
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        auth()->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
