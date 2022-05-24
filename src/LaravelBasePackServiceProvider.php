<?php

namespace Alangiacomin\LaravelBasePack;

use Alangiacomin\LaravelBasePack\Console\Commands\CreateCommand;
use Alangiacomin\LaravelBasePack\Console\Commands\CreateController;
use Alangiacomin\LaravelBasePack\Console\Commands\CreateEvent;
use Alangiacomin\LaravelBasePack\Console\Commands\CreateLogger;
use Alangiacomin\LaravelBasePack\Console\Commands\CreateRepository;
use Alangiacomin\LaravelBasePack\Console\Commands\Install;
use Alangiacomin\LaravelBasePack\Console\Commands\React;
use Alangiacomin\LaravelBasePack\Middleware\JsonResponse;
use Illuminate\Support\ServiceProvider;

class LaravelBasePackServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('LaravelBasePack', function () {
            return new LaravelBasePack();
        });
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/' => config_path(),
            __DIR__.'/../app/' => app_path(),
        ], 'basepack-install');

        $this->publishes([
            __DIR__.'/../database/' => database_path(),
        ], 'basepack-admin');

        $this->publishes([
            __DIR__.'/../react/' => base_path(),
        ], 'basepack-react');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole())
        {
            $this->commands([
                CreateCommand::class,
                CreateEvent::class,
                CreateLogger::class,
                CreateController::class,
                CreateRepository::class,
                Install::class,
                React::class,
            ]);
        }

        app('router')->aliasMiddleware('JsonResponse', JsonResponse::class);
    }
}
