<?php

namespace Alangiacomin\LaravelBasePack;

use Alangiacomin\LaravelBasePack\Console\Commands\CreateCommand;
use Alangiacomin\LaravelBasePack\Console\Commands\CreateEvent;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class LaravelBasePackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        AboutCommand::add('Laravel Base Pack', fn () => ['Version' => '1.0.0']);

        if ($this->app->runningInConsole()) {
            $this->commands(
                [
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
