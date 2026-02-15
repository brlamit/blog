<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL; // ⭐ ADD THIS

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (class_exists(\Filament\Facades\Filament::class)) {
            Filament::registerPanel(fn () => Panel::make()->id('admin')->path('admin')->default());
        }
    }

    public function boot(): void
    {
        // ⭐ FORCE HTTPS FOR RENDER
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Existing middleware
        if (class_exists(\Illuminate\Routing\Router::class)) {
            $this->app['router']->pushMiddlewareToGroup('web', \App\Http\Middleware\LogRequestDetails::class);
        }
    }
}
