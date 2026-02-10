<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register a default Filament panel early so Filament console commands can use it.
        if (class_exists(\Filament\Facades\Filament::class)) {
            Filament::registerPanel(fn () => Panel::make()->id('admin')->path('admin')->default());
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Push temporary admin request logging middleware to the web group for diagnosis.
        if (class_exists(\Illuminate\Routing\Router::class)) {
            $this->app['router']->pushMiddlewareToGroup('web', \App\Http\Middleware\LogRequestDetails::class);
        }
    }
}
