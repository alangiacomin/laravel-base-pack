<?php

namespace Alangiacomin\LaravelBasePack;

use Alangiacomin\LaravelBasePack\Console\Commands\CreateCommand;
use Alangiacomin\LaravelBasePack\Console\Commands\CreateController;
use Alangiacomin\LaravelBasePack\Console\Commands\CreateEvent;
use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class LaravelBasePackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        AboutCommand::add(
            'Laravel Base Pack',
            fn () => [
                'Version' => InstalledVersions::getPrettyVersion('alangiacomin/laravel-base-pack'),
            ]
        );

        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    CreateController::class,
                    CreateCommand::class,
                    CreateEvent::class,
                ]
            );
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
    }
}
