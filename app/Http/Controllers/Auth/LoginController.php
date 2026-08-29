<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

final class LoginController extends Controller
{
    public function __construct(
        private readonly LoginService $service
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

            auth()->login($user);

            return redirect()->route('dashboard');
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
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
