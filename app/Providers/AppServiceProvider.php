<?php

declare(strict_types=1);

namespace App\Providers;

use App\Logging\Logger;
use App\Logging\LoggerInterface;
use Illuminate\Support\ServiceProvider;
use App\Support\Contracts\ShortCodeGeneratorInterface;
use App\Support\Generators\ShortCodeGenerator;
use App\Validation\Contracts\UrlValidatorInterface;
use App\Validation\UrlValidator;
use App\Repositories\Contracts\ShortUrlRepositoryInterface;
use App\Repositories\ShortUrlRepository;
use App\Support\Contracts\UniqueShortCodeGeneratorInterface;
use App\Services\UniqueShortCodeGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            LoggerInterface::class,
            Logger::class,
        );

        $this->app->singleton(
            ShortCodeGeneratorInterface::class,
            ShortCodeGenerator::class,
        );

        $this->app->singleton(
            UrlValidatorInterface::class,
            UrlValidator::class,
        );

        $this->app->singleton(
            ShortUrlRepositoryInterface::class,
            ShortUrlRepository::class,
        );


        $this->app->singleton(
            UniqueShortCodeGeneratorInterface::class,
            UniqueShortCodeGenerator::class,
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
