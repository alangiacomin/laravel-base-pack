<?php

namespace Alangiacomin\LaravelBasePack;

use AlanGiacomin\LaravelBasePack\Core\ClassUtility;
use AlanGiacomin\LaravelBasePack\QueueObject\Contracts\IMessageBus;
use AlanGiacomin\LaravelBasePack\QueueObject\MessageBus;
use AlanGiacomin\LaravelBasePack\Repositories\IRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LaravelBasePackServiceProvider extends ServiceProvider
{
    /**
     * Automatic bindings by Laravel
     */
    public array $bindings = [];

    /**
     * Automatic singletons by Laravel
     */
    public array $singletons = [
        IMessageBus::class => MessageBus::class,
    ];

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->bootFromFiles();
    }

    /**
     * Register any application services.
     */
    public function register(): void {}

    private function bootFromFiles(): void
    {
        $handlerClasses = [];

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(app_path()));

        foreach ($files as $file) {
            if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $fullClassName = ClassUtility::fullClassName($file->getRealPath());
                if (class_exists($fullClassName)) {
                    if (ClassUtility::isCommand($fullClassName) || ClassUtility::isEvent($fullClassName)) {
                        $handlerClasses[$fullClassName] = $fullClassName.'Handler';
                    } elseif (ClassUtility::isRepository($fullClassName)) {
                        $interfaces = class_implements($fullClassName);
                        if (Arr::exists($interfaces, IRepository::class)) {
                            $this->app->bind(Arr::last($interfaces), $fullClassName);
                        }
                    }
                }
            }
        }

        app(IMessageBus::class)->register($handlerClasses);
    }
}
