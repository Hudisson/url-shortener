<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ShortUrlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShortUrlController extends Controller
{
    public function __construct(
        private readonly ShortUrlService $service,
    ) {}

    public function store(Request $request): JsonResponse|View
    {
        $shortUrl = $this->service->create(
            $request->input('url')
        );

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
}
