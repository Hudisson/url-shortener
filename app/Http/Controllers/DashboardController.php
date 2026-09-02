<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

final class DashboardController extends Controller
{

    public function __construct(
        private readonly DashboardService $service,
    ) {}

    public function index(Request $request): View
    {

        $shortUrls = $this->service->getUserShortUrls(
            $request->user()->id,
        );

        return view('dashboard', [
            'shortUrls' => $shortUrls,
        ]);
    }

    public function metrics(Request $request, string $shortCode): View
    {
        $shortUrl = $this->service->getUserShortUrl(
            $shortCode,
            $request->user()->id,
        );


        if ($shortUrl === null) {
            abort(404);
        }

        return view('metrics', [
            'shortUrl' => $shortUrl,
        ]);
    }

    /**
     * Exclui uma URL encurtada pertencente ao usuário autenticado.
     */
    public function destroy(
        Request $request,
        string $shortCode
    ): RedirectResponse {
        $deleted = $this->service->deleteUserShortUrl(
            $shortCode,
            $request->user()->id,
        );

        if (!$deleted) {
            abort(404);
        }

        return redirect()->route('dashboard');
    }
}
