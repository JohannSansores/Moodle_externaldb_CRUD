<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.custom');

        // Force Laravel to generate URLs with /admin-users prefix exactly once
        URL::forceRootUrl(config('app.url'));

        // Optional if using HTTPS
        if (request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
        //
        //URL::forceRootUrl(config('app.url'));

        //if (request()->header('X-Forwarded-Proto') === 'https') {
          //  URL::forceScheme('https');
        //}
    }
}
