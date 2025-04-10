<?php

namespace App\Providers;

use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;
use App\Services\ExchangeRate\ExchangeRateService;
use App\Services\ExchangeRate\ExchangeRateServiceInterface;
use App\Services\File\FileService;
use App\Services\File\FileServiceInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repositories
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        // Services
        $this->app->bind(ExchangeRateServiceInterface::class, ExchangeRateService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(FileServiceInterface::class, FileService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }

    public function boot(): void
    {
        // Define the 'login' rate limiter
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());

        });
    }
}
