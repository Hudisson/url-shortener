<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EmailVerificationController extends Controller
{
    public function create(): View
    {
        return view('auth.verify-email');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => [
                'required',
                'string',
                'size:6',
            ],
        ]);

        return redirect()
            ->route('verification.create')
            ->with('success', 'Código recebido com sucesso.');
    }
}
