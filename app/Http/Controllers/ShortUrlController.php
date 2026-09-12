<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ShortUrlService;
use App\Services\DashboardService;
use App\Services\ShortCustomUrlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShortUrlController extends Controller
{
    public function __construct(
        private readonly ShortUrlService $service,
        private readonly ShortCustomUrlService $customService,
        private readonly DashboardService $dashboardService,
    ) {}

    public function store(Request $request): JsonResponse|View
    {

        $label = $request->user()
            ? $request->input('label')
            : null;

        $tipoUrl = $request->input('tipo_url', 'auto');

        if (! in_array($tipoUrl, ['auto', 'custom'], true)) {
            abort(422, 'Invalid URL type.');
        }

        if ($tipoUrl === 'custom') {

            // Verifica se há usuário logado
            if (! $request->user()) {
                abort(403, 'Authentication is required to create a custom URL.');
            }

            // Chamar o Sevice de URL customizada
            $shortUrl = $this->customService->create(
                $request->input('url'),
                $request->input('codigo_personalizado'),
                $request->user()->id, // Passa o ID do usuário logado
                $label,
            );

        } else {

            // Chamar o Sevice de URL automática (base62)
            $shortUrl = $this->service->create(
                $request->input('url'),
                $request->user()?->id, // Passa o ID do usuário logado, se for um visitante passa (null)
                $label,
            );
        }

        // Retorna a resposta em JSON se a requisição for feita por um cliente de API (Insomnia, Thunder Client e etc)
        if ($request->expectsJson()) {
            return response()->json([
                'short_code' => $shortUrl->short_code,
                'original_url' => $shortUrl->original_url,
            ], 201);
        }

        // Retorna a resposta em HTML se a requisição for feita por um Browser (Navegador de internet)
        return view('short-url.result', [
            'shortUrl' => $shortUrl,
        ]);
    }

    // Método que retorna a página de edição de URLs
    public function edit(Request $request, string $shortCode): View
    {
        $shortUrl = $this->dashboardService->getUserShortUrl(
            $shortCode,
            $request->user()->id,
        );

        return view('dashboard.edit', [
            'shortUrl' => $shortUrl,
        ]);
    }
}
