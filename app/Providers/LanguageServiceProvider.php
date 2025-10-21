<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $userLanguage = Auth::user()->preferred_language ?? 'fr';
                $isFrench = $userLanguage === 'fr';
                $isEnglish = $userLanguage === 'en';
                
                $view->with([
                    'isFrench' => $isFrench,
                    'isEnglish' => $isEnglish,
                    'currentLanguage' => $userLanguage
                ]);
            } else {
                $view->with([
                    'isFrench' => true,
                    'isEnglish' => false,
                    'currentLanguage' => 'fr'
                ]);
            }
        });
    }
}
