<?php

namespace Fontenele\Laravue\Providers;

use Fontenele\Laravue\Commands\LaravueCommand;
use Fontenele\Laravue\Commands\LaravueSeedCommand;
use Illuminate\Support\ServiceProvider;

class LaravueServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands(LaravueCommand::class);
        $this->commands(LaravueSeedCommand::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/../../database/seeds/' => database_path('seeds'),
        ]);
        $this->publishes([
            __DIR__ . '/../Models/' => app_path(),
        ]);
        $this->publishes([
            __DIR__ . '/../Controllers/' => app_path('Http/Controllers'),
        ]);
        $this->publishes([
            __DIR__ . '/../Middleware/' => app_path('Http/Middleware'),
        ]);
        $this->publishes([
            __DIR__ . '/../../resources/views/' => base_path('resources/views'),
        ], 'views');
        $this->publishes([
            __DIR__ . '/../../resources/js/' => base_path('resources/js'),
        ], 'js');
        $this->publishes([
            __DIR__ . '/../../resources/js/components/' => base_path('resources/js/components'),
        ]);
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'laravue');
    }
}
