<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ErrorLogService;

class ErrorLogServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ErrorLogService::class, function ($app) {
            return new ErrorLogService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}