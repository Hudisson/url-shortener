<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {}

    public function show(Request $request): View
    {
        return view('profile', [
            'user' => $request->user(),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function delete(): View
    {
        return view('profile.delete');
    }

    public function update(Request $request): RedirectResponse
    {
        $this->profileService->update(
            $request->user(),
            $request->only(['name', 'email', 'password']),
        );

        return redirect()
            ->route('profile')
            ->with('success', 'Perfil atualizado com sucesso.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->profileService->deleteAccount(
            $request->user(),
            $request->only('password'),
        );

        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
