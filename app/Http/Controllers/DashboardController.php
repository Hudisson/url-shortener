<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{

    public function __construct(
        private readonly DashboardService $service,
    ){}
    public function index(Request $request): View
    {

        $shortUrls = $this->service->getUserShortUrls(
            $request->user()->id,
        );

        return view('dashboard',[
            'shortUrls' => $shortUrls,
        ]);
    }
}
