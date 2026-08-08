<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ShortUrlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ShortUrlController extends Controller
{
    public function __construct(
        private readonly ShortUrlService $service,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $shortUrl = $this->service->create(
            $request->input('url')
        );

        return response()->json([
            'short_code' => $shortUrl->short_code,
            'original_url' => $shortUrl->original_url,
        ], 201);
    }
}
