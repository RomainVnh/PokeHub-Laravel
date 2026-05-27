<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        \Carbon\Carbon::setLocale('fr');

        // Allow up to 120s for pages that call the Pokemon TCG API
        set_time_limit(120);

        // Force HTTPS in production (behind Railway/proxy)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
