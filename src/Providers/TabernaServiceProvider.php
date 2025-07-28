<?php

namespace NanoDepo\Taberna\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class TabernaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/taberna.php', 'taberna');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/taberna.php' => config_path('taberna.php'),
        ], 'taberna');

        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        });

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views/shop', 'taberna');

//        Blade::componentNamespace('NanoDepo\\Taberna\\Views\\Components', 'taberna');

        Volt::mount([
            __DIR__.'/../resources/views'
        ]);
    }
}
