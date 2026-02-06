<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force Laravel to generate URLs with /admin-users prefix exactly once
        URL::forceRootUrl(config('app.url'));

        // Optional if using HTTPS
        if (request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

                // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Force root URL only if APP_URL is set (works for local subfolder too)
        if (config('app.url')) {
            URL::forceRootUrl(config('app.url'));
        }
        //
        //URL::forceRootUrl(config('app.url'));

        //if (request()->header('X-Forwarded-Proto') === 'https') {
          //  URL::forceScheme('https');
        //}
    }
}
