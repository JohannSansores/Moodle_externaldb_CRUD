<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force root URL for reverse proxy subfolder
        if (config('app.url')) {
            URL::forceRootUrl(config('app.url'));
        }

        // Optional: if you have HTTPS in production
        if (request()->header('X-Forwarded-Proto') == 'https') {
            URL::forceScheme('https');
        }
    }
}
