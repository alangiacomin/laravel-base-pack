<?php

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class LaravelBasePackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        AboutCommand::add('Laravel Base Pack', fn () => ['Version' => '1.0.1']);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
    }
}
