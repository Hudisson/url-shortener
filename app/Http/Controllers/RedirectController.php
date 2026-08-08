<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ShortUrlRedirectService;
use Illuminate\Http\RedirectResponse;

final class RedirectController extends Controller
{
    public function __construct(
        private readonly ShortUrlRedirectService $service,
    ) {}

    public function __invoke(string $shortCode): RedirectResponse
    {
        $originalUrl = $this->service->redirect($shortCode);

        return redirect()->away($originalUrl);
    }
}
