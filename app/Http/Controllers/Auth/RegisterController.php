<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\RegisterService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class RegisterController extends Controller
{

    public function __construct(
        private readonly RegisterService $service
    ){}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->service->create(
            $validated['name'],
            $validated['email'],
            $validated['password'],
        );

        return redirect()
            ->route('register')
            ->with('success', 'Cadastro realizado com sucesso. Verifique seu e-mail para validar sua conta.');
    }
}
